<?php
require_once '../config.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die(json_encode(['error' => 'Não autorizado']));
}

function verificarSequenciaChecklist($pdo, $userId, $item, $dias) {
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as dias_seguidos
        FROM checklist
        WHERE usuario_id = ?
        AND item = ?
        AND completado = 1
        AND data >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
        GROUP BY item
    ");
    $stmt->execute([$userId, $item, $dias]);
    $result = $stmt->fetch();
    return $result && $result['dias_seguidos'] >= $dias;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $pdo->prepare("
            SELECT * FROM checklist 
            WHERE usuario_id = ? AND data = CURDATE()
        ");
        $stmt->execute([$_SESSION['user_id']]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao carregar checklist']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO checklist (usuario_id, item, completado, data)
            VALUES (?, ?, ?, CURDATE())
            ON DUPLICATE KEY UPDATE completado = VALUES(completado)
        ");
        $stmt->execute([
            $_SESSION['user_id'],
            $data['item'],
            $data['completado']
        ]);

        // Verificar conquistas baseadas no checklist
        if ($data['completado']) {
            if ($data['item'] === 'checkAgua' && verificarSequenciaChecklist($pdo, $_SESSION['user_id'], 'checkAgua', 3)) {
                // Conquista de hidratação
                $stmt = $pdo->prepare("SELECT id FROM conquistas WHERE titulo = 'Hidratação em Dia'");
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
            
            if ($data['item'] === 'checkExercicio' && verificarSequenciaChecklist($pdo, $_SESSION['user_id'], 'checkExercicio', 5)) {
                // Conquista de exercícios
                $stmt = $pdo->prepare("SELECT id FROM conquistas WHERE titulo = 'Exercício Regular'");
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

            if ($data['item'] === 'checkMeditacao' && verificarSequenciaChecklist($pdo, $_SESSION['user_id'], 'checkMeditacao', 7)) {
                // Conquista de meditação
                $stmt = $pdo->prepare("SELECT id FROM conquistas WHERE titulo = 'Meditação Constante'");
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
        }

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao salvar item do checklist']);
    }
}
