<?php
session_start();

// Impede acesso sem login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../Assets/CSS/home.css" />
    <link rel="stylesheet" href="../Assets/CSS/style.css" />
    <title>To-do List</title>
</head>
<body>

<header class="top-bar">
    <span class="homepage">Homepage</span>
    <a href="../actions.php?action=logout" class="logout">log out</a>
</header>

<h1 class="title">Todo-List-System-Web</h1>

<section class="cards-container">
    <div class="card add-card">
        <span>+</span>
    </div>
</section>

<script src="../Assets/JS/home.js"></script>
</body>
</html>