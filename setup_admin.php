<?php
require_once 'functions.php';
$count = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetchColumn();
if ($count > 0) { exit('Admin sudah tersedia. Hapus file setup_admin.php demi keamanan. <a href="login.php">Login</a>'); }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim($_POST['full_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($name === '' || $username === '' || strlen($password) < 8) {
        $error = 'Lengkapi nama, username, dan password minimal 8 karakter.';
    } else {
        $st = $pdo->prepare("INSERT INTO users(full_name,username,password_hash,role) VALUES(?,?,?,'admin')");
        $st->execute([$name,$username,password_hash($password,PASSWORD_DEFAULT)]);
        header('Location: login.php'); exit;
    }
}
?><!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Buat Admin | Amanah Perfume</title><link rel="stylesheet" href="style.css"></head><body class="login-bg"><main class="login-card"><div class="brand-mark">AP</div><p class="eyebrow">AMANAH PERFUME</p><h1>Buat Admin Pertama</h1><?php if($error): ?><div class="alert error"><?=e($error)?></div><?php endif; ?><form method="post"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><label>Nama lengkap</label><input name="full_name" required><label>Username</label><input name="username" required><label>Password (min. 8 karakter)</label><input type="password" name="password" minlength="8" required><button class="btn primary full">Buat akun admin</button></form><p class="muted small">Setelah berhasil, hapus file setup_admin.php dari folder proyek.</p></main></body></html>
