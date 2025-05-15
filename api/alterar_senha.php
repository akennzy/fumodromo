<?php
session_start();
header('Content-Type: application/json');
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Não autenticado']);
    exit;
}

$data = json_decode(file_get_contents('php://input'));

if (!isset($data->senhaAtual) || !isset($data->novaSenha)) {
    echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
    exit;
}

try {
    // Verifica a senha atual
    $stmt = $pdo->prepare("SELECT senha FROM usuarios WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $usuario = $stmt->fetch();

    if (!password_verify($data->senhaAtual, $usuario->senha)) {
        echo json_encode(['success' => false, 'message' => 'Senha atual incorreta']);
        exit;
    }

    // Atualiza a senha
    $novaSenhaHash = password_hash($data->novaSenha, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
    $stmt->execute([$novaSenhaHash, $_SESSION['user_id']]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao alterar senha']);
}
?>
