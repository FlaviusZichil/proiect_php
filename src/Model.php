<?php

namespace Framework;

use PDO;
use App\Config;

abstract class Model
{
    protected $table;

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

    /**
     *Return all data from table
     */
    public function getAll(): array
    {
        $db = $this->newDbCon();
        $stmt = $db->query("SELECT * FROM $this->table");

        return $stmt->fetchAll();
    }

    /**
     *Return data with specified id/index
     */
    public function getById($id)
    {
        $db = $this->newDbCon();
        $stmt = $db->prepare("SELECT * FROM $this->table WHERE id=?");
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function getByEmail(string $email)
    {
        $db = $this->newDbCon();
        $stmt = $db->prepare("SELECT email FROM $this->table WHERE email=?");
        $stmt->execute([$email]);

        return $stmt->fetch();
    }

    public function getAllDataAboutUserByEmail(string $email){
        $db = $this->newDbCon();
        $stmt = $db->prepare("SELECT * FROM $this->table WHERE email=?");
        $stmt->execute([$email]);

        return $stmt->fetch();
    }

    /**
     * this function will prepare data to be used in sql statement
     * 1. Will extract values from $data
     * 2. Will create the prepared sql string with columns from $data
     */
    protected function prepareDataForStmt(array $data): array
    {
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

    /**
     *Find data with values
     */
    public Function find(array $data)
    {
        list($columns, $values) = $this->prepareDataForStmt($data);
        $db = $this->newDbCon();
        $stmt = $db->prepare("SELECT * FROM $this->table WHERE $columns");
        return $stmt->execute([$values]);
    }

    /**
     *Insert new data in table
     */
    public function new(array $data)
    {
    }

    /**
     *Update data in table
     */
    public function updateUser(string $firstName, $secondName, $password){
        $db = $this->newDbCon();

        $email = $_SESSION["email"];

        $stmt = $db->prepare("UPDATE $this->table SET first_name=?, second_name=?, password=? WHERE email=?");
        $stmt->execute([$firstName, $secondName, $password, $email]);
    }

    /**
     *delete data from table
     */
    public function delete($id)
    {
    }
}