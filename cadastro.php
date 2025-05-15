<!DOCTYPE html                    <div class="signup-animation text-center">
                        <i class="bi bi-person-plus-fill display-1 text-primary animated-bounce mb-3"></i>
                        <i class="bi bi-stars display-2 text-success animated-float"></i>
                    </div>lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - FumoDromo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="bg-gradient">
    <div class="container py-5">
        <div class="card cadastro-card mx-auto">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <div class="signup-icon-container mb-3">
                        <i class="bi bi-person-plus-fill display-1 text-primary animated-float"></i>
                    </div>
                    <h2 class="mt-3 mb-4 fw-bold text-primary">Cadastro</h2>
                </div>

                <form id="cadastroForm" class="needs-validation" novalidate>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="nome" name="nome" required>
                                <label for="nome">Nome completo</label>
                                <div class="invalid-feedback">Por favor, insira seu nome.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="usuario" name="usuario" required>
                                <label for="usuario">Nome de usuário</label>
                                <div class="invalid-feedback">Por favor, escolha um nome de usuário.</div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="senha" name="senha" required>
                                <label for="senha">Senha</label>
                                <div class="invalid-feedback">Por favor, escolha uma senha.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="date" class="form-control" id="dataNascimento" name="dataNascimento" required>
                                <label for="dataNascimento">Data de nascimento</label>
                                <div class="invalid-feedback">Por favor, insira sua data de nascimento.</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <textarea class="form-control" id="razaoMudanca" name="razaoMudanca" required></textarea>
                        <label for="razaoMudanca">Por que você quer parar de fumar?</label>
                        <div class="invalid-feedback">Por favor, conte-nos sua motivação.</div>
                    </div>

                    <div class="form-floating mb-3">
                        <textarea class="form-control" id="medosPreocupacoes" name="medosPreocupacoes" required></textarea>
                        <label for="medosPreocupacoes">Quais são seus medos e preocupações?</label>
                        <div class="invalid-feedback">Por favor, compartilhe seus medos e preocupações.</div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="date" class="form-control" id="dataInicioFumo" name="dataInicioFumo" required>
                                <label for="dataInicioFumo">Quando começou a fumar?</label>
                                <div class="invalid-feedback">Por favor, insira quando começou a fumar.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" id="tentativasParar" name="tentativasParar" required min="0">
                                <label for="tentativasParar">Quantas vezes tentou parar?</label>
                                <div class="invalid-feedback">Por favor, insira o número de tentativas.</div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" id="cigarrosDia" name="cigarrosDia" required min="1">
                                <label for="cigarrosDia">Cigarros por dia</label>
                                <div class="invalid-feedback">Por favor, insira a quantidade.</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" id="cigarrosMaco" name="cigarrosMaco" required min="1">
                                <label for="cigarrosMaco">Cigarros por maço</label>
                                <div class="invalid-feedback">Por favor, insira a quantidade.</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" id="valorMaco" name="valorMaco" required min="0" step="0.01">
                                <label for="valorMaco">Valor do maço (R$)</label>
                                <div class="invalid-feedback">Por favor, insira o valor.</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">Cadastrar</button>
                        <a href="index.php" class="btn btn-link">Voltar para o login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/cadastro.js"></script>
</body>
</html>
