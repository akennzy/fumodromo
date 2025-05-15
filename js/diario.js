document.addEventListener('DOMContentLoaded', () => {
    carregarEntradas();
    inicializarEventos();
    atualizarEstatisticas();
});

// Configuração de eventos
function inicializarEventos() {
    // Seletor de humor
    document.querySelectorAll('.mood-option').forEach(option => {
        option.addEventListener('click', () => {
            document.querySelectorAll('.mood-option').forEach(opt => opt.classList.remove('selected'));
            option.classList.add('selected');
            document.getElementById('humor').value = option.dataset.value;
            
            // Animar o ícone selecionado
            const icon = option.querySelector('i');
            icon.style.animation = 'none';
            icon.offsetHeight; // Trigger reflow
            icon.style.animation = 'mood-pulse 0.5s';
        });
    });

    // Filtros e busca
    document.getElementById('filterHumor').addEventListener('change', carregarEntradas);
    document.getElementById('sortOrder').addEventListener('change', carregarEntradas);
    document.getElementById('searchText').addEventListener('input', debounce(carregarEntradas, 300));

    // Formulário
    document.getElementById('salvarEntrada').addEventListener('click', salvarEntrada);
}

// Carregar entradas do diário
async function carregarEntradas() {
    const humor = document.getElementById('filterHumor').value;
    const ordem = document.getElementById('sortOrder').value;
    const busca = document.getElementById('searchText').value;

    try {
        const response = await fetch('api/diario.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                acao: 'listar',
                humor: humor,
                ordem: ordem,
                busca: busca
            })
        });

        const data = await response.json();
        
        if (data.status === 'success') {
            const entriesList = document.querySelector('.entries-list');
            const emptyState = document.getElementById('emptyState');
            
            if (data.entradas && data.entradas.length > 0) {
                entriesList.innerHTML = data.entradas.map(entrada => criarCardEntrada(entrada)).join('');
                entriesList.style.display = 'block';
                emptyState.style.display = 'none';
            } else {
                entriesList.style.display = 'none';
                emptyState.style.display = 'block';
            }
        } else {
            mostrarErro('Erro ao carregar as entradas');
        }
    } catch (error) {
        console.error('Erro:', error);
        mostrarErro('Erro ao carregar as entradas');
    }
}

// Criar card de entrada
function criarCardEntrada(entrada) {
    const moodIcons = {
        1: 'bi-emoji-frown',
        2: 'bi-emoji-frown-fill',
        3: 'bi-emoji-neutral',
        4: 'bi-emoji-smile',
        5: 'bi-emoji-laughing'
    };

    const moodColors = {
        1: 'danger',
        2: 'warning',
        3: 'info',
        4: 'primary',
        5: 'success'
    };

    const data = new Date(entrada.data_criacao).toLocaleDateString('pt-BR');
    const hora = new Date(entrada.data_criacao).toLocaleTimeString('pt-BR');

    return `
        <div class="diary-entry" data-id="${entrada.id}">
            <div class="d-flex align-items-start">
                <div class="mood-indicator">
                    <i class="bi ${moodIcons[entrada.humor]} text-${moodColors[entrada.humor]}"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="date-time mb-2">${data} às ${hora}</div>
                    <p class="mb-0">${entrada.texto}</p>
                    
                    <div class="diary-metrics">
                        <div class="diary-metric">
                            <i class="bi bi-heart-pulse"></i>
                            <span>Ansiedade: ${entrada.nivel_ansiedade}/5</span>
                        </div>
                        <div class="diary-metric">
                            <i class="bi bi-cloud-haze"></i>
                            <span>Vontade: ${entrada.nivel_vontade}/5</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Salvar nova entrada
async function salvarEntrada() {
    const form = document.getElementById('diarioForm');
    const humor = document.getElementById('humor').value;
    
    if (!humor) {
        mostrarErro('Por favor, selecione seu humor');
        return;
    }

    const dados = {
        acao: 'criar',
        humor: humor,
        nivel_ansiedade: document.getElementById('nivelAnsiedade').value,
        nivel_vontade: document.getElementById('nivelVontade').value,
        texto: document.getElementById('texto').value
    };

    try {
        const response = await fetch('api/diario.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(dados)
        });

        const data = await response.json();
        
        if (data.status === 'success') {
            bootstrap.Modal.getInstance(document.getElementById('entradaModal')).hide();
            form.reset();
            document.querySelectorAll('.mood-option').forEach(opt => opt.classList.remove('selected'));
            
            mostrarSucesso('Entrada salva com sucesso!');
            carregarEntradas();
            atualizarEstatisticas();
        } else {
            mostrarErro(data.message || 'Erro ao salvar a entrada');
        }
    } catch (error) {
        console.error('Erro:', error);
        mostrarErro('Erro ao salvar a entrada');
    }
}

// Atualizar estatísticas
async function atualizarEstatisticas() {
    try {
        const response = await fetch('api/diario.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                acao: 'estatisticas'
            })
        });

        const data = await response.json();
        
        if (data.status === 'success') {
            document.getElementById('totalRegistros').textContent = data.total || 0;
            document.getElementById('diasSeguidos').textContent = data.dias_seguidos || 0;
            document.getElementById('humorMedio').textContent = data.humor_medio ? data.humor_medio.toFixed(1) : '-';
            
            if (document.getElementById('maiorSequencia')) {
                document.getElementById('maiorSequencia').textContent = data.maior_sequencia || 0;
            }
        }
    } catch (error) {
        console.error('Erro:', error);
    }
}

// Abrir modal de nova entrada
function abrirModalEntrada() {
    const modal = new bootstrap.Modal(document.getElementById('entradaModal'));
    document.getElementById('diarioForm').reset();
    document.querySelectorAll('.mood-option').forEach(opt => opt.classList.remove('selected'));
    modal.show();
}

// Utilidades
function mostrarSucesso(mensagem) {
    Swal.fire({
        icon: 'success',
        title: 'Sucesso!',
        text: mensagem,
        timer: 2000,
        showConfirmButton: false
    });
}

function mostrarErro(mensagem) {
    Swal.fire({
        icon: 'error',
        title: 'Ops!',
        text: mensagem
    });
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}
