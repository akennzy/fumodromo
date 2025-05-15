document.addEventListener('DOMContentLoaded', () => {
    // Configuração dos gráficos
    Chart.defaults.font.family = "'Segoe UI', 'Helvetica Neue', 'Arial', sans-serif";
    Chart.defaults.font.size = 14;
    Chart.defaults.color = '#666';

    // Função para carregar os dados das estatísticas
    const carregarEstatisticas = async () => {
        try {
            const response = await fetch('api/estatisticas.php');
            const data = await response.json();

            if (data.success) {
                atualizarGraficos(data);
                atualizarValores(data);
                atualizarTimeline(data.dias);
            }
        } catch (error) {
            console.error('Erro ao carregar estatísticas:', error);
        }
    };

    // Função para atualizar os gráficos
    const atualizarGraficos = (data) => {
        // Gráfico de Progresso
        const ctx = document.getElementById('progressChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: Array.from({length: data.dias + 1}, (_, i) => `Dia ${i}`),
                datasets: [{
                    label: 'Dias sem fumar',
                    data: Array.from({length: data.dias + 1}, (_, i) => i),
                    borderColor: '#4CAF50',
                    backgroundColor: 'rgba(76, 175, 80, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Gráfico de Economia
        const economiaCtx = document.getElementById('economiaChart').getContext('2d');
        new Chart(economiaCtx, {
            type: 'bar',
            data: {
                labels: ['Economia'],
                datasets: [{
                    label: 'R$',
                    data: [data.economia],
                    backgroundColor: 'rgba(33, 150, 243, 0.7)',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Gráfico de Cigarros Evitados
        const cigarrosCtx = document.getElementById('cigarrosChart').getContext('2d');
        new Chart(cigarrosCtx, {
            type: 'doughnut',
            data: {
                labels: ['Evitados', 'Meta Mensal'],
                datasets: [{
                    data: [data.cigarrosEvitados, data.cigarrosEvitados * 0.5],
                    backgroundColor: [
                        'rgba(76, 175, 80, 0.7)',
                        'rgba(244, 67, 54, 0.7)'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    };

    // Função para atualizar os valores nos cards
    const atualizarValores = (data) => {
        document.getElementById('valorEconomizado').textContent = 
            data.economia.toFixed(2).replace('.', ',');
        document.getElementById('cigarrosEvitados').textContent = 
            data.cigarrosEvitados.toLocaleString('pt-BR');
    };

    // Função para atualizar a timeline de benefícios
    const atualizarTimeline = (dias) => {
        const timelineItems = document.querySelectorAll('.timeline-item');
        const horasPassadas = dias * 24;

        timelineItems.forEach((item, index) => {
            // Define os marcos de tempo em horas
            const marcos = [0.33, 12, 336]; // 20min, 12h, 2 semanas
            if (horasPassadas >= marcos[index]) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });
    };

    // Inicia o carregamento das estatísticas
    carregarEstatisticas();

    // Atualiza as estatísticas a cada minuto
    setInterval(carregarEstatisticas, 60000);
});
