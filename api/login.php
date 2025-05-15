<?php
header('Content-Type: application/json');
require_once '../config.php';

$data = json_decode(file_get_contents('php://input'));

if (!isset($data->usuario) || !isset($data->senha)) {
    echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id, usuario, senha FROM usuarios WHERE usuario = ?");
    $stmt->execute([$data->usuario]);
    $user = $stmt->fetch();

    if ($user && password_verify($data->senha, $user->senha)) {
        session_start();
        $_SESSION['user_id'] = $user->id;
        $_SESSION['usuario'] = $user->usuario;
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Usuário ou senha incorretos']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro no servidor']);
}
?>
