<?php

namespace Framework;

use PDO;
use App\Config;

abstract class Model
{
    protected $table;

    public Function newDbCon($resultAsArray = false){
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

    public function getAll(): array{
        $db = $this->newDbCon();
        $stmt = $db->query("SELECT * FROM $this->table");
        return $stmt->fetchAll();
    }

    public function getFieldBy($requiredField, $searchField, $data){
        $db = $this->newDbCon();
        $sql = 'SELECT ' . $requiredField . ' FROM ' . $this->table . ' WHERE ' . $searchField . '=?';
        $stmt = $db->prepare($sql);
        $stmt->execute([$data]);
        return $stmt->fetch();
    }

    public function getRowByField($field, $data){
        $db = $this->newDbCon();
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . $field . '=?';
        $stmt = $db->prepare($sql);
        $stmt->execute([$data]);
        return $stmt->fetch();
    }

    public function getAllByField($field, $data){
        $db = $this->newDbCon();
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . $field . '=?';
        $stmt = $db->prepare($sql);
        $stmt->execute([$data]);

        return $stmt->fetchAll();
    }

    public function deleteById($column, $id){
        $db = $this->newDbCon();
        $stmt = $db->prepare("DELETE FROM $this->table WHERE $column=?");
        $stmt->execute([$id]);
    }

    public function modifyFieldById($field, $id){
        $db = $this->newDbCon();
        $stmt = $db->prepare('UPDATE ' . $this->table . ' SET ' . $field .'=?' . ' WHERE ' . $id . '=?');
        $stmt->execute([$field, $id]);
    }

    public function getAllOrderBY(string $way, $column){
        $db = $this->newDbCon();
        $stmt = $db->prepare("SELECT * FROM $this->table ORDER BY $column $way");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}