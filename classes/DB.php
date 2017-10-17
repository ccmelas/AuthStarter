<?php
/**
 * Created by PhpStorm.
 * User: melas
 * Date: 10/15/17
 * Time: 11:34 AM
 */

class DB
{
    private static $instance = null;
    private $pdo, $query, $results, $error = false, $count = 0;

    /**
     * DB constructor.
     */
    public function __construct()
    {
        $dsn = 'mysql:host='. Config::get('mysql/host') .';dbname=' .
            Config::get('mysql/db');
        $username = Config::get('mysql/username');
        $password = Config::get('mysql/password');
        try {
            $this->pdo = new PDO($dsn,$username,$password);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new DB();
        }
        return self::$instance;
    }

    public function query($sql, $params = array())
    {
        $this->error = false;
        if($this->query = $this->pdo->prepare($sql)) {
            $x = 1;
            foreach ($params as $param) {
                $this->query->bindValue($x, $param);
                $x++;
            }
            if ($this->query->execute()) {
                $this->results = $this->query->fetchAll(PDO::FETCH_OBJ);
                $this->count = $this->query->rowCount();
            } else {
                $this->error = true;
            }
        } else {
            $this->error = true;
        }
        return $this;
    }

    private function action($action, $table, $where = array())
    {
        if (count($where) === 3) {
            $operators = array('=', '>', '<', '>=', '<=');

            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];

            if (in_array($operator, $operators)) {
                $sql ="{$action} FROM {$table} WHERE {$field} {$operator} ?";
                if (!$this->query($sql, array($value))->error()) {
                    return $this;
                }
            }
        }
        return false;
    }

    public function insert($table, $fields = array())
    {
        $keys = array_keys($fields);
        $values = null;
        $x = 1;
        foreach ($fields as $field) {
            $values .= "?";
            if (($x) < count($fields)) {
                $values .= ', ';
            }
            $x++;
        }

        $sql = "INSERT INTO {$table} (". implode(', ', $keys) .") VALUES ({$values})";

        if (!$this->query($sql, $fields)->error()) {
            return true;
        }
        return false;
    }

    public function get($table, $where)
    {
        return $this->action('SELECT *', $table, $where);
    }

    public function update($table, $id, $fields)
    {
        $set = '';
        $x = 1;

        foreach ($fields as $name => $value) {
            $set .= "{$name} = ?";
            if($x < count($fields)) {
                $set .= ", ";
            }
            $x++;
        }

        $sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
        if (!$this->query($sql, $fields)->error()) {
            return true;
        }
        return false;
    }

    public function delete($table, $where)
    {
        return $this->action('DELETE', $table, $where);
    }

    public function results()
    {
        return $this->results;
    }

    public function first()
    {
        return $this->results()[0];
    }

    public function error()
    {
        return $this->error;
    }

    public function count()
    {
        return $this->count;
    }

}