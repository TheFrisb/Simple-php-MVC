<?php

namespace Core;

abstract class objects extends BaseModel
{
    abstract public function getTableName(): string;
    abstract public function getFields(): array;
    public function save(){
        $tableName = $this->getTableName();
        $fields = $this->getFields();
        $params = array_map(fn($field) => ":$field", $fields);
        $statement = $this->prepare("INSERT INTO $tableName (". implode(',', $fields) .") 
            VALUES (". implode(',', $params). ")");

        foreach ($fields as $field){
            $statement->bindValue(":$field", $this->{$field});
        }
        return true;
    }

    public function prepare($sql){
        return Application::$app->db->pdo->prepare($sql);
    }
}