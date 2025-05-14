<?php
require_once '../config.php';
header('Content-Type: application/json');

function login($username, $password) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT id, username, password FROM usuarios WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user->password)) {
        session_start();
        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->username;
        return ['success' => true];
    }
    
    return ['success' => false, 'message' => 'Credenciais inválidas'];
}

function register($username, $password) {
    global $pdo;
    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO usuarios (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $hashedPassword]);
        return ['success' => true];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erro ao registrar usuário'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['action'])) {
        switch ($data['action']) {
            case 'login':
                echo json_encode(login($data['username'], $data['password']));
                break;
            case 'register':
                echo json_encode(register($data['username'], $data['password']));
                break;
        }
    }
}
?>
