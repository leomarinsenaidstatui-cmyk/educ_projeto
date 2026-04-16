@extends('layouts.app-sidebar')

@section('title', 'Painel')

@section('head')
<style>
    .hero {
        border-radius: 18px;
        border: 1px solid #d1dcf0;
        background: linear-gradient(145deg, #ffffff, #eef2ff);
        padding: 20px;
        box-shadow: 0 20px 40px rgba(15,23,42,.1);
    }
    .quick-grid {
        display:grid;
        gap:12px;
        grid-template-columns: repeat(auto-fill, minmax(220px,1fr));
    }
    .quick-item {
        text-decoration:none;
        border:1px solid #d1dcf0;
        border-radius:14px;
        padding:14px;
        color:#0f172a;
        font-weight:700;
        background:linear-gradient(145deg,#ffffff,#f8fafc);
        transition:.15s;
    }
    .quick-item:hover {
        transform: translateY(-3px);
        border-color:#99f6e4;
        color:#0f766e;
    }
</style>
@endsection

@section('content')
<div class="grid" style="max-width: 900px;">
    <section class="hero">
        <h1 style="margin:0 0 8px;">Painel</h1>
        <p style="margin:0;color:#64748b;">Navegue pelas paginas do projeto. O login e cadastro agora ficam na pagina Acesso.</p>
    </section>

    <section class="card">
        <h2 style="margin:0 0 10px;">Acessos Rapidos</h2>
        <div class="quick-grid">
            <a href="{{ route('home') }}" class="quick-item">Home (publicos)</a>
            <a href="{{ route('acesso') }}" class="quick-item">Acesso (login/cadastro)</a>
            <a href="{{ route('perfil') }}" class="quick-item">Perfil</a>
            <a href="{{ route('livros.publicar') }}" class="quick-item">Publicar Livro</a>
            <a href="{{ route('ebooks.lista') }}" class="quick-item">Biblioteca</a>
            <a href="{{ route('processar.pdf') }}" target="_blank" class="quick-item">PDF Exemplo</a>
        </div>
    </section>
</div>
@endsection
