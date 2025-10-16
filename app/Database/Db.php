<?php

namespace App\Database;

use mysqli;
use Exception;

class Db
{
    private $localhost;
    private $username;
    private $password;
    private $db;
    private $conn;
    public $db_name;

    public function __construct()
    {
        $this->localhost = "localhost";
        $this->username = "root";
        $this->password = "";
        $this->db = "enteryourdatabase";
        $this->db_name = $this->db;
    }

    public function connect()
    {
        $this->conn = new mysqli($this->localhost, $this->username, $this->password, $this->db);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
    public function insert_id()
    {
        return $this->conn->insert_id;
    }
    public function getConnection()
    {
        return $this->conn;
    }

    public function query($sql)
    {
        $result = $this->conn->query($sql);

        if ($this->conn->error) {
            die("Query failed: " . $this->conn->error);
        }

        return $result;
    }

    public function close()
    {
        if ($this->conn) {
            $this->conn->close();
        }
    }
    public function error()
    {
        return $this->conn->error;
    }
}
