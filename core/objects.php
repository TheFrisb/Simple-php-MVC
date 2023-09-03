<?php

namespace Core;

abstract class objects extends BaseModel
{
    public int $id;
    abstract public static function getTableName(): string;
    abstract public function getFields(): array;


    public static function get($modelId){
        $tableName = static::getTableName();
        $statement = static::prepare("SELECT * FROM $tableName WHERE ID = :id");
        $statement->bindParam(':id', $modelId);
        $statement->execute();
        $statement->setFetchMode(\PDO::FETCH_CLASS, get_called_class());
        return $statement->fetch();
        
    }

    public static function getAsArray($modelId){
        $tableName = static::getTableName();
        $statement = static::prepare("SELECT * FROM $tableName WHERE ID = :id");
        $statement->bindParam(':id', $modelId);
        $statement->execute();
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        return $statement->fetch();

    }

    public function delete() : void{

        $tableName = $this->getTableName();
        $modelId = $this->id;
        $statement = $this->prepare("DELETE FROM $tableName WHERE ID = :id");
        $statement->bindParam(':id', $modelId);
        $statement->execute();

    }

    public static function count() {
        $tableName = static::getTableName();
        $statement = static::prepare("SELECT COUNT(*) FROM $tableName");
        $statement->execute();
        return $statement->fetchColumn();
    }
    public static function all(int $limit = null, int $offset = null): bool|array
    {
        $tableName = static::getTableName();
        $query = "SELECT * FROM " . $tableName;

        if($limit && $limit >= 0 && $offset && $offset >= 0){
            $query .= " LIMIT :limit OFFSET :offset";
            $statement = static::prepare($query);
            $statement->bindParam(':limit', $limit);
            $statement->bindParam(':offset', $offset);

        } else {
            $statement = static::prepare($query);
        }
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_CLASS, get_called_class());
    }



    public function save()
    {

        $tableName = $this->getTableName();
        $fields = $this->getFields();


        if(isset($this->id)){
            $params = array_map(fn($field) => "$field = :$field", $fields);
            $sql = "UPDATE $tableName SET " . implode(', ', $params) . " WHERE id = $this->id";
        } else {
            $params = array_map(fn($field) => ":$field", $fields);
            $sql = "INSERT INTO $tableName (". implode(',', $fields) .") VALUES (". implode(',', $params). ")";
        }

        $statement = self::prepare($sql);

        foreach ($fields as $field){
            $statement->bindValue(":$field", $this->{$field});
        }


        $statement->execute();
        if(!isset($this->id)){
            $this->id = Application::$app->db->pdo->lastInsertId();
        }
        return true;
    }



    public static function prepare($sql): bool|\PDOStatement
    {
        return Application::$app->db->pdo->prepare($sql);
    }
}