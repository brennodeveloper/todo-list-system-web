<?php
require __DIR__ . '/../../config/database.php';
header('Content-Type: application/json');

// 1. Verificação de Segurança
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'Usuário não autenticado']);
    exit;
}

try {
    $id = $_POST['id'] ?? '';
    $user_id = $_SESSION['user_id'];

    // 2. Validação
    if ($id === '') {
        http_response_code(400);
        echo json_encode(['error' => 'ID não fornecido']);
        exit;
    }

    $pdo = Database::getInstance();
    
    // 3. Query Corrigida (checa user_id)
    // Garante que o usuário só pode deletar a PRÓPRIA lista
    $stmt = $pdo->prepare("DELETE FROM todo_lists WHERE id = :id AND user_id = :user_id");
    $stmt->execute([
        ':id' => $id,
        ':user_id' => $user_id
    ]);

    // 4. Verifica se algo foi realmente deletado
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(404); // Not Found (ou 403 Forbidden)
        echo json_encode(['error' => 'Lista não encontrada ou não pertence a este usuário']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}