<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\TokenUser;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function cadastraUsuario(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:usuarios,email',
            'senha' => 'required|string|min:6',
            'telefone' => 'nullable|string|max:20',
            'nascimento' => 'nullable|date',
            'genero' => 'nullable|string|max:50',
        ]);

        $usuario = Usuario::create([
            'nome' => $request->nome,
            'email' => $request->email,
            'senha' => md5($request->senha),
            'telefone' => $request->telefone,
            'nascimento' => $request->nascimento,
            'genero' => $request->genero,
        ]);

        return response()->json([
            'erro' => 'n',
            'msg' => 'Usuario cadastrado com sucesso.',
            'usuario' => $usuario,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'senha' => 'required|string',
        ]);

        $usuario = Usuario::where('email', $request->email)
            ->where('senha', md5($request->senha))
            ->first();

        if (! $usuario) {
            return response()->json([
                'erro' => 's',
                'msg' => 'Usuario nao encontrado.',
            ], 401);
        }

        TokenUser::where('user_id', $usuario->id)->delete();

        $token = new TokenUser();
        $token->user_id = $usuario->id;
        $token->token = md5($usuario->id.$usuario->email.now()->format('Y-m-d H:i:s.u'));
        $token->valido_ate = Carbon::now()->addDays(7);
        $token->save();

        return response()->json([
            'erro' => 'n',
            'msg' => 'Usuario logado.',
            'token' => $token->token,
            'valido_ate' => $token->valido_ate,
        ]);
    }

    public function perfil(Request $request)
    {
        $usuario = $request->attributes->get('usuario');

        $qtdPublicos = Ebook::where('user_id', $usuario->id)->where('publico', true)->count();
        $qtdPrivados = Ebook::where('user_id', $usuario->id)->where('publico', false)->count();

        return response()->json([
            'erro' => 'n',
            'usuario' => [
                'id' => $usuario->id,
                'nome' => $usuario->nome,
                'email' => $usuario->email,
                'telefone' => $usuario->telefone,
            ],
            'resumo_publicacao' => [
                'publicos' => $qtdPublicos,
                'privados' => $qtdPrivados,
            ],
        ]);
    }
}
