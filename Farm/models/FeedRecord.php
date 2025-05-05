<?php
class FeedRecord {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addFeedRecord(int $cowId, string $date, string $feedType, float $quantity, string $unit): bool {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO feed_records (cow_id, date, feed_type, quantity, unit) VALUES (?, ?, ?, ?, ?)");
            return $stmt->execute([$cowId, $date, $feedType, $quantity, $unit]);
        } catch (PDOException $e) {
            error_log("Database error (addFeedRecord): " . $e->getMessage());
            return false;
        }
    }

    public function getAllFeedRecords(int $cowId = null, string $startDate = null, string $endDate = null): array {
        try {
            $sql = "SELECT * FROM feed_records WHERE 1";
            $params = [];

            if ($cowId !== null) {
                $sql .= " AND cow_id = ?";
                $params[] = $cowId;
            }

            if ($startDate !== null && $endDate !== null && strtotime($startDate) > strtotime($endDate)) {
                error_log("Invalid date range in getAllFeedRecords.");
                return [];
            }

            if ($startDate !== null) {
                $sql .= " AND date >= ?";
                $params[] = $startDate;
            }

            if ($endDate !== null) {
                $sql .= " AND date <= ?";
                $params[] = $endDate;
            }

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error (getAllFeedRecords): " . $e->getMessage());
            return [];
        }
    }
}
?>