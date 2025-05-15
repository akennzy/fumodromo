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
    <title>Perfil - FumoDromo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="bg-gradient">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">
                <i class="bi bi-person-circle me-2"></i>
                Seu Perfil
            </h1>
            <button class="btn btn-outline-primary rounded-pill" id="btnEditarPerfil">
                <i class="bi bi-pencil"></i> Editar Perfil
            </button>
        </div>

        <div class="row">
            <div class="col-lg-4 mb-4">
                <!-- Card de Informações Rápidas -->
                <div class="profile-quick-info p-4 rounded-4">
                    <div class="text-center mb-4">
                        <div class="avatar-container position-relative">
                            <div class="avatar-circle">
                                <i class="bi bi-person-circle" style="font-size: 4rem;"></i>
                            </div>
                            <span class="achievement-badge" title="Nível de Progresso">
                                <i class="bi bi-star-fill"></i>
                                <span id="nivelProgresso">1</span>
                            </span>
                        </div>
                        <h2 class="mt-3 mb-1"><?php echo htmlspecialchars($usuario->nome); ?></h2>
                        <p class="text-muted mb-3">Membro desde <?php echo date('d/m/Y', strtotime($usuario->data_cadastro)); ?></p>
                        
                        <div class="progress-ring mb-3">
                            <div class="progress-circle">
                                <span id="diasSemFumar">0</span>
                                <small>dias</small>
                            </div>
                            <p class="mt-2">Sem fumar</p>
                        </div>
                    </div>

                    <!-- Estatísticas Rápidas -->
                    <div class="quick-stats">
                        <div class="stat-item">
                            <i class="bi bi-piggy-bank"></i>
                            <div>
                                <h6>Economia Total</h6>
                                <p id="economiaTotal">R$ 0,00</p>
                            </div>
                        </div>
                        <div class="stat-item">
                            <i class="bi bi-heart-pulse"></i>
                            <div>
                                <h6>Saúde</h6>
                                <p id="saudeProgresso">+10%</p>
                            </div>
                        </div>
                        <div class="stat-item">
                            <i class="bi bi-trophy"></i>
                            <div>
                                <h6>Conquistas</h6>
                                <p id="totalConquistas">0/12</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="dashboard-card p-4">
                    <!-- Abas de Navegação -->
                    <ul class="nav nav-pills mb-4" id="profileTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#infoGeral">
                                <i class="bi bi-person"></i> Informações Gerais
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#motivacao">
                                <i class="bi bi-heart"></i> Motivação
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#consumo">
                                <i class="bi bi-graph-up"></i> Consumo
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="profileTabsContent">
                        <div class="tab-pane fade show active" id="infoGeral">
                            <form id="perfilForm" class="needs-validation" novalidate>

                    <form id="perfilForm" class="needs-validation" novalidate>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario->nome); ?>" required>
                                            <label for="nome">Nome completo</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="date" class="form-control" id="dataNascimento" name="dataNascimento" value="<?php echo $usuario->data_nascimento; ?>" required>
                                            <label for="dataNascimento">Data de nascimento</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6>Preferências de Notificação</h6>
                                    </div>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="notifDiario" checked>
                                        <label class="form-check-label" for="notifDiario">
                                            Lembretes diários
                                        </label>
                                    </div>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="notifConquistas" checked>
                                        <label class="form-check-label" for="notifConquistas">
                                            Novas conquistas
                                        </label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="notifMilestones" checked>
                                        <label class="form-check-label" for="notifMilestones">
                                            Marcos importantes
                                        </label>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-danger" id="btnAlterarSenha">
                                        <i class="bi bi-key"></i> Alterar Senha
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save"></i> Salvar Alterações
                                    </button>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="motivacao">
                                <div class="motivation-section">
                                    <div class="form-floating mb-4">
                                        <textarea class="form-control" id="razaoMudanca" name="razaoMudanca" style="height: 120px" required><?php echo htmlspecialchars($usuario->razao_mudanca); ?></textarea>
                                        <label for="razaoMudanca">Por que você quer parar de fumar?</label>
                                    </div>

                                    <div class="form-floating mb-4">
                                        <textarea class="form-control" id="medosPreocupacoes" name="medosPreocupacoes" style="height: 120px" required><?php echo htmlspecialchars($usuario->medos_preocupacoes); ?></textarea>
                                        <label for="medosPreocupacoes">Medos e preocupações</label>
                                    </div>

                                    <!-- Seção de Objetivos -->
                                    <div class="goals-section mb-4">
                                        <h6 class="mb-3">Seus Objetivos</h6>
                                        <div class="goal-items">
                                            <div class="goal-item">
                                                <i class="bi bi-heart-pulse"></i>
                                                <span>Melhorar a saúde</span>
                                            </div>
                                            <div class="goal-item">
                                                <i class="bi bi-piggy-bank"></i>
                                                <span>Economizar dinheiro</span>
                                            </div>
                                            <div class="goal-item">
                                                <i class="bi bi-people"></i>
                                                <span>Exemplo para família</span>
                                            </div>
                                            <div class="goal-item">
                                                <i class="bi bi-plus-circle"></i>
                                                <span>Adicionar objetivo</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="consumo">
                                <div class="mb-4">
                                    <h6 class="mb-3">Histórico de Consumo</h6>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="form-floating">
                                                <input type="number" class="form-control" id="cigarrosDia" name="cigarrosDia" value="<?php echo $usuario->cigarros_dia; ?>" required min="1">
                                                <label for="cigarrosDia">Cigarros por dia</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating">
                                                <input type="number" class="form-control" id="cigarrosMaco" name="cigarrosMaco" value="<?php echo $usuario->cigarros_maco; ?>" required min="1">
                                                <label for="cigarrosMaco">Cigarros por maço</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating">
                                                <input type="number" class="form-control" id="valorMaco" name="valorMaco" value="<?php echo $usuario->valor_maco; ?>" required min="0" step="0.01">
                                                <label for="valorMaco">Valor do maço (R$)</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Economia -->
                                <div class="savings-summary mb-4">
                                    <h6 class="mb-3">Sua Economia</h6>
                                    <div class="card-deck">
                                        <div class="savings-card">
                                            <div class="savings-amount">R$ <span id="economiaDia">0,00</span></div>
                                            <div class="savings-period">Por dia</div>
                                        </div>
                                        <div class="savings-card">
                                            <div class="savings-amount">R$ <span id="economiaMes">0,00</span></div>
                                            <div class="savings-period">Por mês</div>
                                        </div>
                                        <div class="savings-card">
                                            <div class="savings-amount">R$ <span id="economiaAno">0,00</span></div>
                                            <div class="savings-period">Por ano</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Alteração de Senha -->
    <div class="modal fade" id="senhaModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Alterar Senha</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="senhaForm" class="needs-validation" novalidate>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="senhaAtual" required>
                            <label for="senhaAtual">Senha Atual</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="novaSenha" required>
                            <label for="novaSenha">Nova Senha</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="confirmarSenha" required>
                            <label for="confirmarSenha">Confirmar Nova Senha</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="salvarSenha">Salvar</button>
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
            <a href="estatisticas.php" class="nav-item-mobile">
                <i class="bi bi-graph-up"></i>
                <span>Progresso</span>
            </a>
            <a href="diario.php" class="nav-item-mobile">
                <i class="bi bi-journal-text"></i>
                <span>Diário</span>
            </a>
            <a href="perfil.php" class="nav-item-mobile active">
                <i class="bi bi-person-fill"></i>
                <span>Perfil</span>
            </a>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/perfil.js"></script>
</body>
</html>
