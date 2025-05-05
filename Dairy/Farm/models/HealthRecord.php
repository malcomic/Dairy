<?php
class HealthRecord {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllHealthRecords(int $cowId = null, string $startDate = null, string $endDate = null): array {
        $sql = "SELECT id, cow_id, date, health_status, treatment, notes FROM health_records"; // No WHERE clause
        $params = [];

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error (getAllHealthRecords): " . $e->getMessage());
            return [];
        }
    }
}
?>