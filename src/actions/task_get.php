<?php
require __DIR__ . '/../../config/database.php';
header('Content-Type: application/json');

// 1. Verificação de Segurança
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Usuário não autenticado']);
    exit;
}

try {
    // 2. Nomes Corrigidos
    $todo_id = $_GET['list_id'] ?? ''; // Recebe 'list_id' da URL
    $user_id = $_SESSION['user_id'];

    if ($todo_id === '') {
        http_response_code(400);
        echo json_encode(['error' => 'ID da lista não fornecido']);
        exit;
    }

    $pdo = Database::getInstance();
    
    // 3. Query Segura
    // Seleciona as tarefas ONDE o todo_id bate E
    // esse todo_id pertence ao usuário logado
    $stmt = $pdo->prepare("
        SELECT * FROM tasks 
        WHERE todo_id = :todo_id
        AND todo_id IN (SELECT id FROM todo_lists WHERE user_id = :user_id)
    ");
    $stmt->execute([
        ':todo_id' => $todo_id,
        ':user_id' => $user_id
    ]);

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}