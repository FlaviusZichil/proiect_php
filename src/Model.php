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
        //by default the result from database will be an object but if specified it can be changed to an associative array / matrix
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
    // intermediate
    public function addMedalForUser($userId, $medalId){
        $db = $this->newDbCon();
        $stmt = $db->prepare("INSERT INTO user_medals(user_id, medal_id) VALUES(?, ?)");
        $stmt->execute([$userId, $medalId]);
    }
    // intermediate
    public function checkUserForMedal($userId, $medalId){
        $db = $this->newDbCon();
        $stmt = $db->prepare("SELECT * FROM user_medals WHERE user_id=? AND medal_id=?");
        $stmt->execute([$userId, $medalId]);

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

    public function updateUser(string $firstName, $secondName, $password){
        $db = $this->newDbCon();

        $email = $_SESSION["email"];

        $stmt = $db->prepare("UPDATE user SET first_name=?, second_name=?, password=? WHERE email=?");
        $stmt->execute([$firstName, $secondName, $password, $email]);
    }
    // good
    public function deleteById($id, $column){
        $db = $this->newDbCon();
        $stmt = $db->prepare("DELETE FROM $this->table WHERE $column=?");
        $stmt->execute([$id]);
    }
    // bad
    public function getUnfinishedTrips($status){
        $db = $this->newDbCon();
        $stmt = $db->prepare("SELECT * FROM $this->table WHERE status=?");
        $stmt->execute([$status]);

        return $stmt->fetchAll();
    }
    // bad
    public function getTripBy($tripId){
        $db = $this->newDbCon();
        $stmt = $db->prepare("SELECT * FROM $this->table WHERE trip_id=?");
        $stmt->execute([$tripId]);

        return $stmt->fetchAll();
    }
}