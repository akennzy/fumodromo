<!DOCTYPE html                    <div class="welcome-animation text-center">
                        <i class="bi bi-heart-pulse display-1 text-primary animated-float mb-3"></i>
                        <i class="bi bi-stars display-2 text-success animated-bounce"></i>
                    </div>lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FumoDromo - Sua Jornada Para Parar de Fumar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="bg-gradient">
    <div class="container min-vh-100 d-flex align-items-center justify-content-center">
        <div class="card login-card">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <div class="welcome-icon-container mb-3">
                        <i class="bi bi-shield-check display-1 text-primary animated-float"></i>
                    </div>
                    <h2 class="mt-3 mb-4 fw-bold text-primary">FumoDromo</h2>
                </div>

                <form id="loginForm" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="usuario" class="form-label">Usuário</label>
                        <input type="text" class="form-control" id="usuario" name="usuario" required>
                        <div class="invalid-feedback">
                            Por favor, insira seu usuário.
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="senha" name="senha" required>
                        <div class="invalid-feedback">
                            Por favor, insira sua senha.
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-3">Entrar</button>
                    <div class="text-center">
                        <p class="mb-0">Ainda não tem uma conta?</p>
                        <a href="cadastro.php" class="btn btn-link">Cadastre-se aqui</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="js/login.js"></script>
</body>
</html>
