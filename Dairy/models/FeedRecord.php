<?php
class FeedRecord {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addFeedRecord(int $cowId, string $date, string $feedType, float $quantity, string $unit): bool {
        try {
            $sql = "INSERT INTO feed_records (cow_id, date, feed_type, quantity, unit) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);

            echo "addFeedRecord SQL: " . $sql . "<br>";
            echo "addFeedRecord Parameters: <pre>";
            print_r([$cowId, $date, $feedType, $quantity, $unit]);
            echo "</pre><br>";

            $result = $stmt->execute([$cowId, $date, $feedType, $quantity, $unit]);

            if ($result === false) {
                $errorInfo = $stmt->errorInfo();
                error_log("addFeedRecord PDO execute() failed: " . $errorInfo[2]);
                echo "addFeedRecord PDO execute() failed: " . $errorInfo[2] . "<br>";
                return false;
            }

            return true;
        } catch (PDOException $e) {
            error_log("addFeedRecord Database error (addFeedRecord): " . $e->getMessage());
            echo "addFeedRecord PDOException: " . $e->getMessage() . "<br>";
            return false;
        }
    }

    public function getFeedRecords(int $cowId = null, string $startDate = null, string $endDate = null): array {
        try {
            $sql = "SELECT id, cow_id, date, feed_type, quantity, unit FROM feed_records WHERE 1";
            $params = [];

            if ($cowId !== null) {
                $sql .= " AND cow_id = ?";
                $params[] = $cowId;
            }

            if ($startDate !== null && $endDate !== null && strtotime($startDate) > strtotime($endDate)) {
                error_log("Invalid date range in getFeedRecords.");
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
            $sql .= " ORDER BY date DESC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error (getFeedRecords): " . $e->getMessage());
            return [];
        }
    }
}
?>
