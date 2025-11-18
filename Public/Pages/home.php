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
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../Assets/CSS/home.css">
<title>To-do List</title>
</head>
<body>

    <header class="top-bar">
        <span class="homepage">Homepage</span>
        <a href="#" class="logout">log out</a>
    </header>

<h1 class="title">Todo-List-System-Web</h1>

    <section class="cards-container">

        <!-- CARD 1 -->
        <div class="card yellow">
            <h2>título do to do list 1</h2>
            <p>(anotações)</p>

            <ul>
                <li><input type="checkbox"> to-do item</li>
                <li><input type="checkbox"> to-do item</li>
                <li><input type="checkbox"> to-do item</li>
                <li><input type="checkbox"> to-do item</li>
            </ul>
        </div>

        <!-- CARD 2 -->
        <div class="card blue">
            <h2>título do to do list 2</h2>
            <p>(anotações)</p>

            <ul>
                <li><input type="checkbox"> to-do item</li>
                <li><input type="checkbox"> to-do item</li>
                <li><input type="checkbox"> to-do item</li>
                <li><input type="checkbox"> to-do item</li>
            </ul>
        </div>

        <!-- CARD 3 -->
        <div class="card pink">
            <h2>título do to do list 3</h2>
            <p>(anotações)</p>

            <ul>
                <li><input type="checkbox"> to-do item</li>
                <li><input type="checkbox"> to-do item</li>
                <li><input type="checkbox"> to-do item</li>
                <li><input type="checkbox"> to-do item</li>
            </ul>
        </div>

        <!-- CARD 4 -->
        <div class="card green">
            <h2>título do to do list 4</h2>
            <p>(anotações)</p>

            <ul>
                <li><input type="checkbox"> to-do item</li>
                <li><input type="checkbox"> to-do item</li>
                <li><input type="checkbox"> to-do item</li>
                <li><input type="checkbox"> to-do item</li>
            </ul>
        </div>

        <!-- CARD 5 -->
        <div class="card purple">
            <h2>título do to do list 5</h2>
            <p>(anotações)</p>

            <ul>
                <li><input type="checkbox"> to-do item</li>
                <li><input type="checkbox"> to-do item</li>
                <li><input type="checkbox"> to-do item</li>
                <li><input type="checkbox"> to-do item</li>
            </ul>
        </div>

        <!-- BOTÃO DE + -->
        <div class="card add-card">
            <span>+</span>
        </div>

    </section>
</body>
</html>