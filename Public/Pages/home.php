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
    <a href="#" class="logout" id="logout-btn">log out</a>
</header>

<h1 class="title">Todo-List-System-Web</h1>

<section class="cards-container">

    <!-- CARD 1 -->
    <div class="card yellow">
        <button class="delete-btn" data-delete aria-label="Excluir card">🗑️</button>
        <h2>To-do List 1</h2>
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
        <button class="delete-btn" data-delete aria-label="Excluir card">🗑️</button>
        <h2>To-do List 2</h2>
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
        <button class="delete-btn" data-delete aria-label="Excluir card">🗑️</button>
        <h2>To-do List 3</h2>
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
        <button class="delete-btn" data-delete aria-label="Excluir card">🗑️</button>
        <h2>To-do List 4</h2>
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
        <button class="delete-btn" data-delete aria-label="Excluir card">🗑️</button>
        <h2>To-do List 5</h2>
        <p>(anotações)</p>
        <ul>
            <li><input type="checkbox"> to-do item</li>
            <li><input type="checkbox"> to-do item</li>
            <li><input type="checkbox"> to-do item</li>
            <li><input type="checkbox"> to-do item</li>
        </ul>
    </div>

    <!-- BOTÃO DE ADICIONAR CARD -->
    <div class="card add-card" id="btnAddCard"><span>+</span></div>

</section>

<script>
// Remove card ao clicar na lixeira
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[data-delete]').forEach(btn => {
        btn.addEventListener('click', function() {
            const card = this.parentElement;
            card.remove();
        });
    });
});
</script>

<script src="../Public/JS/home.js"></script>

</body>
</html>