<?php
require_once 'functions.php';
if (!empty($_SESSION['user'])) { header('Location: dashboard.php'); exit; }
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    verify_csrf();
    $st=$pdo->prepare('SELECT * FROM users WHERE username=? LIMIT 1');
    $st->execute([trim($_POST['username']??'')]); $u=$st->fetch();
    if ($u && password_verify($_POST['password']??'', $u['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user']=['id'=>(int)$u['id'],'name'=>$u['full_name'],'username'=>$u['username'],'role'=>$u['role']];
        header('Location: dashboard.php'); exit;
    }
    $error='Username atau password tidak sesuai.';
}
?><!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Login | Amanah Perfume</title><link rel="stylesheet" href="style.css"></head><body class="login-bg"><main class="login-card"><div class="brand-mark">AP</div><p class="eyebrow">AMANAH PERFUME</p><h1>Kartu Member</h1><p class="muted">Masuk untuk mengakses akun member Anda.</p><?php if($error): ?><div class="alert error"><?=e($error)?></div><?php endif; ?><form method="post"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><label>Username</label><input name="username" autocomplete="username" required autofocus><label>Password</label><input type="password" name="password" autocomplete="current-password" required><button class="btn primary full">Masuk</button></form><p class="muted small">Sistem Member Digital · Amanah Perfume</p></main></body></html>
