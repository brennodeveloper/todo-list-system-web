<?php
// public/pages/register.php

// Inicia a sessão para poder exibir mensagens
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error_message = '';
$success_message = '';

// Lógica para exibir mensagens de ERRO
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'missing_fields':
            $error_message = 'Preencha todos os campos para criar a conta.';
            break;
        case 'password_mismatch':
            $error_message = 'As senhas digitadas não coincidem. Por favor, verifique.';
            break;
        case 'user_exists':
            $error_message = 'Este e-mail já está cadastrado. Tente fazer login.';
            break;
        case 'db_error':
            $error_message = 'Ocorreu um erro no servidor. Tente novamente mais tarde.';
            break;
    }
}

// Lógica para exibir mensagem de SUCESSO
if (isset($_GET['success']) && $_GET['success'] === 'registered') {
    $success_message = 'Conta criada com sucesso! Faça login abaixo.';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Todo List System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body class="login-body">
    <div class="container-login">
        <div class="card-login">
            
            <?php if (!empty($error_message)): ?>
                <div class="alert-error"><?php echo htmlspecialchars($error_message); ?></div>
            <?php elseif (!empty($success_message)): ?>
                <div class="alert-success"><?php echo htmlspecialchars($success_message); ?></div>
            <?php endif; ?>

            <h1>Crie sua conta</h1>
            <p></p>

            <form action="../actions.php" method="POST" class="form-login">
                <input type="hidden" name="action_type" value="register">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="password_confirm" placeholder="Confirm Password" required>

                <button type="submit" class="btn-primary">Register</button>
            </form>

            <p style="margin-top: 20px; font-size: 14px; color: #666;">
                Já tem uma conta? <a href="login.php" style="color: #007bff; text-decoration: none;">Faça login aqui</a>
            </p>
        </div>
    </div>
</body>
</html>