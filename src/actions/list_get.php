<?php
require_once __DIR__ . '/../../config/database.php';
header('Content-Type: application/json');

// 1. Verificação de Segurança
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Usuário não autenticado']);
    exit;
}

try {
    $user_id = $_SESSION['user_id'];
    $pdo = Database::getInstance();

    // 2. Query Corrigida (filtra por user_id)
    // Busca apenas as listas que pertencem ao usuário logado
    $stmt = $pdo->prepare("SELECT id, title FROM todo_lists WHERE user_id = :user_id ORDER BY id DESC");
    $stmt->execute([':user_id' => $user_id]);
    
    $lists = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($lists);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}