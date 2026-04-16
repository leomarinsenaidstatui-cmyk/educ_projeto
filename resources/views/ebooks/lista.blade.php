@extends('layouts.app-sidebar')

@section('title', 'Biblioteca')

@section('head')
<style>
    .hero {
        border: 1px solid #d1dcf0;
        border-radius: 18px;
        background: linear-gradient(145deg, #ffffff, #eef2ff);
        padding: 20px;
        box-shadow: 0 18px 34px rgba(15, 23, 42, 0.1);
    }
    .book-grid {
        margin-top: 14px;
        display: grid;
        gap: 14px;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    }
    .book-card {
        background: linear-gradient(145deg, #ffffff, #f8fafc);
        transition: transform .15s, box-shadow .15s;
    }
    .book-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 28px rgba(15, 23, 42, 0.14);
    }
    .read-link {
        font-weight: 700;
        color: #0f766e;
        text-decoration: none;
        border-bottom: 1px dashed #0f766e7d;
        padding-bottom: 1px;
    }
</style>
@endsection

@section('content')
<div class="hero">
    <h1 style="margin:0 0 8px;">Biblioteca</h1>
    <p style="margin:0; color:#64748b;">Lista geral de ebooks publicos.</p>
</div>

<div class="book-grid">
    @forelse($ebooks as $ebook)
        <article class="card book-card">
            <h2 style="margin:0 0 6px; font-size:1.02rem;">{{ $ebook->titulo }}</h2>
            <div style="font-size:.86rem; color:#64748b; margin-bottom:8px;">Autor: {{ $ebook->autor }}</div>
            <div style="font-size:.9rem; color:#334155; margin-bottom:10px;">{{ \Illuminate\Support\Str::limit($ebook->resumo, 120) }}</div>
            <a href="{{ route('ebooks.ler', $ebook->id) }}" target="_blank" class="read-link">Ler PDF</a>
        </article>
    @empty
        <div class="card">Nenhum ebook publico encontrado.</div>
    @endforelse
</div>
@endsection

