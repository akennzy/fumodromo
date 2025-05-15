<?php
session_start();
header('Content-Type: application/json');
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Não autenticado']);
    exit;
}

try {
    // Buscar dados do usuário
    $stmt = $pdo->prepare("SELECT data_parada, cigarros_dia, cigarros_maco, valor_maco FROM usuarios WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $usuario = $stmt->fetch();

    // Calcular dias sem fumar
    $dataParda = new DateTime($usuario->data_parada);
    $hoje = new DateTime();
    $diasSemFumar = $dataParda->diff($hoje)->days;

    // Calcular economia total
    $valorPorCigarro = $usuario->valor_maco / $usuario->cigarros_maco;
    $gastoDiario = $valorPorCigarro * $usuario->cigarros_dia;
    $economiaTotal = $gastoDiario * $diasSemFumar;

    // Calcular progresso de saúde (baseado em marcos reais de recuperação)
    $saudeProgresso = 0;
    if ($diasSemFumar >= 1) $saudeProgresso += 10; // 24h - Níveis de oxigênio normalizados
    if ($diasSemFumar >= 2) $saudeProgresso += 10; // 48h - Olfato e paladar melhoram
    if ($diasSemFumar >= 3) $saudeProgresso += 10; // 72h - Respiração mais fácil
    if ($diasSemFumar >= 7) $saudeProgresso += 15; // 1 semana - Melhora na circulação
    if ($diasSemFumar >= 14) $saudeProgresso += 15; // 2 semanas - Melhora na função pulmonar
    if ($diasSemFumar >= 30) $saudeProgresso += 20; // 1 mês - Redução significativa na tosse
    if ($diasSemFumar >= 90) $saudeProgresso += 20; // 3 meses - Função pulmonar aumenta até 30%

    // Buscar conquistas
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as obtidas,
               (SELECT COUNT(*) FROM conquistas) as total
        FROM conquistas_usuarios
        WHERE usuario_id = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $conquistas = $stmt->fetch();

    // Calcular nível (baseado em dias sem fumar e conquistas)
    $nivel = floor(($diasSemFumar / 7) + ($conquistas->obtidas / 2));
    if ($nivel < 1) $nivel = 1;

try {
    $stmt = $pdo->prepare("
        SELECT 
            data_parada,
            cigarros_dia,
            cigarros_maco,
            valor_maco
        FROM usuarios 
        WHERE id = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $usuario = $stmt->fetch();

    if (!$usuario->data_parada) {
        // Se ainda não iniciou a jornada, define como agora
        $stmt = $pdo->prepare("UPDATE usuarios SET data_parada = NOW() WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $usuario->data_parada = date('Y-m-d H:i:s');
    }

    // Calcula dias sem fumar
    $inicio = new DateTime($usuario->data_parada);
    $agora = new DateTime();
    $diff = $inicio->diff($agora);
    $dias = $diff->days;
    $horas = $diff->h + ($diff->days * 24);

    // Calcula economia
    $cigarrosDia = $usuario->cigarros_dia;
    $valorPorCigarro = $usuario->valor_maco / $usuario->cigarros_maco;
    $economia = $cigarrosDia * $valorPorCigarro * $dias;

    // Calcula cigarros evitados
    $cigarrosEvitados = $cigarrosDia * $dias;

    // Calcula tempo de vida recuperado (estimativa: 11 minutos por cigarro)
    $horasVida = ($cigarrosEvitados * 11) / 60;

    echo json_encode([
        'success' => true,
        'dias' => $dias,
        'economia' => $economia,
        'cigarrosEvitados' => $cigarrosEvitados,
        'horasVida' => round($horasVida)
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao carregar estatísticas']);
}
?>
