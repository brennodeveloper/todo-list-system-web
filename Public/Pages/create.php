<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- CSS externo -->
    <link rel="stylesheet" href="../Assets/CSS/create.css">

    <title>Minha To-do List</title>
</head>
<body>

    <div class="todo-container">
        <header class="todo-header">
            <h1>Adicionar Nova Tarefa</h1>
            <span class="item-count">0 item(s)</span>
            <button class="delete-all-btn" title="Excluir Lista">&#x1F5D1;</button> 
        </header>

        <hr class="header-divider">

        <main class="todo-list-area">
            <p class="empty-list-message">Sua lista está vazia</p>
        </main>

        <hr class="input-divider">

        <section class="add-item-section">
            <input type="text" 
                   class="new-item-input" 
                   placeholder="Digite sua lista aqui"
                   aria-label="Nova tarefa">
            
            <button class="add-item-btn">Add item</button>
        </section>
    </div>

</body>
</html>
