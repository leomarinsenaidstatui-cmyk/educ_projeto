<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\TokenUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PDFController extends Controller
{
    public function generate()
    {
        $data = ['titulo' => 'Ebook de Exemplo', 'autor' => 'Autor Exemplo', 'conteudo' => 'Conteudo de exemplo do ebook.'];
        $pdf = Pdf::loadView('pdf.ebook', $data)->setPaper('a5', 'portrait');

        return $pdf->stream('ebook-exemplo.pdf');
    }

    public function streamEbook(Request $request, int $id)
    {
        $ebook = Ebook::find($id);
        if (! $ebook) {
            abort(404, 'Ebook nao encontrado.');
        }

        if (! $ebook->publico) {
            $usuario = $this->resolveUsuarioByToken($request);
            if (! $usuario || $usuario->id !== $ebook->user_id) {
                abort(403, 'Ebook privado. Informe token do autor para leitura.');
            }
        }

        if ($ebook->arquivo_pdf && Storage::exists($ebook->arquivo_pdf)) {
            return response()->file(Storage::path($ebook->arquivo_pdf), [
                'Content-Type' => 'application/pdf',
            ]);
        }

        $pdf = Pdf::loadView('pdf.ebook', [
            'titulo' => $ebook->titulo,
            'autor' => $ebook->autor,
            'conteudo' => $ebook->conteudo,
            'resumo' => $ebook->resumo,
            'editora' => $ebook->editora,
            'data_publicacao' => optional($ebook->data_publicacao)->format('d/m/Y'),
        ])->setPaper('a5', 'portrait');

        return $pdf->stream('ebook-'.$ebook->id.'.pdf');
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
