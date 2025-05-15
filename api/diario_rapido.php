<?php
require_once '../config.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die(json_encode(['error' => 'Não autorizado']));
}

function verificarRegistrosDiarios($pdo, $userId) {
    $stmt = $pdo->prepare("
        SELECT COUNT(DISTINCT DATE(criado_em)) as dias_registrados
        FROM diario_rapido
        WHERE usuario_id = ?
        AND criado_em >= DATE_SUB(CURDATE(), INTERVAL 5 DAY)
    ");
    $stmt->execute([$userId]);
    $result = $stmt->fetch();
    return $result && $result['dias_registrados'] >= 5;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['sentimento'])) {
        http_response_code(400);
        die(json_encode(['error' => 'Sentimento é obrigatório']));
    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO diario_rapido (usuario_id, sentimento, nota)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([
            $_SESSION['user_id'],
            $data['sentimento'],
            $data['nota'] ?? null
        ]);

        // Verificar conquista de registro diário
        if (verificarRegistrosDiarios($pdo, $_SESSION['user_id'])) {
            $stmt = $pdo->prepare("SELECT id FROM conquistas WHERE titulo = 'Diário Dedicado'");
            $stmt->execute();
            $conquistaId = $stmt->fetchColumn();
            if ($conquistaId) {
                file_get_contents("http://{$_SERVER['HTTP_HOST']}/api/conquistas.php", false, stream_context_create([
                    'http' => [
                        'method' => 'POST',
                        'header' => 'Content-Type: application/json',
                        'content' => json_encode(['conquista_id' => $conquistaId])
                    ]
                ]));
            }
        }

        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao salvar entrada do diário']);
    }
}
