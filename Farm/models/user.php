<?php
class User {
    public static function authenticate(PDO $pdo, string $username, string $password) {
        try {
            $stmt = $pdo->prepare("SELECT id, username, email, password, role FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                return $user;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Authentication error: " . $e->getMessage());
            throw $e;
        }
    }

    public static function getUserById(PDO $pdo, int $userId) {
        try {
            $stmt = $pdo->prepare("SELECT id, username, email, role FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("getUserById error: " . $e->getMessage());
            throw $e;
        }
    }

    public static function getUsersByRole(PDO $pdo, array $roles) {
        try {
            $placeholders = implode(',', array_fill(0, count($roles), '?'));
            $sql = "SELECT id, username, email, role FROM users WHERE role IN (" . $placeholders . ")";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($roles);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("getUsersByRole error: " . $e->getMessage());
            throw $e;
        }
    }

    public static function createUser(PDO $pdo, string $username, string $email, string $password, string $role) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $email, $hashedPassword, $role]);
            return $pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("createUser error: " . $e->getMessage());
            throw $e;
        }
    }
}
?>