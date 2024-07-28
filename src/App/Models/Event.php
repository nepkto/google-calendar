<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Event
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($userId, $googleEventId, $summary, $description, $startDateTime, $endDateTime)
    {
        $sql = "INSERT INTO events (user_id, google_event_id, summary, description, start_datetime, end_datetime) 
                VALUES (:user_id, :google_event_id, :summary, :description, :start_datetime, :end_datetime)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':user_id' => $userId,
            ':google_event_id' => $googleEventId,
            ':summary' => $summary,
            ':description' => $description,
            ':start_datetime' => $startDateTime,
            ':end_datetime' => $endDateTime
        ]);

        return $this->db->lastInsertId();
    }

    public function getByUserId($userId)
    {
        $sql = "SELECT * FROM events WHERE user_id = :user_id ORDER BY start_datetime";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function delete($id, $userId)
    {
        $sql = "DELETE FROM events WHERE id = :id AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':user_id' => $userId]);
    }
}