document.addEventListener('DOMContentLoaded', () => {
    const perfilForm = document.getElementById('perfilForm');
    const senhaForm = document.getElementById('senhaForm');
    const btnAlterarSenha = document.getElementById('btnAlterarSenha');
    const senhaModal = new bootstrap.Modal(document.getElementById('senhaModal'));
    const btnSalvarSenha = document.getElementById('salvarSenha');

    // Manipulador do formulário de perfil
    perfilForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (!perfilForm.checkValidity()) {
            e.stopPropagation();
            perfilForm.classList.add('was-validated');
            return;
        }

        const formData = {
            nome: document.getElementById('nome').value,
            dataNascimento: document.getElementById('dataNascimento').value,
            razaoMudanca: document.getElementById('razaoMudanca').value,
            medosPreocupacoes: document.getElementById('medosPreocupacoes').value,
            cigarrosDia: document.getElementById('cigarrosDia').value,
            cigarrosMaco: document.getElementById('cigarrosMaco').value,
            valorMaco: document.getElementById('valorMaco').value
        };

        try {
            const response = await fetch('api/atualizar_perfil.php', {
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
                    text: 'Perfil atualizado com sucesso!',
                    showConfirmButton: false,
                    timer: 2000
                });
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: error.message || 'Erro ao atualizar perfil'
            });
        }
    });

    // Manipulador do botão de alteração de senha
    btnAlterarSenha.addEventListener('click', () => {
        senhaForm.reset();
        senhaModal.show();
    });

    // Manipulador do botão de salvar senha
    btnSalvarSenha.addEventListener('click', async () => {
        const senhaAtual = document.getElementById('senhaAtual').value;
        const novaSenha = document.getElementById('novaSenha').value;
        const confirmarSenha = document.getElementById('confirmarSenha').value;

        if (!senhaAtual || !novaSenha || !confirmarSenha) {
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'Todos os campos são obrigatórios'
            });
            return;
        }

        if (novaSenha !== confirmarSenha) {
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'As senhas não coincidem'
            });
            return;
        }

        if (novaSenha.length < 6) {
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'A senha deve ter pelo menos 6 caracteres'
            });
            return;
        }

        try {
            const response = await fetch('api/alterar_senha.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    senhaAtual,
                    novaSenha
                })
            });

            const data = await response.json();

            if (data.success) {
                senhaModal.hide();
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: 'Senha alterada com sucesso!',
                    showConfirmButton: false,
                    timer: 2000
                });
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: error.message || 'Erro ao alterar senha'
            });
        }
    });

    // Validações em tempo real
    const cigarrosDia = document.getElementById('cigarrosDia');
    const cigarrosMaco = document.getElementById('cigarrosMaco');
    const valorMaco = document.getElementById('valorMaco');

    [cigarrosDia, cigarrosMaco, valorMaco].forEach(input => {
        input.addEventListener('input', () => {
            const value = parseFloat(input.value);
            if (value <= 0) {
                input.setCustomValidity('O valor deve ser maior que zero');
            } else {
                input.setCustomValidity('');
            }
        });
    });

    // Atualizar estatísticas de economia
    const atualizarEstatisticas = () => {
        const cigarrosDia = parseInt(document.getElementById('cigarrosDia').value) || 0;
        const cigarrosMaco = parseInt(document.getElementById('cigarrosMaco').value) || 0;
        const valorMaco = parseFloat(document.getElementById('valorMaco').value) || 0;

        if (cigarrosDia && cigarrosMaco && valorMaco) {
            const valorPorCigarro = valorMaco / cigarrosMaco;
            const gastoDiario = valorPorCigarro * cigarrosDia;
            const gastoMensal = gastoDiario * 30;
            const gastoAnual = gastoDiario * 365;

            document.getElementById('economiaDia').textContent = gastoDiario.toFixed(2);
            document.getElementById('economiaMes').textContent = gastoMensal.toFixed(2);
            document.getElementById('economiaAno').textContent = gastoAnual.toFixed(2);
        }
    };

    // Carregar dados do usuário
    const carregarDadosUsuario = async () => {
        try {
            const response = await fetch('api/estatisticas.php');
            const data = await response.json();

            if (data.success) {
                document.getElementById('diasSemFumar').textContent = data.diasSemFumar;
                document.getElementById('economiaTotal').textContent = `R$ ${data.economiaTotal.toFixed(2)}`;
                document.getElementById('saudeProgresso').textContent = `+${data.saudeProgresso}%`;
                document.getElementById('totalConquistas').textContent = `${data.conquistasObtidas}/${data.conquistasTotal}`;
                document.getElementById('nivelProgresso').textContent = data.nivel;

                // Atualizar o círculo de progresso
                const progressCircle = document.querySelector('.progress-circle');
                if (progressCircle) {
                    const progress = (data.diasSemFumar / 30) * 100; // 30 dias como meta inicial
                    progressCircle.style.background = `conic-gradient(
                        var(--primary-color) ${progress}%,
                        var(--card-bg) ${progress}%
                    )`;
                }
            }
        } catch (error) {
            console.error('Erro ao carregar dados do usuário:', error);
        }
    };

    // Manipulador para objetivos
    const goalItems = document.querySelectorAll('.goal-item');
    goalItems.forEach(item => {
        item.addEventListener('click', function() {
            if (this.querySelector('.bi-plus-circle')) {
                Swal.fire({
                    title: 'Novo Objetivo',
                    input: 'text',
                    inputLabel: 'Qual é seu novo objetivo?',
                    showCancelButton: true,
                    confirmButtonText: 'Adicionar',
                    cancelButtonText: 'Cancelar',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Por favor, digite seu objetivo';
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Aqui você pode adicionar a lógica para salvar o novo objetivo
                        const novoObjetivo = document.createElement('div');
                        novoObjetivo.className = 'goal-item';
                        novoObjetivo.innerHTML = `
                            <i class="bi bi-check-circle"></i>
                            <span>${result.value}</span>
                        `;
                        this.parentNode.insertBefore(novoObjetivo, this);
                    }
                });
            }
        });
    });

    // Event listeners para campos de consumo
    ['cigarrosDia', 'cigarrosMaco', 'valorMaco'].forEach(id => {
        const input = document.getElementById(id);
        input.addEventListener('input', atualizarEstatisticas);
    });

    // Manipulador para o botão de editar perfil
    const btnEditarPerfil = document.getElementById('btnEditarPerfil');
    if (btnEditarPerfil) {
        btnEditarPerfil.addEventListener('click', () => {
            const inputs = document.querySelectorAll('#perfilForm input, #perfilForm textarea');
            inputs.forEach(input => input.removeAttribute('readonly'));
            
            Swal.fire({
                icon: 'info',
                title: 'Modo de Edição',
                text: 'Agora você pode editar seus dados. Não esqueça de salvar as alterações!',
                showConfirmButton: false,
                timer: 2000
            });
        });
    }

    // Carregar dados iniciais
    carregarDadosUsuario();
    atualizarEstatisticas();
});
