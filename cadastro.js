document.addEventListener('DOMContentLoaded', function() {
    // Preenche os estados
   
    const form = document.getElementById('form-cadastro');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
       
        document.querySelectorAll('.error-message').forEach(el => el.remove());
        
   
        let isValid = true;
        const camposObrigatorios = form.querySelectorAll('[required]');
        
        camposObrigatorios.forEach(campo => {
            if (!campo.value.trim()) {
                campo.style.borderColor = '#ff4444';
                const errorMsg = document.createElement('div');
                errorMsg.className = 'error-message';
                errorMsg.textContent = 'Este campo é obrigatório';
                campo.parentNode.appendChild(errorMsg);
                isValid = false;
            } else {
                campo.style.borderColor = '#ddd';
            }
        });

        if (!document.getElementById('termos').checked) {
            const termosError = document.createElement('div');
            termosError.className = 'error-message';
            termosError.textContent = 'Você deve aceitar os termos';
            document.getElementById('termos').parentNode.appendChild(termosError);
            isValid = false;
        }

        if (!isValid) return;
        const button = form.querySelector('button[type="submit"]');
        button.disabled = true;
        button.innerHTML = '<span class="loading">Cadastrando...</span>';

        setTimeout(() => {
        
            const successDiv = document.createElement('div');
            successDiv.className = 'success-message';
            successDiv.textContent = 'Cadastro realizado com sucesso!';
            form.prepend(successDiv);

     
            setTimeout(() => {
                window.location.href = "tela_principal.html";
            }, 2000);
        }, 1500);
    });
    });
