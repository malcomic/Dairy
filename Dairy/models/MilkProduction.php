<?php
class MilkProduction {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getMilkRecords(int $cowId = null, string $startDate = null, string $endDate = null): array {
        $sql = "SELECT cow_id, date, milk_yield, notes FROM milk_production"; // No WHERE clause
        $params = [];

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error (getMilkRecords): " . $e->getMessage());
            return [];
        }
    }

    public function addMilkRecord(int $cowId, string $date, float $milkYield, string $notes = null): bool {
        $sql = "INSERT INTO milk_production (cow_id, date, milk_yield, notes) VALUES (?, ?, ?, ?)";
        $params = [$cowId, $date, $milkYield, $notes];

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return true; // Success
        } catch (PDOException $e) {
            error_log("Database error (addMilkRecord): " . $e->getMessage());
            return false; // Failure
        }
    }
}
?>