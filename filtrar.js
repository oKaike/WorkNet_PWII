// Filtro de pesquisa
function search() {
  let input = document.getElementById('searchbar').value.toLowerCase();
  let x = document.getElementsByClassName('alternar');

  for (let i = 0; i < x.length; i++) {
    if (!x[i].innerHTML.toLowerCase().includes(input)) {
      x[i].style.display = "none";
    } else {
      x[i].style.display = "list-item";
    }
  }
}

// Candidatar-se (desativa botão)
function candidatar() {
  const botao = document.getElementById("btn-candidatar");
  if (botao) {
    botao.innerText = "Candidatado!";
    botao.disabled = true;
  }
}

document.addEventListener('DOMContentLoaded', function () {
  const opcoes = document.querySelectorAll('.alternar');
  const visualizador = document.getElementById('visualizador');

  const dadosVagas = { /* ... mesmo conteúdo ... */ };
  const dadosEmpresas = { /* ... mesmo conteúdo ... */ };

  opcoes.forEach(opcao => {
    opcao.addEventListener('click', () => {
      const titulo = opcao.getAttribute('data-titulo');
      const id = opcao.getAttribute('data-id');

      visualizador.setAttribute('data-id-vaga', id);

      visualizador.innerHTML = `
        <h2>${titulo}</h2>
        <div class="botoes-acoes">
          <button id="btn-candidatar" onclick="candidatar()">Candidatar-se</button>
          <div class="botoes-secundarios">
            <button class="botao-vaga">Vaga</button>
            <button class="botao-empresa">Empresa</button>
          </div>
        </div>
        <div class="vaga-detalhes" style="margin-top: 10px;"></div>
      `;
    });
  });

  visualizador.addEventListener('click', function (e) {
    const id = visualizador.getAttribute('data-id-vaga');
    const containerDetalhes = visualizador.querySelector('.vaga-detalhes');

    if (!id || !containerDetalhes) return;

    if (e.target.classList.contains('botao-vaga')) {
      containerDetalhes.innerHTML = dadosVagas[id] || '<p>Detalhes da vaga não disponíveis.</p>';
    }

    if (e.target.classList.contains('botao-empresa')) {
      containerDetalhes.innerHTML = dadosEmpresas[id] || '<p>Informações da empresa não disponíveis.</p>';
    }
  });
});
// Função para atualizar o botão candidatar
function candidatar(botao) {
  botao.innerText = "Candidatado!";
  botao.disabled = true;
}

// Quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', function () {
  const visualizador = document.getElementById('visualizador');

  // Delegação de evento para botão candidatar-se dentro do visualizador
  visualizador.addEventListener('click', function (e) {
    if (e.target.classList.contains('btn-candidatar')) {
      candidatar(e.target);
    }

    // Seu código para tratar botao-vaga e botao-empresa continua aqui...
    const id = visualizador.getAttribute('data-id-vaga');
    const containerDetalhes = visualizador.querySelector('.vaga-detalhes');
    if (!id || !containerDetalhes) return;

    if (e.target.classList.contains('botao-vaga')) {
      containerDetalhes.innerHTML = dadosVagas[id] || '<p>Detalhes da vaga não disponíveis.</p>';
    }

    if (e.target.classList.contains('botao-empresa')) {
      containerDetalhes.innerHTML = dadosEmpresas[id] || '<p>Informações da empresa não disponíveis.</p>';
    }
  });

  // O resto do seu código para inicializar as vagas e o visualizador continua aqui...
});
