<?php
session_start();
header('Content-Type: application/json');
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Não autenticado']);
    exit;
}

try {
    $data = json_decode(file_get_contents('php://input'));

    // Validar dados básicos
    if (!isset($data->nome) || !isset($data->dataNascimento)) {
        throw new Exception('Dados incompletos');
    }

    // Preparar os campos para atualização
    $campos = [
        'nome' => $data->nome,
        'data_nascimento' => $data->dataNascimento,
        'razao_mudanca' => $data->razaoMudanca ?? null,
        'medos_preocupacoes' => $data->medosPreocupacoes ?? null,
        'cigarros_dia' => $data->cigarrosDia ?? null,
        'cigarros_maco' => $data->cigarrosMaco ?? null,
        'valor_maco' => $data->valorMaco ?? null,
        'notif_diario' => $data->notifDiario ?? true,
        'notif_conquistas' => $data->notifConquistas ?? true,
        'notif_milestones' => $data->notifMilestones ?? true
    ];

$data = json_decode(file_get_contents('php://input'));

if (!isset($data->nome) || !isset($data->dataNascimento)) {
    echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        UPDATE usuarios 
        SET 
            nome = ?,
            data_nascimento = ?,
            razao_mudanca = ?,
            medos_preocupacoes = ?,
            cigarros_dia = ?,
            cigarros_maco = ?,
            valor_maco = ?
        WHERE id = ?
    ");

    $stmt->execute([
        $data->nome,
        $data->dataNascimento,
        $data->razaoMudanca,
        $data->medosPreocupacoes,
        $data->cigarrosDia,
        $data->cigarrosMaco,
        $data->valorMaco,
        $_SESSION['user_id']
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao atualizar perfil']);
}
?>
