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
    $task_id = $_POST['id'] ?? ''; // ID da tarefa
    $user_id = $_SESSION['user_id'];

    // 2. Validação
    if ($task_id === '') {
        http_response_code(400);
        echo json_encode(['error' => 'ID da tarefa não fornecido']);
        exit;
    }

    $pdo = Database::getInstance();
    
    // 3. Query de Deleção Segura
    // Deleta a tarefa ONDE o id da tarefa bate E 
    // o 'todo_id' (id da lista) da tarefa está DENTRO de um SELECT de listas que pertencem ao usuário
    $stmt = $pdo->prepare("
        DELETE FROM tasks 
        WHERE id = :task_id 
        AND todo_id IN (SELECT id FROM todo_lists WHERE user_id = :user_id)
    ");
    $stmt->execute([
        ':task_id' => $task_id,
        ':user_id' => $user_id
    ]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Tarefa não encontrada ou não pertence a este usuário']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}