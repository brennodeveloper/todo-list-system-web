<?php
require_once __DIR__ . '/config/database.php';

try {
    $db = Database::getInstance();

    // Tabela de usuários
    $db->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            email TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL
        );
    ");

    // Tabela de listas (cards)
    $db->exec("
        CREATE TABLE IF NOT EXISTS todo_lists (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            title TEXT NOT NULL,
            description TEXT,
            color TEXT DEFAULT '#ffffff',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY(user_id) REFERENCES users(id)
        );
    ");

    // Tabela de tarefas
    $db->exec("
        CREATE TABLE IF NOT EXISTS tasks (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            todo_id INTEGER NOT NULL,
            content TEXT NOT NULL,
            is_done INTEGER DEFAULT 0,
            FOREIGN KEY(todo_id) REFERENCES todo_lists(id)
        );
    ");

    echo "✅ Banco de dados e tabelas criados com sucesso!";
} catch (PDOException $e) {
    echo "❌ Erro ao criar o banco: " . $e->getMessage();
}
?>
