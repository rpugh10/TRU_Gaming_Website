<?php

class Member{
    private $db;

    public function __construct(){
        $this->db = Database::getInstance();
    }

    public function all() : array{
        $sql = "
            SELECT *
            FROM members
            ORDER BY real_name ASC ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id) : ?array{
        $sql = "
            SELECT *
            FROM members
            WHERE member_id = ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(array $data): bool{
        $sql = "
            INSERT INTO members (
                real_name, gamertag, email, favorite_game,
                gaming_platform, join_date, program, user_id
            )
            VALUES (
                :real_name, :gamertag, :email, :favorite_game,
                :gaming_platform, CURRENT_TIMESTAMP, :program, :user_id
            )
        ";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':real_name' => $data['real_name'],
            ':gamertag' => $data['gamertag'],
            ':email' => $data['email'],
            ':favorite_game' => $data['favorite_game'],
            ':gaming_platform' => $data['gaming_platform'],
            ':program' => $data['program'],
            ':user_id' => $data['user_id']
        ]);
    }

    public function update(int $id, array $data): bool{
        $sql = "
            UPDATE members
            SET real_name = :real_name,
                gamertag = :gamertag,
                email = :email,
                favorite_game = :favorite_game,
                gaming_platform = :gaming_platform,
                program = :program
            WHERE member_id = :member_id
        ";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':real_name' => $data['real_name'],
            ':gamertag' => $data['gamertag'],
            ':email' => $data['email'],
            ':favorite_game' => $data['favorite_game'],
            ':gaming_platform' => $data['gaming_platform'],
            ':program' => $data['program'],
            ':member_id' => $id
        ]);
    }

    public function delete(int $id): bool{
        $sql = "
            DELETE FROM members
            WHERE member_id = ?
        ";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
