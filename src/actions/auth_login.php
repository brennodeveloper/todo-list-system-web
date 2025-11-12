<?php
// 1. INICIA A SESSÃO
// Deve ser a primeira coisa no arquivo
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. INCLUI SUA CLASSE DE BANCO DE DADOS
// Usamos __DIR__ para garantir que o caminho funcione de qualquer lugar
require_once __DIR__ . '/../../config/database.php';

// Verifica se a requisição é um POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 3. OBTÉM A CONEXÃO USANDO SEU MÉTODO
    $pdo = Database::getInstance(); 

    // 4. Coleta e sanitiza os dados
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_DEFAULT); // Senha não se sanitiza, se compara

    if (empty($email) || empty($password)) {
        header("Location: pages/login.php?error=missing_fields");
        exit;
    }

    // 5. Lógica de autenticação
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        // 6. VERIFICA A SENHA COM HASH
        // $user['password'] deve conter o HASH do banco
        if ($user && password_verify($password, $user['password'])) {
            // Sucesso no Login!
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];

            // Redireciona para a homepage
            // (Note que a homepage no seu caso pode ser index.php ou home.php)
           header("Location: pages/home.php"); // <- Verifique se é 'home.php' ou 'index.php'
            exit;
        } else {
            // Credenciais inválidas
            header("Location: pages/login.php?error=invalid_credentials");
            exit;
        }
    } catch (PDOException $e) {
        // Erro no banco de dados
        error_log("DB Error during login: " . $e->getMessage());
        header("Location: pages/login.php?error=db_error");
        exit;
    }
} else {
    // Tentativa de acesso direto
    header("Location: pages/login.php");
    exit;
}