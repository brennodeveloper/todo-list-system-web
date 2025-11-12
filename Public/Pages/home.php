<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Home - ToDo List</title>
  <link rel="stylesheet" href="../Assets/CSS/style.css"/>
  <link rel="stylesheet" href="../Assets/CSS/home.css"/>
</head>

<body>
  <header>
    <h1>Minhas To-Do Lists</h1>
    <button id="logout-btn">Sair</button>
  </header>

  <main class="cards-container">
    <!-- Exemplo de card de to-do list -->
    <div class="todo-card" style="--card-color: #A5D8FF;">
      <h2>Título da Lista</h2>
      <p>Descrição breve da lista...</p>
      <ul class="tasks">
        <li><input type="checkbox" /> Jogar volei </li>
        <li><input type="checkbox" checked /> Ir para a Estácio</li>
      </ul>
      <div class="card-actions">
        <button class="edit">Editar</button>
        <button class="delete">Excluir</button>
      </div>
    </div>

    <!-- Card para criar nova lista -->
    <div class="todo-card add-card">
      <span>+</span>
    </div>
  </main>

  <script src="../Assets/JS/home.js"></script>
</body>
</html>
