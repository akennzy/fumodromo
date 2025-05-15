document.addEventListener('DOMContentLoaded', function() {
    // Inicializar efeitos de hover nos cartões de estatísticas
    document.querySelectorAll('.stat-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            const icon = this.querySelector('.bi');
            icon.classList.add('animated-pulse');
        });
        
        card.addEventListener('mouseleave', function() {
            const icon = this.querySelector('.bi');
            icon.classList.remove('animated-pulse');
        });
    });

    // Inicializar dados do usuário
    const dataParda = new Date(userConfig.dataParda);
    const cigarrosPorDia = userConfig.cigarrosPorDia;
    const precoMaco = userConfig.precoMaco;

    // Contador em tempo real
    function atualizarContador() {
        const agora = new Date();
        const diff = agora - dataParda;

        const dias = Math.floor(diff / (1000 * 60 * 60 * 24));
        const horas = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutos = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const segundos = Math.floor((diff % (1000 * 60)) / 1000);

        document.getElementById('dias').textContent = dias;
        document.getElementById('horas').textContent = horas.toString().padStart(2, '0');
        document.getElementById('minutos').textContent = minutos.toString().padStart(2, '0');
        document.getElementById('segundos').textContent = segundos.toString().padStart(2, '0');

        // Atualizar estatísticas
        const cigarrosEvitados = Math.floor((diff / (1000 * 60 * 60 * 24)) * cigarrosPorDia);
        const economiaTotal = (cigarrosEvitados / 20) * precoMaco;
        const vidaRecuperada = Math.floor(cigarrosEvitados * 11); // 11 minutos por cigarro

        document.getElementById('cigarrosEvitados').textContent = cigarrosEvitados.toLocaleString();
        document.getElementById('economiaTotal').textContent = 
            `R$ ${economiaTotal.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`;
        document.getElementById('vidaRecuperada').textContent = 
            `${Math.floor(vidaRecuperada / 60)}h ${vidaRecuperada % 60}min`;
    }

    setInterval(atualizarContador, 1000);
    atualizarContador();

    // Gerenciar Checklist
    const checklistItems = document.querySelectorAll('.form-check-input');
    checklistItems.forEach(item => {
        item.addEventListener('change', function() {
            fetch('/api/checklist.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    item: this.id,
                    completado: this.checked
                })
            });

            if (this.checked) {
                Swal.fire({
                    icon: 'success',
                    title: 'Ótimo trabalho!',
                    text: 'Continue assim! Cada pequeno passo conta.',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
    });

    // Carregar estado atual do checklist
    fetch('/api/checklist.php')
        .then(response => response.json())
        .then(data => {
            data.forEach(item => {
                const checkbox = document.getElementById(item.item);
                if (checkbox) {
                    checkbox.checked = item.completado;
                }
            });
        });

    // Gerenciar Diário Rápido
    const moodButtons = document.querySelectorAll('.mood-btn');
    let selectedMood = null;

    moodButtons.forEach(button => {
        button.addEventListener('click', function() {
            const mood = this.dataset.mood;
            selectedMood = mood;

            // Remover classe active de todos os botões
            moodButtons.forEach(btn => btn.classList.remove('active'));
            // Adicionar classe active ao botão selecionado
            this.classList.add('active');

            // Abrir modal para nota opcional
            const modal = new bootstrap.Modal(document.getElementById('diarioRapidoModal'));
            modal.show();
        });
    });

    document.getElementById('salvarDiario').addEventListener('click', function() {
        if (!selectedMood) return;

        const nota = document.getElementById('notaDiario').value;
        
        fetch('/api/diario_rapido.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                sentimento: selectedMood,
                nota: nota
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('diarioRapidoModal'));
                modal.hide();

                // Limpar campos
                document.getElementById('notaDiario').value = '';
                moodButtons.forEach(btn => btn.classList.remove('active'));
                selectedMood = null;

                Swal.fire({
                    icon: 'success',
                    title: 'Registrado!',
                    text: 'Seu sentimento foi registrado com sucesso.',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
    });

    // Gerenciar botões de ação
    const btnRecomecar = document.getElementById('btnRecomecar');
    const btnAjuda = document.getElementById('btnAjuda');
    const recomecarModal = new bootstrap.Modal(document.getElementById('recomecarModal'));
    const ajudaModal = new bootstrap.Modal(document.getElementById('ajudaModal'));

    btnRecomecar.addEventListener('click', () => recomecarModal.show());
    btnAjuda.addEventListener('click', () => ajudaModal.show());

    document.getElementById('confirmarRecomecar').addEventListener('click', async () => {
        try {
            const response = await fetch('api/recomecar.php', {
                method: 'POST'
            });
            const data = await response.json();

            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Jornada Reiniciada',
                    text: 'Mantenha-se forte! Cada tentativa é um passo em direção ao sucesso.',
                    showConfirmButton: false,
                    timer: 2000
                });
                
                recomecarModal.hide();
                location.reload();
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'Não foi possível reiniciar sua jornada. Tente novamente.'
            });
        }
    });

    // Sistema de conquistas
    function carregarConquistas() {
        fetch('/api/conquistas.php')
            .then(response => response.json())
            .then(data => {
                atualizarProgresso(data.progresso);
                renderizarConquistas(data.conquistas);
            });
    }

    function atualizarProgresso(progresso) {
        document.getElementById('nivelAtual').textContent = progresso.nivel;
        document.getElementById('pontosAtuais').textContent = progresso.pontos;
        document.getElementById('pontosProximoNivel').textContent = progresso.proxima_pontuacao;
        
        const percentual = (progresso.pontos % 100) || 0;
        document.getElementById('progressoNivel').style.width = `${percentual}%`;
    }

    function renderizarConquistas(conquistas) {
        const container = document.getElementById('conquistasContainer');
        container.innerHTML = '';

        conquistas.forEach(conquista => {
            const card = document.createElement('div');
            card.className = `achievement-card ${conquista.data_obtida ? '' : 'locked'}`;
            card.innerHTML = `
                <div class="achievement-icon">
                    <i class="bi ${conquista.icone} ${conquista.data_obtida ? 'animated-float' : ''}"></i>
                </div>
                <div class="achievement-title">${conquista.titulo}</div>
                <div class="achievement-points">+${conquista.pontos} pontos</div>
                ${conquista.data_obtida ? `
                    <div class="achievement-date">
                        Obtida em ${new Date(conquista.data_obtida).toLocaleDateString()}
                    </div>
                ` : ''}
            `;
            container.appendChild(card);
        });
    }

    function verificarConquistas() {
        const agora = new Date();
        const diff = agora - dataParda;
        const dias = Math.floor(diff / (1000 * 60 * 60 * 24));
        const economiaTotal = (Math.floor((diff / (1000 * 60 * 60 * 24)) * cigarrosPorDia) / 20) * precoMaco;

        // Verificar conquistas de tempo
        if (dias >= 1) verificarConquista(1); // Primeiro dia
        if (dias >= 7) verificarConquista(2); // Primeira semana
        if (dias >= 30) verificarConquista(3); // Primeiro mês

        // Verificar conquistas de economia
        if (economiaTotal >= 50) verificarConquista(7); // Economia inicial
        if (economiaTotal >= 200) verificarConquista(8); // Super economia
    }

    function verificarConquista(conquistaId) {
        fetch('/api/conquistas.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                conquista_id: conquistaId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarConquista(data);
                atualizarProgresso(data.progresso);
                carregarConquistas();
            }
        });
    }

    function mostrarConquista(data) {
        const { titulo, descricao, pontos } = data;
        
        Swal.fire({
            icon: 'success',
            title: 'Nova Conquista!',
            html: `
                <div class="achievement-animation">
                    <i class="bi bi-trophy-fill display-1 text-warning animated-bounce"></i>
                </div>
                <h4>${titulo}</h4>
                <p>${descricao}</p>
                <div class="points-earned">
                    <span class="badge bg-success">+${pontos} pontos</span>
                </div>
            `,
            showConfirmButton: true,
            confirmButtonText: 'Legal!',
            customClass: {
                popup: 'achievement-popup'
            }
        });
    }

    // Inicializar sistema de conquistas
    carregarConquistas();
    setInterval(verificarConquistas, 60000);
    verificarConquistas();
});
