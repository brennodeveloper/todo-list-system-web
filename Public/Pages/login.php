<?php
// PARTE 1: LÓGICA PHP (VEM ANTES DE TODO O HTML)
// Inicia a sessão para poder exibir erros
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Lógica para exibir mensagens de erro
$error_message = '';
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'invalid_credentials':
            $error_message = 'Credenciais inválidas. Verifique seu e-mail e senha.';
            break;
        case 'missing_fields':
            $error_message = 'Preencha todos os campos para fazer login.';
            break;
        case 'db_error':
            $error_message = 'Ocorreu um erro interno. Tente novamente mais tarde.';
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Todo List System</title>
    <link rel="stylesheet" href="../Assets/CSS/style.css">
    <link rel="stylesheet" href="../Assets/CSS/login.css">
</head>
<body class="login-body">
    <div class="container-login">
        <div class="card-login">
            <?php if (!empty($error_message)): ?>
                <div class="alert-error"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
            <h1>Login</h1>
            <p>Escolha uma das opções para ir</p>

            <form action="../actions.php" method="POST" class="form-login">
                <input type="hidden" name="action_type" value="login">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" class="btn-primary">Login</button>
            </form>

            <p class="register-link-text">
                Você ainda não tem uma conta? <a href="register.php">Registre agora!</a>
            </p>
            </div>
    </div>
</body>
</html>