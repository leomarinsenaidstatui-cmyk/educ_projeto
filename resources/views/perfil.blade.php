@extends('layouts.app-sidebar')

@section('title', 'Perfil')

@section('head')
<style>
    .hero {
        border: 1px solid #d1dcf0;
        border-radius: 18px;
        background: linear-gradient(145deg, #ffffff, #eef2ff);
        padding: 20px;
        box-shadow: 0 18px 34px rgba(15, 23, 42, 0.1);
    }
    .status { margin-top:10px; padding:10px 12px; border-radius:10px; font-weight:600; display:none; }
    .status.ok { display:block; border:1px solid #bbf7d0; background:#ecfdf3; color:#166534; }
    .status.err { display:block; border:1px solid #fecdd3; background:#fff1f2; color:#9f1239; }
    .list { display:grid; gap:8px; margin-top:10px; }
    .item { border:1px solid #d9e2ec; border-radius:10px; background:#fff; padding:9px; font-size:.9rem; }
    .item .read-link {
        display: inline-block;
        margin-top: 6px;
        text-decoration: none;
        color: #0f766e;
        font-weight: 700;
        font-size: .86rem;
    }
    .item .publish-btn {
        margin-top: 8px;
        border: none;
        border-radius: 999px;
        padding: 7px 10px;
        font-size: .8rem;
        font-weight: 700;
        cursor: pointer;
        color: #fff;
        background: linear-gradient(145deg, #0f766e, #14b8a6);
    }
</style>
@endsection

@section('content')
<div class="grid" style="grid-template-columns: 1fr; max-width: 780px;">
    <section class="hero">
        <h1 style="margin:0 0 8px;">Perfil</h1>
        <p style="margin:0;color:#64748b;">Carregue os dados do usuario pelo token salvo na pagina Acesso.</p>
    </section>

    <section class="card">
        <h1 style="margin:0 0 8px;">Perfil</h1>
        <p style="margin:0 0 10px; color:#64748b;">Informe o token para consultar dados do usuario logado e seus livros privados.</p>
        <label for="token" style="font-weight:700; font-size:.9rem;">Token</label>
        <input id="token" type="text" style="width:100%; border:1px solid #cbd5e1; border-radius:10px; padding:10px;" placeholder="Cole o token aqui">
        <div style="margin-top:8px; color:#64748b; font-size:.84rem;">Sem token? Acesse a pagina <a href="{{ route('acesso') }}" style="color:#0f766e; font-weight:700;">Acesso</a>.</div>
        <button id="btnCarregar" style="margin-top:10px; border:none; border-radius:999px; background:#0f766e; color:#fff; padding:10px 14px; font-weight:700; cursor:pointer;">Carregar Perfil</button>
        <div id="status" class="status"></div>
    </section>

    <section class="card" id="perfilBox">
        <h2 style="margin:0 0 8px;">Dados do Usuario</h2>
        <div id="perfilConteudo" style="color:#64748b;">Aguardando token.</div>
    </section>

    <section class="card">
        <h2 style="margin:0 0 8px;">Livros Privados do Usuario</h2>
        <div id="privados" class="list">
            <div class="item" style="color:#64748b;">Aguardando token.</div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
async function parseRes(res){
  const t = res.headers.get('content-type') || '';
  if(t.includes('application/json')) return await res.json();
  return { msg: await res.text() };
}

async function tornarLivroPublico(livro, token) {
  try {
    const fd = new FormData();
    fd.append('token', token);
    fd.append('_method', 'PUT');
    fd.append('titulo', livro.titulo || '');
    fd.append('autor', livro.autor || '');
    fd.append('editora', livro.editora || '');
    fd.append('data_publicacao', (livro.data_publicacao || '').slice(0, 10));
    fd.append('categoria', livro.categoria || '');
    fd.append('resumo', livro.resumo || '');
    fd.append('conteudo', livro.conteudo || '');
    fd.append('publico', '1');

    const res = await fetch('/api/ebooks/' + livro.id, {
      method: 'POST',
      body: fd
    });
    const data = await parseRes(res);
    if (!res.ok || data.erro === 's') {
      setStatus((data.msg || 'Falha ao tornar livro publico.'), false);
      return;
    }

    setStatus('Livro #' + livro.id + ' agora esta publico.', true);
    carregarPerfil();
  } catch (e) {
    setStatus('Erro ao publicar livro privado: ' + e.message, false);
  }
}

function setStatus(msg, ok){
  const s = document.getElementById('status');
  s.textContent = msg;
  s.className = 'status ' + (ok ? 'ok' : 'err');
}

async function carregarPerfil() {
  const token = document.getElementById('token').value.trim();
  if(!token){
    setStatus('Informe o token.', false);
    return false;
  }

  try {
    const [perfilRes, privRes] = await Promise.all([
      fetch('/api/usuarios/perfil?token=' + encodeURIComponent(token)),
      fetch('/api/ebooks/privados/me?token=' + encodeURIComponent(token))
    ]);

    const perfil = await parseRes(perfilRes);
    const privados = await parseRes(privRes);

    if(!perfilRes.ok || perfil.erro === 's'){
      setStatus((perfil.msg || 'Falha ao carregar perfil.'), false);
      return;
    }

    const u = perfil.usuario || {};
    const r = perfil.resumo_publicacao || { publicos: 0, privados: 0 };
    document.getElementById('perfilConteudo').innerHTML =
      '<div><strong>Nome:</strong> ' + (u.nome || '-') + '</div>' +
      '<div><strong>Email:</strong> ' + (u.email || '-') + '</div>' +
      '<div><strong>Livros Publicos:</strong> ' + r.publicos + '</div>' +
      '<div><strong>Livros Privados:</strong> ' + r.privados + '</div>';

    const box = document.getElementById('privados');
    box.innerHTML = '';
    const livros = privados.ebooks_privados || [];
    if(!livros.length){
      box.innerHTML = '<div class="item" style="color:#64748b;">Nenhum livro privado.</div>';
    } else {
      livros.forEach((livro) => {
        const div = document.createElement('div');
        div.className = 'item';
        const lerUrl = '/ebooks/' + livro.id + '/ler?token=' + encodeURIComponent(token);
        div.innerHTML =
          '<strong>' + livro.titulo + '</strong><br>' +
          '<span style="color:#64748b;">Autor: ' + (livro.autor || '-') + '</span><br>' +
          '<a class="read-link" href="' + lerUrl + '" target="_blank">Ler PDF privado</a>';

        const publishBtn = document.createElement('button');
        publishBtn.className = 'publish-btn';
        publishBtn.type = 'button';
        publishBtn.textContent = 'Tornar Publico';
        publishBtn.addEventListener('click', function () {
          tornarLivroPublico(livro, token);
        });
        div.appendChild(publishBtn);
        box.appendChild(div);
      });
    }

    setStatus('Perfil carregado com sucesso.', true);
    return true;
  } catch (e) {
    setStatus('Erro: ' + e.message, false);
    return false;
  }
}

document.getElementById('btnCarregar').addEventListener('click', carregarPerfil);

(function inicializarToken() {
  const token = localStorage.getItem('ebook_token') || '';
  if (token) {
    document.getElementById('token').value = token;
    carregarPerfil();
  }
})();
</script>
@endsection
