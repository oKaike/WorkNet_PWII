//Filtro 
function search(){
    let input= document.getElementById('searchbar').value;
    input = input.toLowerCase();
    let x= document.getElementsByClassName('alternar');

    for(let i=0; i<x.length; i++){
        if(!x[i].innerHTML.toLowerCase().includes(input)){
            x[i].style.display = "none"; 
        } else{
            x[i].style.display = "list-item";
        }
    }
}




document.addEventListener('DOMContentLoaded', function () { 
const opcoes = document.querySelectorAll('.alternar');
const visualizador = document.getElementById('visualizador');

opcoes.forEach(opcao => {
  opcao.addEventListener('click', () => {
    const titulo = opcao.getAttribute('data-titulo');
    const descricao = opcao.getAttribute('data-descricao');

    visualizador.innerHTML= `<h2>${titulo}</h2>
    <p>${descricao}</p>`;
});
});
});
document.addEventListener('DOMContentLoaded', function () {
    const opcoes = document.querySelectorAll('.alternar');
    const visualizador = document.getElementById('visualizador');
  
    const dadosVagas = {
      "vaga-backend": `
        <h4>Pré-requisitos:</h4>
         <p>Experiência com frameworks front-end (React, Angular ou Vue.js)</p>
    <p>Domínio de linguagens back-end como Node.js, Ruby ou Python</p>
<p>Conhecimento em bancos de dados (SQL e NoSQL)</p>
<p>Desejável experiência com containers (Docker) e CI/CD</p>
<p>Capacidade de desenvolver soluções completas, do front ao back</p>
<p>Boa prática com Git e controle de versão</p>
<p>Conhecimento em integração de APIs externas e autenticação OAuth</p>
<p>Inglês técnico é um diferencial</p>    
<p><strong>Número de vagas:</strong> 2</p>    
<p><strong>Tipo de contrato e Jornada:</strong> Prestador de Serviços (PJ) - Período Integral</p>    
<p><strong>Área Profissional:</strong> Desenvolvimento Web - Full Stack</p>    
<strong> Exigências  Escolaridade Mínima:</strong>  
<p>Ensino Superior</p>    
<p><strong>Valorizado</strong></p>    
<p>Portfólio no GitHub</p>    
<p>Experiência com testes automatizados</p>    
<p>Participação em projetos open source</p>
      `,
      "vaga-ciberseguranca": `
        <h4>Pré-requisitos:</h4>
        <p>Experiência anterior em gestão de equipes de segurança cibernética</p>
        <p>Conhecimento em gestão de incidentes, firewalls, SIEM e antivírus corporativo</p>
        <p>Vivência com frameworks de compliance como ISO 27001, LGPD e NIST</p>
        <p>Necessário domínio em gestão de riscos de TI e auditorias internas</p>
        <p>Desejável experiência em ambientes cloud (AWS, Azure, GCP)</p>
        <p>Familiaridade com ferramentas de pentest e análise de vulnerabilidades</p>
        <p>Boa comunicação para interagir com diferentes áreas da empresa</p>
        <p>Disponibilidade para viagens pontuais</p>
        <p><strong>Número de vagas:</strong> 1</p>
        <p><strong>Tipo de contrato e Jornada:</strong> Prestador de Serviços (PJ) - Período Integral</p>
        <p><strong>Área Profissional:</strong> Segurança da Informação - Coordenação Técnica</p>
        <p><strong>Exigências - Escolaridade Mínima:</strong> Ensino Superior</p>
        <p><strong>Certificações como:</strong> CISSP, CISM, CEH</p>
        <p>Pós-graduação em Segurança da Informação</p>
        <p>Experiência com LGPD e GDPR</p>
              `,
      "vaga-fullstack": `
              <h4>Pré-requisitos:</h4>

        <p>Experiência com frameworks front-end (React, Angular ou Vue.js)</p>
        <p>Domínio de linguagens back-end como Node.js, Ruby ou Python</p>
        <p>Conhecimento em bancos de dados (SQL e NoSQL)</p>
        <p>Desejável experiência com containers (Docker) e CI/CD</p>
        <p>Capacidade de desenvolver soluções completas, do front ao back</p>
        <p>Boa prática com Git e controle de versão</p>
        <p>Conhecimento em integração de APIs externas e autenticação OAuth</p>
        <p>Inglês técnico é um diferencial</p>
        <p><strong>Número de vagas:</strong> 2</p>
        <p><strong>Tipo de contrato e Jornada:</strong> Prestador de Serviços (PJ) - Período Integral</p>
        <p><strong>Área Profissional:</strong> Desenvolvimento Web - Full Stack</p>
        <p><strong>Exigências - Escolaridade Mínima:</strong> Ensino Superior</p>
        <p><strong>Valorizado:</strong> Portfólio no GitHub</p>
        <p>Experiência com testes automatizados</p>
        <p>Participação em projetos open source</p>      `,
      "vaga-stackholder": `
              <h4>Pré-requisitos:</h4>

        <p>Experiência na definição de visão e estratégia de produtos digitais</p>
        <p>Capacidade de alinhar expectativas de diferentes áreas da empresa</p>
        <p>Domínio em metodologias ágeis (Scrum, Kanban, OKRs)</p>
        <p>Conhecimento em análise de dados para tomada de decisões</p>
        <p>Boa comunicação para apresentação de resultados e relatórios executivos</p>
        <p>Experiência em liderar squads multidisciplinares</p>
        <p>Vivência com ferramentas como Jira, Trello, Miro</p>
        <p>Disponibilidade para reuniões presenciais e viagens pontuais</p>
        <p><strong>Número de vagas:</strong> 1</p>
        <p><strong>Tipo de contrato e Jornada:</strong> Prestador de Serviços (PJ) - Período Integral</p>
        <p><strong>Área Profissional:</strong> Gestão de Produto / Stakeholder de Tecnologia</p>
        <p><strong>Exigências - Escolaridade Mínima:</strong> Ensino Superior</p>
        <p><strong>Valorizado:</strong> MBA ou pós-graduação em Gestão de Projetos ou Negócios</p>
        <p>Experiência com produtos B2B e B2C</p>
        <p>Fluência em inglês será diferencial</p>
      `,
      "vaga-engenheiro": `
        <h4>Pré-requisitos:</h4>
        <p>Sólida experiência em desenvolvimento de software e arquitetura de sistemas</p>
        <p>Conhecimento profundo em POO, SOLID, Clean Code</p>
        <p>Experiência com Git, integração contínua e ambientes ágeis</p>
        <p>Vivência com sistemas distribuídos e escaláveis</p>
        <p>Desejável experiência com cloud (AWS, GCP, Azure)</p>
        <p>Domínio em linguagens como Java, C#, Python ou Go</p>
        <p>Conhecimento em segurança de aplicações será um diferencial</p>
        <p>Inglês técnico para leitura de documentação</p>
        <p><strong>Número de vagas:</strong> 1</p>
        <p><strong>Tipo de contrato e Jornada:</strong> Prestador de Serviços (PJ) - Período Integral</p>
        <p><strong>Área Profissional:</strong> Engenharia de Software - Desenvolvimento e Arquitetura</p>
        <p><strong>Exigências - Escolaridade Mínima:</strong> Ensino Superior</p>
        <p><strong>Valorizado:</strong> Certificações técnicas em engenharia de software ou cloud</p>
        <p>Participação em projetos de larga escala</p>
        <p>Mestrado ou especialização em áreas correlatas</p>
      `,
      "vaga-dados": `
        <h4>Pré-requisitos:</h4>
         <p>Experiência com manipulação, análise e visualização de dados</p>
        <p>Proficiência em Python (pandas, scikit-learn) ou R</p>
        <p>Conhecimento em estatística, modelagem preditiva e machine learning</p>
        <p>Domínio de ferramentas de visualização como Power BI, Tableau ou Matplotlib</p>
        <p>Desejável conhecimento em Big Data (Spark, Hadoop)</p>
        <p>Experiência em versionamento de código (Git)</p>
        <p>Inglês técnico para leitura e escrita de relatórios</p>
        <p>Disponibilidade para reuniões presenciais esporádicas</p>
        <p><strong>Número de vagas:</strong> 1</p>
        <p><strong>Tipo de contrato e Jornada:</strong> Prestador de Serviços (PJ) - Período Integral</p>
        <p><strong>Área Profissional:</strong> Análise de Dados - Ciência de Dados</p>
        <p><strong>Exigências - Escolaridade Mínima:</strong> Ensino Superior</p>
        <p><strong>Valorizado:</strong> Participação em competições como Kaggle</p>
        <p>Certificações em Data Science ou Machine Learning</p>
        <p>Mestrado em Estatística, Matemática ou Ciência de Dados</p>
      `
    };
  
    const dadosEmpresas = {
      "vaga-backend": `
        <h4>Informações da Empresa:</h4>
        <br>Empresa Confidencial
        <br>Localização: São Paulo - SP, a 24 Km de você
      `,
      "vaga-ciberseguranca": `
        <h4>Informações da Empresa:</h4>
        <br>Empresa: Confidencial
        <br>Local: São Paulo - SP
      `,
      "vaga-fullstack": `
        <h4>Informações da Empresa:</h4>
        <br>MarketEase
        <br>Diadema - SP, a Alguns Metros de você
      `,
      "vaga-stackholder": `
        <h4>Informações da Empresa:</h4>
        <br>Empresa: Confidencial
        <br>Local: ---
      `,
      "vaga-engenheiro": `
        <h4>Informações da Empresa:</h4>
        <br>RESTOQUE SA
        <br>São Paulo - SP, a 23,1 km de você
      `,
      "vaga-dados": `
        <h4>Informações da Empresa:</h4>
        <br>CORPORATIVO GRUPO CARREFOUR BRASIL
        <br>São Paulo - SP, a 20,4 km de você
      `
    };
  
    opcoes.forEach(opcao => {
      opcao.addEventListener('click', () => {
        const titulo = opcao.getAttribute('data-titulo');
        const descricao = opcao.getAttribute('data-descricao');
        const id = opcao.getAttribute('data-id');
  
        visualizador.innerHTML = `
          <h2>${titulo}</h2>
          <p>${descricao}</p>
          <div class="vaga-detalhes" style="margin-top: 10px;"></div>
        `;
  
        visualizador.setAttribute('data-id-vaga', id);
      });
    });
  
    visualizador.addEventListener('click', function (e) {
      const id = visualizador.getAttribute('data-id-vaga');
      if (!id) return;
  
      const containerDetalhes = visualizador.querySelector('.vaga-detalhes');
  
      if (e.target.classList.contains('botao-vaga')) {
        containerDetalhes.innerHTML = dadosVagas[id] || '<p>Detalhes da vaga não disponíveis.</p>';
      }
  
      if (e.target.classList.contains('botao-empresa')) {
        containerDetalhes.innerHTML = dadosEmpresas[id] || '<p>Informações da empresa não disponíveis.</p>';
      }
    });
  });

  function candidatar() {
    const botao = document.getElementById("btn-candidatar");
    botao.innerText = "Candidatado!";
    botao.disabled = true;
  }
  
  
  
  


