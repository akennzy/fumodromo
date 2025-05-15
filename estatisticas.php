<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
require_once 'config.php';

try {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $usuario = $stmt->fetch();
} catch (PDOException $e) {
    die("Erro ao carregar dados do usuário");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estatísticas - FumoDromo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="bg-gradient">
    <div class="container py-4">
        <h1 class="text-center mb-4">
            <i class="bi bi-graph-up-arrow"></i>
            Sua Evolução
        </h1>
        
        <div class="main-content">
            <!-- Status do Jogador -->
            <div class="dashboard-card p-4 mb-4 achievement-showcase">
                <div class="row align-items-center">
                    <div class="col-md-4 text-center">
                        <div class="player-level-circle">
                            <div class="level-number">
                                <span id="nivelAtual">1</span>
                            </div>
                            <div class="level-label">Nível</div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h4>Status do Guerreiro</h4>
                        <div class="progress mb-3" style="height: 10px;">
                            <div class="progress-bar" id="progressoNivel" role="progressbar" style="width: 0%"></div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="badge bg-success rounded-pill">
                                <i class="bi bi-star-fill"></i>
                                <span id="pontosAtuais">0</span> pontos
                            </span>
                            <span class="badge bg-primary rounded-pill">
                                <i class="bi bi-trophy"></i>
                                <span id="conquistasTotal">0</span> conquistas
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráfico Principal com Tabs -->
            <div class="dashboard-card p-4 mb-4">
                <ul class="nav nav-pills mb-3" id="statsTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="progress-tab" data-bs-toggle="pill" data-bs-target="#progress" type="button">
                            <i class="bi bi-calendar-check"></i> Progresso
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="habits-tab" data-bs-toggle="pill" data-bs-target="#habits" type="button">
                            <i class="bi bi-check2-circle"></i> Hábitos
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="mood-tab" data-bs-toggle="pill" data-bs-target="#mood" type="button">
                            <i class="bi bi-emoji-smile"></i> Humor
                        </button>
                    </li>
                </ul>
                <div class="tab-content" id="statsTabContent">
                    <div class="tab-pane fade show active" id="progress">
                        <canvas id="progressChart"></canvas>
                    </div>
                    <div class="tab-pane fade" id="habits">
                        <canvas id="habitsChart"></canvas>
                    </div>
                    <div class="tab-pane fade" id="mood">
                        <canvas id="moodChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Cards de Benefícios à Saúde -->
            <div class="row g-4">
                <div class="col-12">
                    <div class="dashboard-card p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="mb-0">
                                <i class="bi bi-heart-pulse text-danger"></i>
                                Benefícios Desbloqueados
                            </h4>
                            <div class="health-progress">
                                <div class="progress" style="width: 150px; height: 8px;">
                                    <div class="progress-bar bg-success" id="healthProgress" role="progressbar"></div>
                                </div>
                                <small class="text-muted">Progresso da Recuperação</small>
                            </div>
                        </div>
                        <div class="timeline" id="benefitsTimeline">
                            <div class="timeline-item active achievement-unlocked">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="timeline-icon" style="background: var(--gradient-1)">
                                        <i class="bi bi-heart-pulse"></i>
                                    </div>
                                    <div class="ms-3">
                                        <div class="d-flex align-items-center">
                                            <h5>20 minutos</h5>
                                            <span class="badge bg-success ms-2">
                                                <i class="bi bi-unlock"></i> Desbloqueado
                                            </span>
                                        </div>
                                        <p class="mb-0">Sua pressão arterial e frequência cardíaca já diminuíram</p>
                                    </div>
                                </div>
                            </div>
                            <div class="timeline-item" data-unlock-time="12">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="timeline-icon" style="background: var(--gradient-2)">
                                        <i class="bi bi-lungs"></i>
                                    </div>
                                    <div class="ms-3">
                                        <div class="d-flex align-items-center">
                                            <h5>12 horas</h5>
                                            <div class="countdown ms-2" data-hours="12"></div>
                                        </div>
                                        <p class="mb-0">O nível de monóxido de carbono no seu sangue voltou ao normal</p>
                                    </div>
                                </div>
                            </div>
                            <div class="timeline-item" data-unlock-time="336">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="timeline-icon" style="background: var(--gradient-3)">
                                        <i class="bi bi-wind"></i>
                                    </div>
                                    <div class="ms-3">
                                        <div class="d-flex align-items-center">
                                            <h5>2 semanas</h5>
                                            <div class="countdown ms-2" data-hours="336"></div>
                                        </div>
                                        <p class="mb-0">Sua circulação melhorou e sua função pulmonar aumentou</p>
                                    </div>
                                </div>
                            </div>
                            <div class="timeline-item" data-unlock-time="2160">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="timeline-icon" style="background: var(--gradient-4)">
                                        <i class="bi bi-shield-check"></i>
                                    </div>
                                    <div class="ms-3">
                                        <div class="d-flex align-items-center">
                                            <h5>3 meses</h5>
                                            <div class="countdown ms-2" data-hours="2160"></div>
                                        </div>
                                        <p class="mb-0">Risco de ataque cardíaco começou a diminuir</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cards de Estatísticas Detalhadas -->
            <div class="row g-4 mt-2">
                <div class="col-md-6">
                    <div class="dashboard-card p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">
                                <i class="bi bi-piggy-bank text-success"></i>
                                Economia
                            </h5>
                            <div class="savings-milestone">
                                <span class="badge bg-primary">
                                    Próxima meta: R$ <span id="proximaMeta">100</span>
                                </span>
                            </div>
                        </div>
                        <div id="economiaChart"></div>
                        <div class="text-center mt-3">
                            <h4 class="mb-0">R$ <span id="valorEconomizado" class="stat-highlight">0,00</span></h4>
                            <small class="text-muted">economizados até agora</small>
                            <div class="savings-ideas mt-2">
                                <button class="btn btn-sm btn-outline-success toggle-ideas">
                                    <i class="bi bi-lightbulb"></i> Ver ideias para investir
                                </button>
                                <div class="savings-suggestions d-none">
                                    <div class="alert alert-success mt-2">
                                        <ul class="mb-0">
                                            <li>Invista em um novo hobby</li>
                                            <li>Faça uma viagem especial</li>
                                            <li>Comece uma poupança</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="dashboard-card p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">
                                <i class="bi bi-lungs text-primary"></i>
                                Impacto na Saúde
                            </h5>
                            <div class="health-milestone">
                                <span class="badge bg-success">
                                    <span id="saudeBonus">+0</span>% de melhora
                                </span>
                            </div>
                        </div>
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="health-stat mb-3">
                                    <h3 id="cigarrosEvitados" class="stat-highlight">0</h3>
                                    <small class="text-muted">Cigarros Evitados</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="health-stat mb-3">
                                    <h3 id="vidaRecuperada" class="stat-highlight">0h</h3>
                                    <small class="text-muted">Vida Recuperada</small>
                                </div>
                            </div>
                        </div>
                        <div id="cigarrosChart"></div>
                        <div class="text-center mt-3">
                            <span class="badge bg-warning text-dark achievement-pill">
                                <i class="bi bi-award"></i>
                                Meta: 1000 cigarros evitados
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Histórico de Tentativas -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="dashboard-card p-4">
                        <h5 class="mb-4">
                            <i class="bi bi-clock-history"></i>
                            Histórico de Jornadas
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Início</th>
                                        <th>Duração</th>
                                        <th>Cigarros Evitados</th>
                                        <th>Economia</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="historicoTentativas">
                                    <!-- Preenchido via JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Barra de Navegação Inferior -->
    <nav class="bottom-nav">
        <div class="nav-items">
            <a href="dashboard.php" class="nav-item-mobile">
                <i class="bi bi-house"></i>
                <span>Início</span>
            </a>
            <a href="estatisticas.php" class="nav-item-mobile active">
                <i class="bi bi-graph-up-arrow"></i>
                <span>Progresso</span>
            </a>
            <a href="diario.php" class="nav-item-mobile">
                <i class="bi bi-journal-text"></i>
                <span>Diário</span>
            </a>
            <a href="perfil.php" class="nav-item-mobile">
                <i class="bi bi-person"></i>
                <span>Perfil</span>
            </a>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/estatisticas.js"></script>
</body>
</html>
