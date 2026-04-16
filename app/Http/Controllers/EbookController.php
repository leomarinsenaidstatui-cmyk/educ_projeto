<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\TokenUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EbookController extends Controller
{
    public function salvar(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'autor' => 'required|string|max:255',
            'editora' => 'nullable|string|max:255',
            'data_publicacao' => 'required|date',
            'categoria' => 'nullable|string|max:120',
            'resumo' => 'required|string',
            'conteudo' => 'required|string',
            'arquivo_pdf' => 'nullable|file|mimes:pdf|max:20480',
            'publico' => 'nullable|boolean',
        ]);

        $usuario = $request->attributes->get('usuario');

        $ebook = new Ebook();
        $ebook->user_id = $usuario->id;
        $ebook->titulo = $request->titulo;
        $ebook->autor = $request->autor;
        $ebook->editora = $request->editora;
        $ebook->data_publicacao = $request->data_publicacao;
        $ebook->categoria = $request->categoria;
        $ebook->resumo = $request->resumo;
        $ebook->conteudo = $request->conteudo;
        $ebook->publico = $request->has('publico') ? (bool) $request->boolean('publico') : true;

        if ($request->hasFile('arquivo_pdf')) {
            $ebook->arquivo_pdf = $request->file('arquivo_pdf')->store('ebooks/pdf');
        }

        $ebook->save();

        return response()->json([
            'erro' => 'n',
            'msg' => 'Ebook publicado com sucesso.',
            'ebook' => $ebook,
        ], 201);
    }

    public function listar()
    {
        $ebooks = Ebook::where('publico', true)->orderByDesc('id')->get();

        return response()->json([
            'erro' => 'n',
            'ebooks' => $ebooks,
        ]);
    }

    public function exibir(Request $request, int $id)
    {
        $ebook = Ebook::find($id);

        if (! $ebook) {
            return response()->json([
                'erro' => 's',
                'msg' => 'Ebook nao encontrado.',
            ], 404);
        }

        if (! $ebook->publico) {
            $usuario = $this->resolveUsuarioByToken($request);
            if (! $usuario || $usuario->id !== $ebook->user_id) {
                return response()->json([
                    'erro' => 's',
                    'msg' => 'Ebook privado: acesso negado.',
                ], 403);
            }
        }

        return response()->json([
            'erro' => 'n',
            'ebook' => $ebook,
        ]);
    }

    public function atualizar(Request $request, int $id)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'autor' => 'required|string|max:255',
            'editora' => 'nullable|string|max:255',
            'data_publicacao' => 'required|date',
            'categoria' => 'nullable|string|max:120',
            'resumo' => 'required|string',
            'conteudo' => 'required|string',
            'arquivo_pdf' => 'nullable|file|mimes:pdf|max:20480',
            'publico' => 'nullable|boolean',
        ]);

        $ebook = Ebook::find($id);
        if (! $ebook) {
            return response()->json([
                'erro' => 's',
                'msg' => 'Ebook nao encontrado.',
            ], 404);
        }

        $usuario = $request->attributes->get('usuario');
        if ($usuario->id !== $ebook->user_id) {
            return response()->json([
                'erro' => 's',
                'msg' => 'Usuario nao pode alterar ebook de outro usuario.',
            ], 403);
        }

        $ebook->titulo = $request->titulo;
        $ebook->autor = $request->autor;
        $ebook->editora = $request->editora;
        $ebook->data_publicacao = $request->data_publicacao;
        $ebook->categoria = $request->categoria;
        $ebook->resumo = $request->resumo;
        $ebook->conteudo = $request->conteudo;
        $ebook->publico = $request->has('publico') ? (bool) $request->boolean('publico') : $ebook->publico;

        if ($request->hasFile('arquivo_pdf')) {
            if ($ebook->arquivo_pdf) {
                Storage::delete($ebook->arquivo_pdf);
            }
            $ebook->arquivo_pdf = $request->file('arquivo_pdf')->store('ebooks/pdf');
        }

        $ebook->save();

        return response()->json([
            'erro' => 'n',
            'msg' => 'Ebook atualizado com sucesso.',
            'ebook' => $ebook,
        ]);
    }

    public function deletar(Request $request, int $id)
    {
        $ebook = Ebook::find($id);
        if (! $ebook) {
            return response()->json([
                'erro' => 's',
                'msg' => 'Ebook nao encontrado.',
            ], 404);
        }

        $usuario = $request->attributes->get('usuario');
        if ($usuario->id !== $ebook->user_id) {
            return response()->json([
                'erro' => 's',
                'msg' => 'Usuario nao pode excluir ebook de outro usuario.',
            ], 403);
        }

        if ($ebook->arquivo_pdf) {
            Storage::delete($ebook->arquivo_pdf);
        }

        $ebook->delete();

        return response()->json([
            'erro' => 'n',
            'msg' => 'Ebook removido com sucesso.',
        ]);
    }

    public function listaView()
    {
        $ebooks = Ebook::where('publico', true)->orderByDesc('id')->get();

        return view('ebooks.lista', compact('ebooks'));
    }

    public function listarPublicos()
    {
        $ebooks = Ebook::where('publico', true)->orderByDesc('id')->get();

        return response()->json([
            'erro' => 'n',
            'ebooks_publicos' => $ebooks,
        ]);
    }

    public function listarPrivadosDoUsuario(Request $request)
    {
        $usuario = $request->attributes->get('usuario');

        $ebooks = Ebook::where('user_id', $usuario->id)
            ->where('publico', false)
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'erro' => 'n',
            'ebooks_privados' => $ebooks,
        ]);
    }

    public function listarDoUsuario(Request $request)
    {
        $usuario = $request->attributes->get('usuario');

        $ebooks = Ebook::where('user_id', $usuario->id)
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'erro' => 'n',
            'ebooks_usuario' => $ebooks,
        ]);
    }

    private function resolveUsuarioByToken(Request $request)
    {
        $tokenValor = $request->input('token');
        if (! $tokenValor && $request->bearerToken()) {
            $tokenValor = $request->bearerToken();
        }

        if (! $tokenValor) {
            return null;
        }

        $token = TokenUser::where('token', $tokenValor)
            ->where('valido_ate', '>=', Carbon::now())
            ->first();

        if (! $token) {
            return null;
        }

        return $token->usuario;
    }
}
