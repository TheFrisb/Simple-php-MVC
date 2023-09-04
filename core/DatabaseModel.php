<?php

namespace Core;

/**
 * The DatabaseModel class,
 * responsible for laying out the way a model implementing database logic should behave.
 */
abstract class DatabaseModel extends BaseModel
{
    /**
     * The id as stored in the database
     * @var int
     */
    public int $id;

    /**
     * Return the tableName of the Model
     * @return string
     */
    abstract public static function getTableName(): string;

    /**
     * returns the fields of the model
     * @return array
     */
    abstract public function getFields(): array;


    /**
     * Fetches a single object from the database
     * @param $modelId | the id to be filtered for in the database
     * @param $asArray | default=false returns a Class, set it to true to return an associative Array
     * @return mixed
     */
    public static function get($modelId, $asArray = false){
        $tableName = static::getTableName();
        $statement = static::prepare("SELECT * FROM $tableName WHERE ID = :id");
        $statement->bindParam(':id', $modelId);
        $statement->execute();
        if($asArray){
            $statement->setFetchMode(\PDO::FETCH_ASSOC);
        }
        $statement->setFetchMode(\PDO::FETCH_CLASS, get_called_class());
        return $statement->fetch();
        
    }

    /**
     * Returns associative array
     * Could be also made to implement $asArray
     * @param $fieldName | The fieldname of the model to be filtered for
     * @param $fieldValue | The expected value of the fieldName
     * @return array|false
     */
    public static function filter($fieldName, $fieldValue){
        $tableName = static::getTableName();

        $statement = static::prepare("SELECT * FROM $tableName WHERE $fieldName = :$fieldName");
        $statement->bindParam(":$fieldName", $fieldValue);
        $statement->execute();
        $statement->setFetchMode(\PDO::FETCH_ASSOC);

        return $statement->fetchAll();
    }


    /**
     * Deletes the object from the database
     * @return void
     */
    public function delete() : void{

        $tableName = $this->getTableName();
        $modelId = $this->id;
        $statement = $this->prepare("DELETE FROM $tableName WHERE ID = :id");
        $statement->bindParam(':id', $modelId);
        $statement->execute();

    }

    /**
     * Counts the records in the database
     * @return mixed
     */
    public static function count() {
        $tableName = static::getTableName();
        $statement = static::prepare("SELECT COUNT(*) FROM $tableName");
        $statement->execute();
        return $statement->fetchColumn();
    }

    /**
     * Fetches all objects from the database
     * @param int|null $limit | number of objects to be paginated
     * @param int|null $offset | offset to start the query
     * @param bool $asArray | default=false returns Object, true returns associative array
     * @return bool|array
     */
    public static function all(int $limit = null, int $offset = null, bool $asArray = false): bool|array
    {
        $tableName = static::getTableName();
        $query = "SELECT * FROM " . $tableName;

        if($limit !== null && $limit >= 0 && $offset !== null && $offset >= 0){
            $query .= " LIMIT :limit OFFSET :offset";
            $statement = static::prepare($query);
            $statement->bindParam(':limit', $limit);
            $statement->bindParam(':offset', $offset);

        } else {
            $statement = static::prepare($query);
        }
        $statement->execute();
        if($asArray){
            return $statement->fetchAll(\PDO::FETCH_ASSOC);
        }
        return $statement->fetchAll(\PDO::FETCH_CLASS, get_called_class());
    }


    /**
     * Saves the object to the database,
     * sets the id to the instance if the object is new
     * @return true
     */
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


    /**
     * Prepares the sql statement, handy static method.
     * @param $sql
     * @return bool|\PDOStatement
     */
    public static function prepare($sql): bool|\PDOStatement
    {
        return Application::$app->db->pdo->prepare($sql);
    }
}