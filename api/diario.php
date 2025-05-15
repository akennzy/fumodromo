<?php
session_start();
header('Content-Type: application/json');
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Não autenticado']);
    exit;
}

// GET: Retorna as entradas do diário com filtros e estatísticas
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $where = ['usuario_id = ?'];
        $params = [$_SESSION['user_id']];
        
        // Aplicar filtros
        if (isset($_GET['humor']) && $_GET['humor'] !== '') {
            $where[] = 'humor = ?';
            $params[] = $_GET['humor'];
        }
        
        if (isset($_GET['search']) && $_GET['search'] !== '') {
            $where[] = 'texto LIKE ?';
            $params[] = '%' . $_GET['search'] . '%';
        }
        
        // Montar query base
        $whereClause = implode(' AND ', $where);
        $orderBy = isset($_GET['sort']) && $_GET['sort'] === 'oldest' 
            ? 'ORDER BY data_registro ASC' 
            : 'ORDER BY data_registro DESC';
        
        // Buscar entradas
        $stmt = $pdo->prepare("
            SELECT * FROM diario 
            WHERE {$whereClause}
            {$orderBy}
            LIMIT 50
        ");
        $stmt->execute($params);
        $entradas = $stmt->fetchAll();
        
        // Buscar estatísticas
        $stats = [];
        
        // Total de registros
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as total FROM diario 
            WHERE usuario_id = ?
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $stats['total_registros'] = $stmt->fetch()['total'];
        
        // Dias seguidos
        $stmt = $pdo->prepare("
            WITH RECURSIVE dias AS (
                SELECT data_registro::date as dia, 1 as streak
                FROM diario
                WHERE usuario_id = ?
                  AND data_registro >= CURRENT_DATE - INTERVAL '30 days'
                GROUP BY data_registro::date
                ORDER BY data_registro::date DESC
                LIMIT 1
                
                UNION ALL
                
                SELECT d.dia - INTERVAL '1 day', streak + 1
                FROM dias d
                WHERE EXISTS (
                    SELECT 1
                    FROM diario
                    WHERE usuario_id = ?
                      AND data_registro::date = d.dia - INTERVAL '1 day'
                    GROUP BY data_registro::date
                )
            )
            SELECT MAX(streak) as dias_seguidos
            FROM dias
        ");
        $stmt->execute([$_SESSION['user_id'], $_SESSION['user_id']]);
        $stats['dias_seguidos'] = $stmt->fetch()['dias_seguidos'] ?? 0;
        
        // Humor médio dos últimos 30 dias
        $stmt = $pdo->prepare("
            SELECT ROUND(AVG(humor), 1) as humor_medio
            FROM diario
            WHERE usuario_id = ?
              AND data_registro >= CURRENT_DATE - INTERVAL '30 days'
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $stats['humor_medio'] = $stmt->fetch()['humor_medio'];
        
        echo json_encode([
            'success' => true,
            'entradas' => $entradas,
            'stats' => $stats
        ]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erro ao carregar entradas']);
    }
}

// POST: Cria uma nova entrada
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'));

    if (!isset($data->humor) || !isset($data->nivelAnsiedade) || !isset($data->nivelVontade)) {
        echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO diario (
                usuario_id, humor, nivel_ansiedade, 
                nivel_vontade, texto
            ) VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $_SESSION['user_id'],
            $data->humor,
            $data->nivelAnsiedade,
            $data->nivelVontade,
            $data->texto ?? null
        ]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erro ao salvar entrada']);
    }
}
?>
