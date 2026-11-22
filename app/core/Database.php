<?php
class Database
{
    private static $instance = null;
    private $conn;

    private function __construct()
    {
        $this->conn = new PDO(
            "mysql:host=127.0.0.1;port=3305;dbname=tru_gaming;charset=utf8mb4",
            "root",
            ""
        );

        $this->conn->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );
    }

    public static function getInstance()
    {
        if(self::$instance === null)
        {
            self::$instance = new Database();
        }

        return self::$instance->conn;
    }
}