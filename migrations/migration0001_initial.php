<?php

/*
 * A migrations script called by Core/Database->migrate();
 * All migration files in this folder are read, and their sql executed
 * Can be used with php migrations.php at top root of the project.
 * However not fully finished, there are no checks for existing tables,
 * migrations are not stored properly in the database, and not properly checked if they
 * have already been executed.
 *
 */
class migration0001_initial{
    public function create(): void
    {
        $db = \Core\Application::$app->db;
        $SQL_products = "
            CREATE TABLE IF products (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title VARCHAR(255) NOT NULL,
                thumbnail_path VARCHAR(255) NOT NULL,
                regular_price INTEGER NOT NULL,
                sale_price INTEGER NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
        $db->pdo->exec($SQL_products);

        // New orders table
        $SQL_orders = "
            CREATE TABLE orders (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                full_name VARCHAR(255) NOT NULL,
                address TEXT NOT NULL,
                order_total INTEGER NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
        $db->pdo->exec($SQL_orders);

        // New order_items table
        $SQL_order_items = "
            CREATE TABLE order_items (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                order_id INTEGER NOT NULL,
                product_id INTEGER NOT NULL,
                product_title VARCHAR(255) NOT NULL,
                quantity INTEGER NOT NULL,
                price INTEGER NOT NULL,
                line_total INTEGER NOT NULL,
                FOREIGN KEY (order_id) REFERENCES orders(id),
                FOREIGN KEY (product_id) REFERENCES products(id)
            )";
        $db->pdo->exec($SQL_order_items);

    }
    public function drop(): void
    {
        $db = \Core\Application::$app->db;

        $SQL_products = "DROP TABLE products";
        $db->pdo->exec($SQL_products);


        $SQL_orders = "DROP TABLE orders";
        $db->pdo->exec($SQL_orders);


        $SQL_order_items = "DROP TABLE order_items";
        $db->pdo->exec($SQL_order_items);

    }
}