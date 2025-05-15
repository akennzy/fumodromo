<?php
header('Content-Type: application/json');
require_once '../config.php';

$data = json_decode(file_get_contents('php://input'));

// Validação básica
if (!isset($data->usuario) || !isset($data->senha) || !isset($data->nome)) {
    echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
    exit;
}

try {
    // Verifica se o usuário já existe
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = ?");
    $stmt->execute([$data->usuario]);
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => false, 'message' => 'Este nome de usuário já está em uso']);
        exit;
    }

    // Insere o novo usuário
    $stmt = $pdo->prepare("INSERT INTO usuarios (
        nome, usuario, senha, data_nascimento, razao_mudanca,
        medos_preocupacoes, data_inicio_fumo, tentativas_parar,
        cigarros_dia, cigarros_maco, valor_maco
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $senha_hash = password_hash($data->senha, PASSWORD_DEFAULT);

    $stmt->execute([
        $data->nome,
        $data->usuario,
        $senha_hash,
        $data->dataNascimento,
        $data->razaoMudanca,
        $data->medosPreocupacoes,
        $data->dataInicioFumo,
        $data->tentativasParar,
        $data->cigarrosDia,
        $data->cigarrosMaco,
        $data->valorMaco
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar usuário']);
}
?>
