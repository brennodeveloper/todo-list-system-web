<?php
class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        // Caminho do banco de dados SQLite
        $db_path = __DIR__ . '/../database/todolist.db';

        // Garante que a pasta exista
        if (!file_exists(dirname($db_path))) {
            mkdir(dirname($db_path), 0777, true);
        }

        try {
            $this->connection = new PDO("sqlite:" . $db_path);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erro ao conectar ao banco de dados: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->connection;
    }
}