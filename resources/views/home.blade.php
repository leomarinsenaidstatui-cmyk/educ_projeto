@extends('layouts.app-sidebar')

@section('title', 'Home - Livros Publicos')

@section('head')
<style>
    .hero {
        background: linear-gradient(145deg, #ffffff, #eef2ff);
        border: 1px solid #d1dcf0;
        border-radius: 18px;
        padding: 20px;
        box-shadow: 0 18px 36px rgba(15, 23, 42, 0.1);
    }
    .books-grid {
        margin-top: 14px;
        display: grid;
        gap: 14px;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    }
    .book-card {
        transition: transform .15s, box-shadow .15s;
        background: linear-gradient(145deg, #ffffff, #f8fafc);
    }
    .book-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 28px rgba(15, 23, 42, 0.14);
    }
    .book-title {
        margin:0 0 6px;
        font-size:1.06rem;
        line-height:1.25;
    }
    .read-link {
        font-weight:700;
        color:#0f766e;
        text-decoration:none;
        border-bottom:1px dashed #0f766e7d;
        padding-bottom:1px;
    }
</style>
@endsection

@section('content')
<div class="hero">
    <h1 style="margin:0 0 8px;">Home</h1>
    <p style="margin:0;color:#64748b;">Aqui aparecem apenas os livros publicos publicados no site.</p>
</div>

<div class="books-grid">
    @forelse($ebooksPublicos as $ebook)
        <article class="card book-card">
            <h2 class="book-title">{{ $ebook->titulo }}</h2>
            <div style="font-size:.88rem; color:#64748b; margin-bottom:8px;">Autor: {{ $ebook->autor }}</div>
            <div style="font-size:.9rem; color:#334155; margin-bottom:10px;">{{ \Illuminate\Support\Str::limit($ebook->resumo, 130) }}</div>
            <a href="{{ route('ebooks.ler', $ebook->id) }}" target="_blank" class="read-link">Ler PDF</a>
        </article>
    @empty
        <div class="card">Nenhum livro publico encontrado.</div>
    @endforelse
</div>
@endsection
