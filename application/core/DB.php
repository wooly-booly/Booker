<?php

class DB
{
    private $mysqli_conn = false;
    private static $_instance;

    private function __clone() {}
    private function __construct() {}

    public static function instance()
    {
        if (empty(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function connect($host, $user, $pass, $dbName)
    {
        if (!$this->mysqli_conn) {
            $this->mysqli_conn = new mysqli($host, $user, $pass, $dbName);

            if ($this->mysqli_conn->connect_errno) {
                throw new Exception($this->mysqli_conn->connect_error,
                                    $this->mysqli_conn->connect_errno);
            }
        } else {
            return true;
        }
    }

    public function select($table, $rows = '*', $join = null, $where = null,
                           $order = null, $limit = null)
    {
        $result = array();

        $q = 'SELECT '.$rows.' FROM '.$table;

        if ($join != null) {
            $q .= ' JOIN '.$join;
        }

        if ($where != null) {
            $q .= ' WHERE '.$where;
        }

        if ($order != null) {
            $q .= ' ORDER BY '.$order;
        }

        if ($limit != null) {
            $q .= ' LIMIT '.$limit;
        }

        $resultQuery = $this->mysqli_conn->query($q);

        $this->errorTest();

        if ($resultQuery->num_rows) {
            while ($obj = $resultQuery->fetch_object()) {
                $result[] = $obj;
            }
        }

        $resultQuery->close();

        return $result;
    }

    public function insert($table, $params = array())
    {
        $result = false;

        $q = 'INSERT INTO `'.$table.'` (`'.implode('`, `', array_keys($params))
            .'`) VALUES ("'.implode('", "', $params).'")';

        $this->mysqli_conn->query($q);
        $this->errorTest();

        $result = $this->mysqli_conn->insert_id;

        return $result;
    }

    public function delete($table, $where)
    {
        $result = false;
        $q = 'DELETE FROM '.$table.' WHERE '.$where;
        $result = $this->mysqli_conn->query($q);

        $this->errorTest();

        return $result;
    }

    public function update($table, $params = array(), $where, $limit = null)
    {
        $result = false;
        $args = array();

        foreach ($params as $field => $value) {
            $args[] = $field.'="'.$value.'"';
        }

        $q = 'UPDATE '.$table.' SET '.implode(',', $args).' WHERE '.$where;

        if ($limit != null) {
            $q .= ' LIMIT '.$limit;
        }

        $result = $this->mysqli_conn->query($q);

        $this->errorTest();

        return $result;
    }

    public function sql($q, $type = '')
    {
        $result = array();
        $resultQuery = $this->mysqli_conn->query($q);
        $this->errorTest();

        if ($type == 'select') {
            if ($resultQuery->num_rows) {
                while ($obj = $resultQuery->fetch_object()) {
                    $result[] = $obj;
                }
            }

            $resultQuery->close();

            return $result;
        }

        return $resultQuery;
    }

    public function escape($q)
    {
        if (is_array($q)) {
            foreach ($q as $key => $val) {
                $q[$key] = $this->mysqli_conn->real_escape_string($val);
            }

            return $q;
        }

        return $this->mysqli_conn->real_escape_string($q);
    }

    private function errorTest()
    {
        if ($this->mysqli_conn->errno) {
            throw new Exception($this->mysqli_conn->error,
                                $this->mysqli_conn->errno);
        }
    }
}
