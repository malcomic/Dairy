<?php
class Cow {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // ... (addCow, getCowById, updateCow, deleteCow methods remain the same)

    public function getCowsFiltered(string $search = null, string $breed = null, int $ageMin = null, int $ageMax = null): array {
        $sql = "SELECT * FROM cows WHERE 1";
        $params = [];

        if ($search !== null) {
            $sql .= " AND cow_id LIKE ?";
            $params[] = "%" . $search . "%";
        }

        if ($breed !== null) {
            $sql .= " AND breed = ?";
            $params[] = $breed;
        }

        if ($ageMin !== null) {
            $sql .= " AND age >= ?";
            $params[] = $ageMin;
        }

        if ($ageMax !== null) {
            $sql .= " AND age <= ?";
            $params[] = $ageMax;
        }

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error (getCowsFiltered): " . $e->getMessage());
            return [];
        }
    }
}
?>