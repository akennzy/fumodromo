document.addEventListener('DOMContentLoaded', () => {
    const cadastroForm = document.getElementById('cadastroForm');

    cadastroForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (!cadastroForm.checkValidity()) {
            e.stopPropagation();
            cadastroForm.classList.add('was-validated');
            return;
        }

        const formData = {
            nome: document.getElementById('nome').value,
            usuario: document.getElementById('usuario').value,
            senha: document.getElementById('senha').value,
            dataNascimento: document.getElementById('dataNascimento').value,
            razaoMudanca: document.getElementById('razaoMudanca').value,
            medosPreocupacoes: document.getElementById('medosPreocupacoes').value,
            dataInicioFumo: document.getElementById('dataInicioFumo').value,
            tentativasParar: document.getElementById('tentativasParar').value,
            cigarrosDia: document.getElementById('cigarrosDia').value,
            cigarrosMaco: document.getElementById('cigarrosMaco').value,
            valorMaco: document.getElementById('valorMaco').value
        };

        try {
            const response = await fetch('api/cadastro.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: 'Cadastro realizado com sucesso!',
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    window.location.href = 'index.php';
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: data.message || 'Erro ao realizar cadastro'
                });
            }
        } catch (error) {
            console.error('Erro:', error);
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'Erro ao conectar com o servidor'
            });
        }
    });

    // Validações adicionais
    const senha = document.getElementById('senha');
    senha.addEventListener('input', () => {
        if (senha.value.length < 6) {
            senha.setCustomValidity('A senha deve ter pelo menos 6 caracteres');
        } else {
            senha.setCustomValidity('');
        }
    });

    const dataNascimento = document.getElementById('dataNascimento');
    dataNascimento.addEventListener('input', () => {
        const data = new Date(dataNascimento.value);
        const hoje = new Date();
        const idade = hoje.getFullYear() - data.getFullYear();
        
        if (idade < 18) {
            dataNascimento.setCustomValidity('Você deve ter pelo menos 18 anos');
        } else {
            dataNascimento.setCustomValidity('');
        }
    });
});
