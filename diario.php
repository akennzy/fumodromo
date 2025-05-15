<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diário - FumoDromo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="bg-gradient">
    <div class="container py-4">
        <!-- Cabeçalho -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 mb-0">
                <i class="bi bi-journal-text me-2 text-primary"></i>
                Seu Diário
            </h1>
            <div class="progress-stats d-flex gap-3 align-items-center">
                <div class="text-end">
                    <small class="text-muted d-block">Dias Seguidos</small>
                    <span class="h4 mb-0" id="diasSeguidos">0</span>
                </div>
                <div class="icon-container">
                    <i class="bi bi-calendar-check text-primary"></i>
                </div>
            </div>
        </div>

        <!-- Contêiner Principal -->
        <div class="main-content">
            <!-- Filtros e Busca -->
            <div class="filter-section mb-4 p-3 rounded-3 bg-white bg-opacity-75 backdrop-blur">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-search text-primary"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" id="searchText" placeholder="Buscar nas anotações...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="filterHumor">
                            <option value="">Todos os humores</option>
                            <option value="5">Muito Bem</option>
                            <option value="4">Bem</option>
                            <option value="3">Neutro</option>
                            <option value="2">Mal</option>
                            <option value="1">Muito Mal</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="sortOrder">
                            <option value="newest">Mais recentes primeiro</option>
                            <option value="oldest">Mais antigas primeiro</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Resumo do Progresso -->
            <div class="progress-summary mb-4 p-4">
                <h5 class="mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-graph-up text-primary"></i>
                    Seu Progresso
                </h5>
                <div class="d-flex gap-3 flex-wrap">
                    <div class="progress-item">
                        <small class="text-muted">Total de Registros</small>
                        <h3 id="totalRegistros">0</h3>
                    </div>
                    <div class="progress-item">
                        <small class="text-muted">Humor Médio</small>
                        <h3 id="humorMedio">-</h3>
                    </div>
                    <div class="progress-item">
                        <small class="text-muted">Maior Sequência</small>
                        <h3 id="maiorSequencia">0</h3>
                    </div>
                </div>
            </div>

            <!-- Lista de Entradas -->
            <div class="entries-list">
                <!-- Entradas serão carregadas dinamicamente aqui -->
            </div>

            <!-- Estado Vazio -->
            <div id="emptyState" class="text-center p-5" style="display: none;">
                <div class="empty-state-icon mb-4">
                    <i class="bi bi-journal-plus display-1 text-primary mood-animation"></i>
                </div>
                <h4 class="mt-3">Seu diário está vazio</h4>
                <p class="text-muted">Comece a registrar como você está se sentindo!</p>
                <button class="btn btn-primary mt-3" onclick="abrirModalEntrada()">
                    <i class="bi bi-plus-lg me-2"></i>
                    Fazer Primeiro Registro
                </button>
            </div>
        </div>
    </div>

    <!-- Botão Flutuante de Nova Entrada -->
    <button class="add-entry-fab" onclick="abrirModalEntrada()" title="Nova Entrada">
        <i class="bi bi-plus-lg"></i>
    </button>

    <!-- Modal de Nova Entrada -->
    <div class="modal fade" id="entradaModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title">Como você está hoje?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="diarioForm">
                        <!-- Humor -->
                        <div class="mb-4">
                            <label class="form-label d-block text-center h5 mb-3">Seu humor</label>
                            <div class="mood-selector">
                                <div class="mood-option" data-value="1">
                                    <i class="bi bi-emoji-frown"></i>
                                    <span>Muito Mal</span>
                                </div>
                                <div class="mood-option" data-value="2">
                                    <i class="bi bi-emoji-frown-fill"></i>
                                    <span>Mal</span>
                                </div>
                                <div class="mood-option" data-value="3">
                                    <i class="bi bi-emoji-neutral"></i>
                                    <span>Neutro</span>
                                </div>
                                <div class="mood-option" data-value="4">
                                    <i class="bi bi-emoji-smile"></i>
                                    <span>Bem</span>
                                </div>
                                <div class="mood-option" data-value="5">
                                    <i class="bi bi-emoji-laughing"></i>
                                    <span>Muito Bem</span>
                                </div>
                            </div>
                            <input type="hidden" id="humor" name="humor" required>
                        </div>

                        <!-- Níveis -->
                        <div class="mb-4">
                            <label class="form-label">Nível de Ansiedade</label>
                            <div class="range-slider">
                                <input type="range" class="form-range" id="nivelAnsiedade" name="nivelAnsiedade" min="1" max="5" value="3">
                                <div class="range-labels d-flex justify-content-between">
                                    <span class="text-muted">Muito Baixo</span>
                                    <span class="text-muted">Muito Alto</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Vontade de Fumar</label>
                            <div class="range-slider">
                                <input type="range" class="form-range" id="nivelVontade" name="nivelVontade" min="1" max="5" value="3">
                                <div class="range-labels d-flex justify-content-between">
                                    <span class="text-muted">Muito Baixa</span>
                                    <span class="text-muted">Muito Alta</span>
                                </div>
                            </div>
                        </div>

                        <!-- Texto -->
                        <div class="mb-4">
                            <label for="texto" class="form-label">Como você está se sentindo?</label>
                            <textarea class="form-control" id="texto" name="texto" rows="4" 
                                placeholder="Compartilhe seus pensamentos, desafios e conquistas..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary px-4" id="salvarEntrada">
                        <i class="bi bi-check-lg me-2"></i>Salvar
                    </button>
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
            <a href="diario.php" class="nav-item-mobile active">
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
    <script src="js/diario.js"></script>
</body>
</html>
