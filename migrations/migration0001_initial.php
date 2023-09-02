<?php

class migration0001_initial{
    public function create(): void
    {
        $db = \Core\Application::$app->db;
        $SQL = "
            CREATE TABLE products (
                id INT AUTO_INCREMENT PRIMARY KEY ,
                title VARCHAR(255) NOT NULL,
                thumbnail VARCHAR(255) NOT NULL,
                regular_price INTEGER NOT NULL,
                sale_price INTEGER NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
        $db->pdo->exec($SQL);

    }
    public function drop(): void
    {
        $db = \Core\Application::$app->db;
        $SQL = "DROP TABLE products";
        $db->pdo->exec($SQL);

    }
}