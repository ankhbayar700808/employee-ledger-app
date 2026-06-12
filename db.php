<?php
$host = 'db'; // Docker-compose дээрх сервисийн нэр
$db_name = 'ledger_db';
$db_user = 'root';
$db_pass = 'root_password_123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Холболт амжилтгүй: " . $e->getMessage());
}
?>
