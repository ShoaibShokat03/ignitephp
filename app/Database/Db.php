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
        $this->localhost = $_ENV['DATABASE_SERVER_NAME'] ?? "localhost";
        $this->username = $_ENV['DATABASE_USERNAME'] ?? "root";
        $this->password = $_ENV['DATABASE_PASSWORD'] ?? $_ENV['DB_PASSWORD'] ?? "";
        $this->db = $_ENV['DATABASE_NAME'] ?? "test_db";
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

    public function escape($string): string
    {
        if ($string === null) {
            return '';
        }
        // Convert to string if not already
        if (!is_string($string)) {
            $string = (string)$string;
        }
        return $this->conn->real_escape_string($string);
    }
}
