<?php
require_once '../config.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die(json_encode(['error' => 'Não autorizado']));
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Buscar conquistas do usuário
        $stmt = $pdo->prepare("
            SELECT c.*, uc.data_obtida 
            FROM conquistas c
            LEFT JOIN usuario_conquistas uc ON c.id = uc.conquista_id AND uc.usuario_id = ?
            ORDER BY c.tipo, c.pontos
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $conquistas = $stmt->fetchAll();

        // Buscar nível e pontos do usuário
        $stmt = $pdo->prepare("SELECT nivel, pontos, proxima_pontuacao FROM usuarios WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $progressoUsuario = $stmt->fetch();

        echo json_encode([
            'conquistas' => $conquistas,
            'progresso' => $progressoUsuario
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao carregar conquistas']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $conquista_id = $data['conquista_id'];

    try {
        // Verificar se já tem a conquista
        $stmt = $pdo->prepare("
            SELECT COUNT(*) FROM usuario_conquistas 
            WHERE usuario_id = ? AND conquista_id = ?
        ");
        $stmt->execute([$_SESSION['user_id'], $conquista_id]);
        if ($stmt->fetchColumn() > 0) {
            http_response_code(400);
            die(json_encode(['error' => 'Conquista já obtida']));
        }

        // Registrar nova conquista
        $stmt = $pdo->prepare("
            INSERT INTO usuario_conquistas (usuario_id, conquista_id)
            VALUES (?, ?)
        ");
        $stmt->execute([$_SESSION['user_id'], $conquista_id]);

        // Adicionar pontos e verificar level up
        $stmt = $pdo->prepare("
            SELECT pontos FROM conquistas WHERE id = ?
        ");
        $stmt->execute([$conquista_id]);
        $pontos = $stmt->fetchColumn();

        $stmt = $pdo->prepare("
            UPDATE usuarios 
            SET pontos = pontos + ?,
                nivel = FLOOR(1 + (pontos + ?) / 100),
                proxima_pontuacao = (FLOOR(1 + (pontos + ?) / 100) * 100)
            WHERE id = ?
        ");
        $stmt->execute([$pontos, $pontos, $pontos, $_SESSION['user_id']]);

        // Retornar dados atualizados
        $stmt = $pdo->prepare("SELECT nivel, pontos, proxima_pontuacao FROM usuarios WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $progressoUsuario = $stmt->fetch();

        echo json_encode([
            'success' => true,
            'mensagem' => 'Conquista desbloqueada!',
            'pontos_ganhos' => $pontos,
            'progresso' => $progressoUsuario
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao registrar conquista']);
    }
}
