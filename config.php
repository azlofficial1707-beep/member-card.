<?php
declare(strict_types=1);
session_start();

$host = 'localhost';
$db   = 'amanah_member';
$user = 'root';
$pass = ''; // Default XAMPP biasanya kosong. Sesuaikan jika MySQL Anda memakai password.

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$db};charset=utf8mb4",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
} catch (PDOException $e) {
    http_response_code(500);
    exit('Koneksi database gagal. Periksa config.php dan pastikan MySQL XAMPP aktif.');
}
