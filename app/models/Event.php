<?php

class Event{
    private $db;

    public function __construct(){
        $this->db = Database::getInstance();
    }

    public function createEvent(array $data): bool{
        $sql = "
            INSERT INTO events (
                event_name, event_date, location,
                game_title, max_players
            )
            VALUES (
                :event_name, :event_date, :location,
                :game_title, :max_players
            )
        ";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':event_name' => $data['event_name'],
            ':event_date' => $data['event_date'],
            ':location' => $data['location'],
            ':game_title' => $data['game_title'],
            ':max_players' => $data['max_players']
        ]);
    }

    public function getAllEvents() : array{
        $sql = "
            SELECT *
            FROM events
            ORDER BY event_date ASC ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateEvent(int $id, array $data): bool{
        $sql = "
            UPDATE events
            SET event_name = :event_name,
                event_date = :event_date,
                location = :location,
                game_title = :game_title,
                max_players = :max_players
            WHERE event_id = :event_id
        ";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':event_name' => $data['event_name'],
            ':event_date' => $data['event_date'],
            ':location' => $data['location'],
            ':game_title' => $data['game_title'],
            ':max_players' => $data['max_players'],
            ':event_id' => $id
        ]);
    }

    public function getEventById(int $id) : ?array{
        $sql = "
            SELECT *
            FROM events
            WHERE event_id = ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function deleteEvent(int $id): bool{
        $sql = "
            DELETE FROM events
            WHERE event_id = ?
        ";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}