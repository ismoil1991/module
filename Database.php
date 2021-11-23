<?php


class Database
{
    private static $instance = null;
    private $pdo, $query, $error = false, $results, $count;

    private function __construct()
    {
        try {
            $this->pdo = new PDO('mysql:host=localhost;dbname=components;charset=utf8', 'root', 'root');
        } catch (PDOException $exception) {
            die($exception->getMessage());
        }
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    public function query($sql, $params = [])
    {
        $this->error = false;
        $this->query = $this->pdo->prepare($sql);

        if (count($params)) {
            $i = 1;
            foreach ($params as $param) {
                $this->query->bindValue($i, $param);
                $i++;
            }
        }

        if (!$this->query->execute()) {
            $this->error = true;
        } else {
            $this->results = $this->query->fetchAll(PDO::FETCH_OBJ);
            $this->count = $this->query->rowCount();
        }
        return $this;
    }

    public function error()
    {
        return $this->error;
    }

    public function results()
    {
        return $this->results;
    }

    public function count()
    {
        return $this->count;
    }

    public function get($table, $where = [])
    {
        return $this->action('SELECT *', $table, $where);
    }

    public function delete($table, $where = [])
    {
        return $this->action('DELETE', $table, $where);
    }

    public function insert($table, $fields = [])
    {
        $values = '';
        foreach ($fields as $field) {
            $values .= "?,"; // ?,?,
        }

        $val = rtrim($values, ','); // ?,?
        // implode("`, `", array_keys($fields)); "title`, `description"
        // '`' . implode("`, `", array_keys($fields)) . '`'; `title`, `description`
        $sql = "INSERT INTO {$table} (" . '`' . implode("`, `", array_keys($fields)) . '`' . ") VALUES ({$val})";

        if (!$this->query($sql, $fields)->error()) {
            return true;
        }

        return false;
    }

    public function update($table, $id, $fields = [])
    {
        $set = '';
        foreach ($fields as $key => $field) {
            $set .= $key . "=?,"; // title = ?, description = ?,
        }

        $set = rtrim($set, ','); // title = ?, description = ?

        $sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";

        if (!$this->query($sql, $fields)->error()) {
            return true;
        }

        return false;
    }

    public function action($action, $table, $where = [])
    {
        if (count($where) === 3) {
            $operators = ['=', '<', '>', '<=', '>='];

            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];

            if (in_array($operator, $operators)) {
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
                if (!$this->query($sql, [$value])->error()) { // true if has error
                    return $this;
                }
            }
        }

        return false;
    }

    public function first()
    {
        return $this->results()[0];
    }
}