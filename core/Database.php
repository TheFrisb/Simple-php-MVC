<?php

namespace Core;

class Database
{
    public \PDO $pdo;
    public function __construct()
    {
        $this->pdo = new \PDO('sqlite:' . $_ENV['DATABASE_PATH']);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }


    public function migrate(): void
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();

        $files = scandir(Application::$ROOT_DIR . '/migrations');
        $toApplyMigrations = array_diff($files, $appliedMigrations);

        foreach ($toApplyMigrations as $migration) {
            if($migration === '.' || $migration === '..'){
                continue;
            }

            require_once Application::$ROOT_DIR . '/migrations/' . $migration;

            $className = pathinfo($migration, PATHINFO_FILENAME);

            echo "Making migration for $migration" . PHP_EOL; // \n not working?
            $instance = new $className;
            $instance->create();
            echo "Applied migrations for $migration". PHP_EOL;
            $newMigrations[] = $migration;

        }

        if(!empty($newMigrations)) {
            $this->saveMigrations($newMigrations);
        } else {
            echo 'All migrations are applied!';
        }
    }

    public function createMigrationsTable(): void
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(100),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );");
    }

    public function getAppliedMigrations(): bool|array
    {
        $statement = $this->pdo->prepare("SELECT migration FROM migrations");

        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    private function saveMigrations(array $newMigrations)
    {
        $numberOfMigrations = sizeof($newMigrations);
        $sqlMigrationValues = "";
        for($i = 0; $i < $numberOfMigrations; $i++){
            if ($i+1 === $numberOfMigrations){
                $sqlMigrationValues .= "('$newMigrations[$i]')";
            } else {
                $sqlMigrationValues .= "('$newMigrations[$i]'), ";
            }

        }



        $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES ($sqlMigrationValues)");
        $statement->execute();

    }


}