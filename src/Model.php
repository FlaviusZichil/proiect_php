<?php

namespace Framework;

use PDO;
use App\Config;

abstract class Model
{
    protected $table;
    // good
    public Function newDbCon($resultAsArray = false)
    {
        $dsn = Config::DB['driver'];
        $dsn .= ":host=".Config::DB['host'];
        $dsn .= ";dbname=".Config::DB['dbname'];
        $dsn .= ";port=".Config::DB['port'];
        $dsn .= ";charset=".Config::DB['charset'];

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        if ($resultAsArray)
        {
            $options[PDO::ATTR_DEFAULT_FETCH_MODE] = PDO::FETCH_ASSOC;
        }

        try {
            return new PDO($dsn, Config::DB['user'], Config::DB['pass'], $options);
        } catch (\PDOException $e)
        {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    // good
    public function getAll(): array{
        $db = $this->newDbCon();
        $stmt = $db->query("SELECT * FROM $this->table");
        return $stmt->fetchAll();
    }
    // good
    public function getById($id){
        $db = $this->newDbCon();
        $stmt = $db->prepare("SELECT * FROM $this->table WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    // good
    public function getFieldBy($requiredField, $searchField, $data){
        $db = $this->newDbCon();
        $sql = 'SELECT ' . $requiredField . ' FROM ' . $this->table . ' WHERE ' . $searchField . '=?';
        $stmt = $db->prepare($sql);
        $stmt->execute([$data]);

        return $stmt->fetch();
    }
    // good
    public function getAllByField($field, $data){
        $db = $this->newDbCon();
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . $field . '=?';
        $stmt = $db->prepare($sql);
        $stmt->execute([$data]);

        return $stmt->fetch();
    }
    // good
    protected function prepareDataForStmt(array $data): array{
        $columns = '';
        $values = [];

        for($i = 0; $i < count($data); $i++) {

             $values[]= $data[$i];
             $columns .= "key($data) = ? ";
             //if we are not at the last element with the iteration
             if(count($data) < ($i + 1))
             {
                 $columns .= "AND ";
             }
        }
        return [$columns, $values];
    }
    // good
    public Function find(array $data)
    {
        list($columns, $values) = $this->prepareDataForStmt($data);
        $db = $this->newDbCon();
        $stmt = $db->prepare("SELECT * FROM $this->table WHERE $columns");
        return $stmt->execute([$values]);
    }
    // good
    public function deleteById($id, $column){
        $db = $this->newDbCon();
        $stmt = $db->prepare("DELETE FROM $this->table WHERE $column=?");
        $stmt->execute([$id]);
    }

    public function modifyFieldById($field, $id){
        $db = $this->newDbCon();
        $stmt = $db->prepare('UPDATE ' . $this->table . ' SET ' . $field .'=?' . ' WHERE ' . $id . '=?');
        $stmt->execute([$field, $id]);
    }
}