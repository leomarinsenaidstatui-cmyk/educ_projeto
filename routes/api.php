<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EbookController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\UsuarioController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/usuarios/cadastro', [UsuarioController::class, 'cadastraUsuario']);
Route::post('/usuarios/login', [UsuarioController::class, 'login']);
Route::get('/ebooks/publicos', [EbookController::class, 'listarPublicos']);

Route::get('/ebooks', [EbookController::class, 'listar']);
Route::get('/ebooks/{id}', [EbookController::class, 'exibir'])->whereNumber('id');
Route::get('/ebooks/{id}/pdf', [PDFController::class, 'streamEbook'])->whereNumber('id');

Route::middleware('auth.api')->group(function () {
    Route::get('/usuarios/perfil', [UsuarioController::class, 'perfil']);
    Route::post('/ebooks', [EbookController::class, 'salvar']);
    Route::get('/ebooks/me', [EbookController::class, 'listarDoUsuario']);
    Route::get('/ebooks/privados/me', [EbookController::class, 'listarPrivadosDoUsuario']);
    Route::put('/ebooks/{id}', [EbookController::class, 'atualizar'])->whereNumber('id');
    Route::delete('/ebooks/{id}', [EbookController::class, 'deletar'])->whereNumber('id');
});
