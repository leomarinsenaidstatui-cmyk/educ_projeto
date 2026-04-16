<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>{{ $titulo ?? 'Ebook' }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.45;
            color: #1f2937;
            margin: 24px;
        }
        h1 {
            font-size: 22px;
            margin-bottom: 8px;
        }
        .meta {
            margin-bottom: 16px;
            color: #374151;
            font-size: 11px;
        }
        .resumo {
            background: #f3f4f6;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 16px;
        }
        .conteudo {
            text-align: justify;
            white-space: pre-line;
        }
    </style>
</head>
<body>
    <h1>{{ $titulo ?? 'Sem titulo' }}</h1>

    <div class="meta">
        <strong>Autor:</strong> {{ $autor ?? 'Nao informado' }}<br>
        @if(!empty($editora))
            <strong>Editora:</strong> {{ $editora }}<br>
        @endif
        @if(!empty($data_publicacao))
            <strong>Data de publicacao:</strong> {{ $data_publicacao }}<br>
        @endif
    </div>

    @if(!empty($resumo))
        <div class="resumo">
            <strong>Resumo:</strong><br>
            {{ $resumo }}
        </div>
    @endif

    <div class="conteudo">
        {!! nl2br(e($conteudo ?? '')) !!}
    </div>
</body>
</html>

