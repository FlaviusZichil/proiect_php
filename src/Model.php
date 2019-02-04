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

    protected function prepareDataForSearchStmt(array $data, bool $like): array
    {
        $columns = '';
        $values = [];
        $i = 1;
        $searchStr = "=";
        if ($like) {
            $searchStr = " LIKE ";
        }
        foreach($data as $key => $value) {
            $values[]= $value;
            $columns .= $key . $searchStr . "?";
            //if we are not at the last element with the iteration
            if($i < (count($data))) {
                $columns .= " AND ";
            }
            $i++;
        }
        return [$columns, $values];
    }

    private function prepareStmt(array $data): array{
        $i = 1;
        $columns = '';
        $values = [];
        foreach ($data as $key => $value) {
            $values[] = $value;
            $columns .= $key .'=?';
            if($i < (count($data))) {
                $columns .= ", ";
            }
            $i++;
        }
        return [$columns, $values];
    }

    public function findOne(array $data, bool $like = false){
        list($columns, $values) = $this->prepareDataForSearchStmt($data, $like);
        $db = $this->newDbCon();
        $stmt = $db->prepare("SELECT * from $this->table WHERE $columns");
        $stmt->execute($values);
        return $stmt->fetch();
    }

    public function findAll(array $data, bool $like = false){
        list($columns, $values) = $this->prepareDataForSearchStmt($data, $like);
        $db = $this->newDbCon();
        $stmt = $db->prepare("SELECT * from $this->table WHERE $columns");
        $stmt->execute($values);
        return $stmt->fetchAll();
    }

    public function new(array $data): int{
        list($columns, $values) = $this->prepareStmt($data);
        $db = $this->newDbCon();
        $stmt = $db->prepare('INSERT INTO ' . $this->table . ' SET ' . $columns);
        $stmt->execute($values);
        return $db->lastInsertId();
    }

    public function update(array $where, array $data): bool
    {
        list($columns, $values) = $this->prepareStmt($data);
        $values[] = reset($where);
        $db = $this->newDbCon();
        $stmt = $db->prepare('UPDATE ' . $this->table . ' SET ' . $columns . ' WHERE ' . key($where) . '=?');
        return $stmt->execute($values);
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

    public function getAllOrderBY(string $way, $column){
        $db = $this->newDbCon();
        $stmt = $db->prepare("SELECT * FROM $this->table ORDER BY $column $way");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function deleteById($column, $id){
        $db = $this->newDbCon();
        $stmt = $db->prepare("DELETE FROM $this->table WHERE $column=?");
        $stmt->execute([$id]);
    }
}