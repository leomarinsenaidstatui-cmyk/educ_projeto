<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\EbookController;
use App\Http\Controllers\PDFController;
use App\Models\Ebook;

Route::get('/', function () {
    $ebooksPublicos = Schema::hasTable('ebooks')
        ? Ebook::where('publico', true)->orderByDesc('id')->get()
        : collect();
    return view('home', compact('ebooksPublicos'));
})->name('root');

Route::get('/home', function () {
    $ebooksPublicos = Schema::hasTable('ebooks')
        ? Ebook::where('publico', true)->orderByDesc('id')->get()
        : collect();
    return view('home', compact('ebooksPublicos'));
})->name('home');

Route::get('/perfil', function () {
    return view('perfil');
})->name('perfil');

Route::get('/acesso', function () {
    return view('acesso');
})->name('acesso');

Route::get('/publicar-livro', function () {
    return view('publicar-livro');
})->name('livros.publicar');

Route::get('/painel', function () {
    return view('welcome');
})->name('painel');

Route::get("/processar-pdf",[PDFController:: class,"generate"])->name("processar.pdf");
Route::get('/ebooks', [EbookController::class, 'listaView'])->name('ebooks.lista');
Route::get('/ebooks/{id}/ler', [PDFController::class, 'streamEbook'])->whereNumber('id')->name('ebooks.ler');
