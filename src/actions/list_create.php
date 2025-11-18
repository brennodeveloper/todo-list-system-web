<?php
require_once __DIR__ . '/../../config/database.php';

header('Content-Type: application/json');

// 1. Verificação de Segurança
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'Usuário não autenticado']);
    exit;
}

try {
    $title = trim($_POST['title'] ?? '');
    $user_id = $_SESSION['user_id']; // Pega o ID do usuário logado

    // 2. Validação
    if ($title === '') {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Título vazio']);
        exit;
    }

    $pdo = Database::getInstance();
    
    // 3. Query Corrigida (inclui user_id)
    $stmt = $pdo->prepare("INSERT INTO todo_lists (title, user_id) VALUES (?, ?)");
    $stmt->execute([$title, $user_id]);

    echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}