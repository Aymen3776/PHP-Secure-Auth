<?php
const host = "localhost";
const database = "login_register";
const username = "root";
const password = "";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$dsn = "mysql:host=" . host . ";dbname=" . database . ";charset=utf8mb4";
try {
    $pdo = new PDO($dsn, username, password, $options);
} catch (PDOException $e) {
    die("Veritabanı bağlantısı kurulamadı: " . $e->getMessage());
}
