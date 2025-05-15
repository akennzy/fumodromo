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
    <title>Dashboard - FumoDromo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
</head>
<body class="bg-gradient">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">
                <i class="bi bi-house-heart me-2"></i>
                Início
            </h1>
            <div class="welcome-icon">
                <i class="bi bi-stars display-4 text-primary animated-float"></i>
            </div>
        </div>

        <div class="row g-4">
            <!-- Contador em Tempo Real -->
            <div class="col-12">
                <div class="dashboard-card p-4 text-center fade-in">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <div class="timer-icon-container text-center">
                                <i class="bi bi-stopwatch display-1 text-primary animated-pulse"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="counter-container">
                        <div class="counter-item">
                            <span id="dias" class="counter-number">0</span>
                            <span class="counter-label">Dias</span>
                        </div>
                        <div class="counter-item">
                            <span id="horas" class="counter-number">0</span>
                            <span class="counter-label">Horas</span>
                        </div>
                        <div class="counter-item">
                            <span id="minutos" class="counter-number">0</span>
                            <span class="counter-label">Minutos</span>
                        </div>
                        <div class="counter-item">
                            <span id="segundos" class="counter-number">0</span>
                            <span class="counter-label">Segundos</span>
                        </div>
                    </div>
                    <h3 class="mt-3">Livre do Cigarro</h3>
                    <div class="progress mt-3" style="height: 10px;">
                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <!-- Botão de Diário Rápido -->
            <div class="col-12">
                <div class="dashboard-card p-3 text-center fade-in">
                    <h4>Como você está se sentindo?</h4>
                    <div class="mood-buttons mt-3">
                        <button class="btn mood-btn" data-mood="otimo">😄<span>Ótimo</span></button>
                        <button class="btn mood-btn" data-mood="bom">🙂<span>Bem</span></button>
                        <button class="btn mood-btn" data-mood="regular">😐<span>Regular</span></button>
                        <button class="btn mood-btn" data-mood="ruim">😕<span>Mal</span></button>
                        <button class="btn mood-btn" data-mood="pessimo">😫<span>Péssimo</span></button>
                    </div>
                </div>
            </div>

            <!-- Checklist Diário -->
            <div class="col-12">
                <div class="dashboard-card p-4 fade-in">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">Checklist Diário</h4>
                        <i class="bi bi-check2-circle display-4 text-success animated-bounce"></i>
                    </div>
                    <div class="checklist-container">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="checkAgua">
                            <label class="form-check-label" for="checkAgua">Beber mais água hoje</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="checkExercicio">
                            <label class="form-check-label" for="checkExercicio">Fazer exercício físico</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="checkMeditacao">
                            <label class="form-check-label" for="checkMeditacao">5 minutos de meditação</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="checkRespiracao">
                            <label class="form-check-label" for="checkRespiracao">Exercícios de respiração</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card de Nível e Conquistas -->
            <div class="col-12">
                <div class="dashboard-card p-4 fade-in">
                    <div class="level-header mb-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="mb-0">Nível <span id="nivelAtual">1</span></h4>
                            <div class="level-info">
                                <span id="pontosAtuais">0</span> / <span id="pontosProximoNivel">100</span> pontos
                            </div>
                        </div>
                        <div class="progress mt-2" style="height: 8px;">
                            <div class="progress-bar bg-success" id="progressoNivel" role="progressbar" style="width: 0%"></div>
                        </div>
                    </div>
                    
                    <h5 class="mb-3">Suas Conquistas</h5>
                    <div class="achievements-grid" id="conquistasContainer">
                        <!-- As conquistas serão inseridas aqui via JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Modal de Nova Conquista -->
            <div class="modal fade" id="conquistaModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body text-center py-4">
                            <div class="achievement-icon mb-3">
                                <i class="bi bi-trophy-fill display-1 text-warning animated-float"></i>
                            </div>
                            <h4>Nova Conquista Desbloqueada!</h4>
                            <p id="conquistaTitulo" class="mb-2"></p>
                            <p class="text-muted mb-3" id="conquistaDescricao"></p>
                            <div class="points-earned">
                                <span class="badge bg-success">+<span id="pontosGanhos">0</span> pontos</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cards de Estatísticas -->
            <div class="col-md-4">
                <div class="dashboard-card stat-card fade-in">
                    <div class="stat-icon">
                        <i class="bi bi-piggy-bank-fill display-4 text-success animated-bounce"></i>
                    </div>
                    <h3 class="stat-number" id="economiaTotal">R$ 0,00</h3>
                    <p class="text-muted">Economia Total</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="dashboard-card stat-card fade-in">
                    <div class="stat-icon">
                        <i class="bi bi-x-circle-fill display-4 text-danger animated-pulse"></i>
                    </div>
                    <h3 class="stat-number" id="cigarrosEvitados">0</h3>
                    <p class="text-muted">Cigarros Evitados</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="dashboard-card stat-card fade-in">
                    <div class="stat-icon">
                        <i class="bi bi-heart-pulse-fill display-4 text-danger animated-float"></i>
                    </div>
                    <h3 class="stat-number" id="vidaRecuperada">0h</h3>
                    <p class="text-muted">Vida Recuperada</p>
                </div>
            </div>

            <!-- Card de Motivação -->
            <div class="col-12">
                <div class="dashboard-card p-4 fade-in">
                    <h4 class="mb-3">Sua Motivação</h4>
                    <p class="lead"><?php echo htmlspecialchars($usuario->razao_mudanca); ?></p>
                </div>
            </div>

            <!-- Botões de Ação -->
            <div class="col-12 text-center mt-4">
                <button class="btn btn-primary me-2" id="btnRecomecar">
                    <i class="bi bi-arrow-clockwise"></i> Recomeçar Jornada
                </button>
                <button class="btn btn-outline-primary" id="btnAjuda">
                    <i class="bi bi-question-circle"></i> Preciso de Ajuda
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Recomeço -->
    <div class="modal fade" id="recomecarModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Recomeçar Jornada</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza que deseja recomeçar sua jornada? Seus dados anteriores serão mantidos no histórico.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="confirmarRecomecar">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Ajuda -->
    <div class="modal fade" id="ajudaModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Precisa de Ajuda?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h6>Linhas de Apoio</h6>
                            <ul class="list-unstyled">
                                <li><i class="bi bi-telephone"></i> Disque Saúde: 136</li>
                                <li><i class="bi bi-telephone"></i> CVV: 188</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Dicas Rápidas</h6>
                            <ul>
                                <li>Beba muita água</li>
                                <li>Pratique exercícios</li>
                                <li>Evite gatilhos conhecidos</li>
                                <li>Busque apoio de amigos e família</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Diário Rápido -->
    <div class="modal fade" id="diarioRapidoModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Adicionar Nota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <textarea class="form-control" id="notaDiario" rows="3" placeholder="Como você está se sentindo? (opcional)"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="salvarDiario">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Barra de Navegação Inferior -->
    <nav class="bottom-nav">
        <div class="nav-items">
            <a href="dashboard.php" class="nav-item-mobile active">
                <i class="bi bi-house-fill"></i>
                <span>Início</span>
            </a>
            <a href="estatisticas.php" class="nav-item-mobile">
                <i class="bi bi-graph-up"></i>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Passando dados do PHP para o JavaScript
        const userData = {
            cigarrosDia: <?php echo $usuario->cigarros_dia; ?>,
            valorMaco: <?php echo $usuario->valor_maco; ?>,
            cigarrosMaco: <?php echo $usuario->cigarros_maco; ?>
        };
    </script>
    <script>
        const userConfig = {
            dataParda: "<?php echo $usuario['data_parada']; ?>",
            cigarrosPorDia: <?php echo $usuario['cigarros_por_dia']; ?>,
            precoMaco: <?php echo $usuario['preco_maco']; ?>
        };
    </script>
    <script src="js/dashboard.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.js"></script>
</body>
</html>
