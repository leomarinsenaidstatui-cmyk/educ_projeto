@extends('layouts.app-sidebar')

@section('title', 'Acesso')

@section('head')
<style>
    .access-wrap {
        display: grid;
        gap: 12px;
        grid-template-columns: 1fr;
        max-width: 980px;
    }
    @media (min-width: 900px) {
        .access-wrap {
            grid-template-columns: 1fr 1fr;
        }
    }
    .hero {
        border: 1px solid #d3def0;
        border-radius: 16px;
        background: linear-gradient(145deg, #ffffff, #eef2ff);
        padding: 18px;
    }
    .hero p { margin: 0; color: #5b6472; }
    .field { margin-bottom: 10px; }
    label {
        display: block;
        margin-bottom: 5px;
        font-size: .85rem;
        font-weight: 700;
        color: #334155;
    }
    input {
        width: 100%;
        border: 1px solid #c9d5ea;
        border-radius: 11px;
        padding: 10px;
        font-size: .92rem;
    }
    .btn-row {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .btn {
        border: none;
        border-radius: 999px;
        padding: 10px 14px;
        color: #fff;
        font-weight: 700;
        cursor: pointer;
    }
    .btn.login { background: linear-gradient(145deg, #0f766e, #14b8a6); }
    .btn.register { background: linear-gradient(145deg, #2563eb, #0ea5e9); }
    .btn.copy { background: linear-gradient(145deg, #374151, #1f2937); }
    .token-box {
        border: 1px dashed #9ab0d0;
        border-radius: 12px;
        background: #f8fafc;
        padding: 12px;
        color: #334155;
        word-break: break-all;
        font-size: .88rem;
    }
    .status {
        margin-top: 10px;
        padding: 10px;
        border-radius: 10px;
        font-weight: 700;
        display: none;
    }
    .status.ok { display: block; background: #ecfdf3; border: 1px solid #bbf7d0; color: #166534; }
    .status.err { display: block; background: #fff1f2; border: 1px solid #fecdd3; color: #9f1239; }
</style>
@endsection

@section('content')
<div class="grid" style="max-width: 980px;">
    <section class="hero">
        <h1 style="margin:0 0 8px;">Acesso</h1>
        <p>Esta pagina concentra login e cadastro. O token gerado fica salvo no navegador para uso nas outras telas.</p>
    </section>

    <div class="access-wrap">
        <section class="card">
            <h2 style="margin:0 0 10px;">Entrar ou Cadastrar</h2>
            <div class="field">
                <label for="email">Email</label>
                <input id="email" type="email" placeholder="usuario@email.com">
            </div>
            <div class="field">
                <label for="senha">Senha</label>
                <input id="senha" type="password" placeholder="********">
            </div>
            <div class="btn-row">
                <button id="btnLogin" class="btn login" type="button">Fazer Login</button>
                <button id="btnCadastro" class="btn register" type="button">Cadastrar</button>
            </div>
            <div id="status" class="status"></div>
        </section>

        <section class="card">
            <h2 style="margin:0 0 10px;">Token Atual</h2>
            <div id="tokenBox" class="token-box">Nenhum token salvo.</div>
            <div class="btn-row" style="margin-top:10px;">
                <button id="btnCopy" class="btn copy" type="button">Copiar Token</button>
            </div>
            <p style="margin:10px 0 0; color:#64748b; font-size:.85rem;">
                Use este token no Perfil e na pagina Publicar Livro.
            </p>
        </section>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const KEY = 'ebook_token';
    const status = document.getElementById('status');
    const tokenBox = document.getElementById('tokenBox');
    const emailInput = document.getElementById('email');
    const senhaInput = document.getElementById('senha');

    function setStatus(msg, ok) {
        status.textContent = msg;
        status.className = 'status ' + (ok ? 'ok' : 'err');
    }

    function renderToken() {
        const token = localStorage.getItem(KEY) || '';
        tokenBox.textContent = token || 'Nenhum token salvo.';
    }

    async function parseRes(res) {
        const type = res.headers.get('content-type') || '';
        if (type.includes('application/json')) return await res.json();
        return { msg: await res.text() };
    }

    document.getElementById('btnCadastro').addEventListener('click', async function () {
        const email = emailInput.value.trim();
        const senha = senhaInput.value.trim();
        if (!email || !senha) {
            setStatus('Informe email e senha.', false);
            return;
        }

        try {
            const nome = email.split('@')[0] || 'usuario';
            const res = await fetch('/api/usuarios/cadastro', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ nome, email, senha, telefone: '', nascimento: '', genero: '' }),
            });
            const data = await parseRes(res);
            if (!res.ok || data.erro === 's') {
                setStatus(data.msg || 'Falha no cadastro.', false);
                return;
            }
            setStatus('Cadastro realizado com sucesso.', true);
        } catch (e) {
            setStatus('Erro no cadastro: ' + e.message, false);
        }
    });

    document.getElementById('btnLogin').addEventListener('click', async function () {
        const email = emailInput.value.trim();
        const senha = senhaInput.value.trim();
        if (!email || !senha) {
            setStatus('Informe email e senha.', false);
            return;
        }

        try {
            const res = await fetch('/api/usuarios/login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, senha }),
            });
            const data = await parseRes(res);
            if (!res.ok || data.erro === 's' || !data.token) {
                setStatus(data.msg || 'Falha no login.', false);
                return;
            }

            localStorage.setItem(KEY, data.token);
            renderToken();
            setStatus('Login realizado. Token salvo no navegador.', true);
        } catch (e) {
            setStatus('Erro no login: ' + e.message, false);
        }
    });

    document.getElementById('btnCopy').addEventListener('click', async function () {
        const token = localStorage.getItem(KEY) || '';
        if (!token) {
            setStatus('Nenhum token para copiar.', false);
            return;
        }

        try {
            await navigator.clipboard.writeText(token);
            setStatus('Token copiado.', true);
        } catch (e) {
            setStatus('Nao foi possivel copiar token.', false);
        }
    });

    renderToken();
</script>
@endsection

