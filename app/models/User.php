<?php

class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findByUsername($username)
    {
        $sql = "
            SELECT *
            FROM users
            WHERE username = ?
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$username]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createMemberAccount(string $username, string $password): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO users (username, password_hash, role) VALUES (?, ?, 'member')"
        );
        $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT)]);
        return (int) $this->db->lastInsertId();
    }
}
