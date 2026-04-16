@extends('layouts.app-sidebar')

@section('title', 'Publicar Livro')

@section('head')
<style>
    .hero {
        border: 1px solid #d1dcf0;
        border-radius: 18px;
        background: linear-gradient(145deg, #ffffff, #eef2ff);
        padding: 20px;
        box-shadow: 0 18px 34px rgba(15, 23, 42, 0.1);
    }
    .form-grid { display: grid; gap: 10px; }
    .row-2 { display: grid; gap: 10px; grid-template-columns: 1fr; }
    @media (min-width: 760px) {
        .row-2 { grid-template-columns: 1fr 1fr; }
    }
    label {
        display: block;
        margin-bottom: 5px;
        font-size: .85rem;
        font-weight: 700;
        color: #334155;
    }
    input, textarea, select {
        width: 100%;
        border: 1px solid #c9d5ea;
        border-radius: 12px;
        padding: 10px;
        font-size: .93rem;
    }
    textarea { min-height: 110px; resize: vertical; }
    .btn-main {
        border: none;
        border-radius: 999px;
        background: linear-gradient(145deg, #0f766e, #14b8a6);
        color: #fff;
        padding: 10px 14px;
        font-weight: 700;
        cursor: pointer;
    }
    .status {
        margin-top: 10px;
        padding: 10px;
        border-radius: 10px;
        font-weight: 600;
        display: none;
    }
    .status.ok { display: block; background: #ecfdf3; border: 1px solid #bbf7d0; color: #166534; }
    .status.err { display: block; background: #fff1f2; border: 1px solid #fecdd3; color: #9f1239; }
</style>
@endsection

@section('content')
<div class="grid" style="max-width: 920px;">
    <section class="hero">
        <h1 style="margin:0 0 8px;">Publicar Novo Livro</h1>
        <p style="margin:0;color:#64748b;">Preencha os campos e envie. O token do usuario e obrigatorio.</p>
    </section>

    <section class="card">
        <form id="formPublicar" class="form-grid">
            <div>
                <label for="token">Token</label>
                <input id="token" name="token" type="text" placeholder="Token salvo na pagina Acesso">
            </div>

            <div class="row-2">
                <div>
                    <label for="titulo">Titulo</label>
                    <input id="titulo" name="titulo" type="text" required>
                </div>
                <div>
                    <label for="autor">Autor</label>
                    <input id="autor" name="autor" type="text" required>
                </div>
            </div>

            <div class="row-2">
                <div>
                    <label for="editora">Editora</label>
                    <input id="editora" name="editora" type="text">
                </div>
                <div>
                    <label for="categoria">Categoria</label>
                    <input id="categoria" name="categoria" type="text">
                </div>
            </div>

            <div class="row-2">
                <div>
                    <label for="data_publicacao">Data de publicacao</label>
                    <input id="data_publicacao" name="data_publicacao" type="date" required>
                </div>
                <div>
                    <label for="publico">Visibilidade</label>
                    <select id="publico" name="publico">
                        <option value="1">Publico</option>
                        <option value="0">Privado</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="resumo">Resumo</label>
                <textarea id="resumo" name="resumo" required></textarea>
            </div>

            <div>
                <label for="conteudo">Conteudo</label>
                <textarea id="conteudo" name="conteudo" style="min-height:150px;" required></textarea>
            </div>

            <div>
                <label for="arquivo_pdf">Arquivo PDF (opcional)</label>
                <input id="arquivo_pdf" name="arquivo_pdf" type="file" accept="application/pdf">
            </div>

            <div>
                <button class="btn-main" type="submit">Publicar Livro</button>
            </div>
        </form>

        <div id="status" class="status"></div>
    </section>
</div>
@endsection

@section('scripts')
<script>
    const form = document.getElementById('formPublicar');
    const statusBox = document.getElementById('status');
    const tokenInput = document.getElementById('token');

    tokenInput.value = localStorage.getItem('ebook_token') || '';

    function setStatus(msg, ok) {
        statusBox.textContent = msg;
        statusBox.className = 'status ' + (ok ? 'ok' : 'err');
    }

    async function parseRes(res) {
        const type = res.headers.get('content-type') || '';
        if (type.includes('application/json')) return await res.json();
        return { msg: await res.text() };
    }

    form.addEventListener('submit', async function (ev) {
        ev.preventDefault();

        const token = tokenInput.value.trim();
        if (!token) {
            setStatus('Informe o token antes de publicar.', false);
            return;
        }

        const fd = new FormData(form);
        fd.set('token', token);

        try {
            const res = await fetch('/api/ebooks', {
                method: 'POST',
                body: fd,
            });
            const data = await parseRes(res);

            if (!res.ok || data.erro === 's') {
                setStatus(data.msg || 'Falha ao publicar livro.', false);
                return;
            }

            setStatus('Livro publicado com sucesso.', true);
            form.reset();
            tokenInput.value = localStorage.getItem('ebook_token') || token;
        } catch (e) {
            setStatus('Erro ao publicar: ' + e.message, false);
        }
    });
</script>
@endsection

