<?php
require_once '../config.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die(json_encode(['error' => 'Não autorizado']));
}

try {
    $pdo->beginTransaction();

    // Salvar dados da tentativa atual no histórico
    $stmt = $pdo->prepare("
        INSERT INTO historico_tentativas (
            usuario_id, 
            data_inicio, 
            data_fim,
            cigarros_evitados,
            economia,
            duracao_dias
        )
        SELECT 
            id as usuario_id,
            data_parada as data_inicio,
            NOW() as data_fim,
            DATEDIFF(NOW(), data_parada) * cigarros_por_dia as cigarros_evitados,
            (DATEDIFF(NOW(), data_parada) * cigarros_por_dia / 20) * preco_maco as economia,
            DATEDIFF(NOW(), data_parada) as duracao_dias
        FROM usuarios
        WHERE id = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);

    // Atualizar data de parada para agora
    $stmt = $pdo->prepare("
        UPDATE usuarios 
        SET data_parada = NOW()
        WHERE id = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);

    $pdo->commit();
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao reiniciar jornada']);
}
?>
