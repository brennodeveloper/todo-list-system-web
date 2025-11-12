<?php
// Arquivo: todo-list-system-web/public/actions.php

// Inicia a sessão (necessário para os scripts de autenticação)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclui o arquivo de conexão. O caminho está correto pois estamos na pasta public/
require_once __DIR__ . '/../config/database.php';

// 1. Garante que o método é POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: pages/login.php"); 
    exit;
}

// 2. Verifica qual ação foi solicitada pelo formulário
// O valor vem do campo <input type="hidden" name="action_type" ...>
$action = filter_input(INPUT_POST, 'action_type', FILTER_SANITIZE_STRING);

switch ($action) {
    case 'login':
        // Inclui o script de login seguro (agora o caminho é relativo a public/)
        require_once __DIR__ . '/../src/actions/auth_login.php';
        break;

    case 'register':
        // Inclui o script de registro seguro (agora o caminho é relativo a public/)
        require_once __DIR__ . '/../src/actions/auth_register.php';
        break;

    default:
        // Ação desconhecida - Redireciona de volta
        header("Location: pages/login.php?error=invalid_action");
        exit;
}
?>