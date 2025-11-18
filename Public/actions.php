<?php
// Arquivo: Public/actions.php

// Inicia a sessão se ainda não iniciou
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclui a conexão com o banco
require_once dirname(__DIR__) . '/config/database.php';

// --- LÓGICA DE ROTEAMENTO INTELIGENTE ---

// 1. Tenta pegar 'action_type' (Usado pelos formulários de Login/Register)
$actionType = $_POST['action_type'] ?? '';

// 2. Tenta pegar 'action' (Usado pelo nosso JavaScript do Home)
$actionAjax = $_REQUEST['action'] ?? '';

// 3. Define qual ação usar (dá prioridade ao action_type)
$action = $actionType !== '' ? $actionType : $actionAjax;

// Caminho correto para a pasta SRC (subindo apenas 1 nível)
$srcPath = dirname(__DIR__) . '/src/actions';

switch ($action) {
    // =================================================
    // ÁREA DE AUTENTICAÇÃO (Login/Register)
    // =================================================

    case 'login':
        require $srcPath . '/auth_login.php';
        break;

    case 'register':
        require $srcPath . '/auth_register.php';
        break;

    // =================================================
    // ÁREA DO SISTEMA (Listas e Tarefas - AJAX)
    // =================================================

    // --- Listas ---
    case 'list_create':
        require $srcPath . '/list_create.php';
        break;

    case 'list_get':
        require $srcPath . '/list_get.php';
        break;

    case 'list_delete':
        require $srcPath . '/list_delete.php';
        break;

    // --- Tarefas ---
    case 'task_create':
        require $srcPath . '/task_create.php';
        break;

    case 'task_delete':
        require $srcPath . '/task_delete.php';
        break;

    case 'task_update':
        require $srcPath . '/task_update.php';
        break;

    case 'task_get':
        require $srcPath . '/task_get.php';
        break;

    // =================================================
    // PADRÃO (ERRO)
    // =================================================

    default:
        // Se veio pelo Ajax (JavaScript), retorna JSON de erro
        if ($actionAjax !== '') {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(['error' => 'Ação inválida ou não encontrada.']);
        } 
        // Se veio pelo navegador (acesso direto ou erro de login)
        else {
            header("Location: login.php?error=invalid_action");
        }
        exit;
}
?>
