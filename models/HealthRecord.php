<?php
class HealthRecord {
    private $pdo;
    private $tableName = 'health_records'; // Consistent table name

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getHealthRecords(int $cowId = null, string $startDate = null, string $endDate = null): array {
        try {
            $sql = "SELECT id, cow_id, date, diagnosis, treatment, veterinarian FROM " . $this->tableName; // Use $this->tableName
            $params = [];
            $whereClauses = [];

            if ($cowId !== null) {
                $whereClauses[] = "cow_id = ?";
                $params[] = $cowId;
            }
            if ($startDate !== null) {
                $whereClauses[] = "date >= ?";
                $params[] = $startDate;
            }
            if ($endDate !== null) {
                $whereClauses[] = "date <= ?";
                $params[] = $endDate;
            }

            if (!empty($whereClauses)) {
                $sql .= " WHERE " . implode(" AND ", $whereClauses);
            }

            $sql .= " ORDER BY date DESC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error (getHealthRecords): " . $e->getMessage());
            return [];
        }
    }

    public function addHealthRecord(int $cowId, string $date, string $diagnosis, string $treatment, string $veterinarian): bool {
        try {
            $sql = "INSERT INTO " . $this->tableName . " (cow_id, date, diagnosis, treatment, veterinarian) VALUES (?, ?, ?, ?, ?)";  // Use $this->tableName
            $params = [$cowId, $date, $diagnosis, $treatment, $veterinarian];
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($params);
            if ($result === false) {
                $errorInfo = $stmt->errorInfo();
                error_log("PDO execute() failed: " . $errorInfo[2]);
                return false;
            }
            return true;
        } catch (PDOException $e) {
            error_log("Database error (addHealthRecord): " . $e->getMessage());
            return false;
        }
    }

    public function updateHealthRecord(int $id, int $cowId, string $date, string $diagnosis, string $treatment, string $veterinarian): bool {
        try {
            $sql = "UPDATE " . $this->tableName . " SET cow_id = ?, date = ?, diagnosis = ?, treatment = ?, veterinarian = ? WHERE id = ?";  // Use $this->tableName
            $params = [$cowId, $date, $diagnosis, $treatment, $veterinarian, $id];
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return true;
        } catch (PDOException $e) {
            error_log("Database error (updateHealthRecord): " . $e->getMessage());
            return false;
        }
    }

    public function deleteHealthRecord(int $id): bool {
        try {
            $sql = "DELETE FROM " . $this->tableName . " WHERE id = ?";  // Use $this->tableName
            $params = [$id];
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return true;
        } catch (PDOException $e) {
            error_log("Database error (deleteHealthRecord): " . $e->getMessage());
            return false;
        }
    }
}
?>