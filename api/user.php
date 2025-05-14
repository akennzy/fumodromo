<?php
require_once '../config.php';
session_start();
header('Content-Type: application/json');

function updateProgress($userId, $days, $money_saved) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("UPDATE usuarios SET days_smoke_free = ?, money_saved = ? WHERE id = ?");
        $stmt->execute([$days, $money_saved, $userId]);
        return ['success' => true];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erro ao atualizar progresso'];
    }
}

function getProgress($userId) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT days_smoke_free, money_saved FROM usuarios WHERE id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
        exit;
    }
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    switch ($data['action']) {
        case 'update':
            echo json_encode(updateProgress($_SESSION['user_id'], $data['days'], $data['money_saved']));
            break;
        case 'get':
            echo json_encode(getProgress($_SESSION['user_id']));
            break;
    }
}
?>
