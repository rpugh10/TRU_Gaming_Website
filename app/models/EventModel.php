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
        $stmt->execute([$id]);
        return $stmt->rowCount() === 1;
    }

    public function register(int $userId, int $eventId): string
    {
        $this->db->beginTransaction();

        try {
            // Lock the event before reading registrations to serialize requests.
            $stmt = $this->db->prepare('SELECT max_players FROM events WHERE event_id = ? FOR UPDATE');
            $stmt->execute([$eventId]);
            $limit = $stmt->fetchColumn();
            if ($limit === false) {
                return 'missing_event';
            }

            $stmt = $this->db->prepare('SELECT member_id FROM members WHERE user_id = ? LIMIT 2 FOR UPDATE');
            $stmt->execute([$userId]);
            $memberIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
            if ($memberIds === []) {
                return 'missing_profile';
            }
            if (count($memberIds) > 1) {
                return 'multiple_profiles';
            }

            $memberId = $memberIds[0];
            $stmt = $this->db->prepare(
                'SELECT COUNT(*) AS total, COUNT(CASE WHEN member_id = ? THEN 1 END) AS already_registered
                 FROM registrations WHERE event_id = ?'
            );
            $stmt->execute([$memberId, $eventId]);
            $counts = $stmt->fetch(PDO::FETCH_ASSOC);
            if ((int) $counts['already_registered'] > 0) {
                return 'duplicate';
            }
            if ((int) $counts['total'] >= (int) $limit) {
                return 'full';
            }

            $stmt = $this->db->prepare('INSERT INTO registrations (member_id, event_id) VALUES (?, ?)');
            $stmt->execute([$memberId, $eventId]);
            $this->db->commit();
            return 'registered';
        } catch (PDOException $exception) {
            if ((int) ($exception->errorInfo[1] ?? 0) === 1062) {
                return 'duplicate';
            }
            throw $exception;
        } finally {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
        }
    }
}
