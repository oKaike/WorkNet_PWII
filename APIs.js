function nacionalidade(paises){
    let pais = document.getElementById('pais');
      for(let i = 0; i<=paises.length; i++){
        let option = document.createElement('option');
        option.value = paises[i];
        option.innerHTML = paises[i];
        pais.appendChild(option);
      }
  }
  
  const host = 'https://restcountries.com/v3.1/all';
  
  fetch(`${host}`, {
      method: 'GET',
      headers:{
          Aceppt:'Application/json',
      }
  }).then((Response) =>{
      return Response.json();
  }).then((data)=>{
  
    
     for(let i = 0; i<=data.length; i++){
      paises = data.map(item => item.name.common);
      paises.sort();
      console.log(paises);
     }
     nacionalidade(paises);
  })
  
  window.onload = nacionalidade(paises);