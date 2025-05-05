<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../functions.php';
requireLogin();
require_once '../../config/database.php';
require_once '../../models/HealthRecord.php';

$healthRecord = new HealthRecord($pdo);

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];

    try {
        $result = $healthRecord->deleteHealthRecord($id);
        if ($result === true) {
            echo "<script>alert('Health record deleted successfully!'); window.location.href = 'list.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error: Health record NOT deleted. Please check the database and error logs.'); window.location.href = 'list.php';</script>";
            exit();
        }
    } catch (PDOException $e) {
        echo "<script>alert('Database error: " . $e->getMessage() . ". Please check the database and error logs.'); window.location.href = 'list.php';</script>";
        exit();
    }  catch (Exception $e) {
        echo "<script>alert('General error: " . $e->getMessage() . ". Please check the database and error logs.'); window.location.href = 'list.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href = 'list.php';</script>";
    exit();
}
?>