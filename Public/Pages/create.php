<?php
session_start();

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
    <link rel="stylesheet" href="../Assets/CSS/create.css">
    <title>Minha To-do List</title>
</head>
<body>

<div class="todo-container">
    
    <header class="todo-header">
        <input type="text" 
            class="todo-title-input" 
            placeholder="Título da Lista" 
            aria-label="Título da Lista">

        <button class="delete-list-btn" title="Excluir Lista">&#x1F5D1;</button>  
    </header>

    <hr class="header-divider">

    <main class="todo-list-area">
        <p class="empty-list-message">Sua lista está vazia</p>
    </main>

    <hr class="input-divider">

    <section class="add-item-section">
        <input type="text" 
               class="new-item-input" 
               placeholder="Digite um item e pressione Enter"
               aria-label="Nova tarefa">
    </section>

    <footer class="todo-footer">
        <button class="cancel-btn">Cancelar</button>
        <button class="save-list-btn">Salvar Lista</button>
    </footer>
</div>

</body>
</html>