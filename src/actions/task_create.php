<?php
// Arquivo: src/actions/task_create.php

// Não precisa de session_start() aqui se já está no actions.php

require __DIR__ . '/../../config/database.php';
header('Content-Type: application/json');

// 1. Verificação de Segurança
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die(json_encode(['error' => 'Usuário não autenticado']));
}

// 2. Validação dos Dados
if (!isset($_POST['list_id']) || !isset($_POST['description']) || empty(trim($_POST['description']))) {
    http_response_code(400); // Bad Request
    die(json_encode(['error' => 'Dados da tarefa incompletos.']));
}

try {
    $pdo = Database::getInstance();
    $listId = filter_var($_POST['list_id'], FILTER_VALIDATE_INT);
    $content = trim($_POST['description']);
    $isDone = 0;
    
    // Garantir que o ID da lista é válido e que o user_id da lista bate
    // (Melhoria de segurança: evita que um usuário salve tarefas em listas de outro)
    $stmt = $pdo->prepare("SELECT user_id FROM todo_lists WHERE id = :id");
    $stmt->execute([':id' => $listId]);
    $listOwner = $stmt->fetchColumn();

    if (!$listOwner || $listOwner != $_SESSION['user_id']) {
        http_response_code(403);
        die(json_encode(['error' => 'Acesso negado à lista.']));
    }

    // 3. Inserção na Tabela 'tasks'
    // IMPORTANTE: 'todo_id' é a chave estrangeira (FK)
    $stmt = $pdo->prepare("INSERT INTO tasks (todo_id, content, is_done) VALUES (?, ?, ?)");
    $stmt->execute([$listId, $content, $isDone]);
    
    // Sucesso
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    http_response_code(500);
    error_log("Task creation failed: " . $e->getMessage());
    die(json_encode(['error' => 'Erro interno do servidor ao salvar tarefa.']));
}