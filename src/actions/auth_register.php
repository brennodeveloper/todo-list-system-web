<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclui a conexão com o banco de dados
require_once __DIR__ . '/../../config/database.php';
$pdo = Database::getInstance();

// Garante que é um POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: ../../public/pages/register.php");
    exit;
}

// 1. Coleta e validação
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);
$password_confirm = filter_input(INPUT_POST, 'password_confirm', FILTER_DEFAULT);

if (empty($email) || empty($password) || empty($password_confirm)) {
    // Redireciona com erro (campos faltando)
    header("Location: pages/register.php?error=missing_fields");
    exit;
}

if ($password !== $password_confirm) {
    // Redireciona com erro (senhas não batem)
    header("Location: pages/register.php?error=password_mismatch");
    exit;
}

// 2. Verifica se o usuário já existe
try {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    if ($stmt->fetch()) {
        // Redireciona com erro (usuário já existe)
        header("Location: pages/register.php?error=user_exists");
        exit;
    }

    // 3. CRIA O HASH DA SENHA (O PASSO CRÍTICO DE SEGURANÇA!)
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // 4. Insere o novo usuário
    $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (:email, :password_hash)");
    $stmt->execute([
        ':email' => $email,
        ':password_hash' => $password_hash
    ]);

    // 5. Sucesso e redirecionamento (Pode logar o usuário automaticamente ou mandar para o login)
    header("Location: pages/login.php?success=registered");
    exit;

} catch (PDOException $e) {
    // Erro no banco de dados
    error_log("DB Error during registration: " . $e->getMessage());
    header("Location: pages/register.php?error=db_error");;
    exit;
}