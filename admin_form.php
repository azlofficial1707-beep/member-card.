<?php
require_once 'functions.php';require_admin();$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){verify_csrf();$name=trim($_POST['full_name']??'');$username=trim($_POST['username']??'');$password=$_POST['password']??'';
 if($name===''||$username===''||strlen($password)<8)$error='Nama, username, dan password minimal 8 karakter wajib diisi.';
 else{try{$pdo->prepare("INSERT INTO users(full_name,username,password_hash,role) VALUES(?,?,?,'admin')")->execute([$name,$username,password_hash($password,PASSWORD_DEFAULT)]);flash('Akun admin baru berhasil dibuat.');header('Location: dashboard.php');exit;}catch(PDOException $e){$error='Username sudah digunakan. Pilih username lain.';}}
}
$pageTitle='Tambah Admin';$active='admins';require 'header.php';
?>
<div class="form-card"><p class="eyebrow">ADMINISTRATOR ACCESS</p><h3>Buat Akun Admin Baru</h3><p class="muted small">Admin baru memiliki hak akses penuh untuk mengelola member dan transaksi.</p><?php if($error): ?><div class="alert error"><?=e($error)?></div><?php endif; ?><form method="post"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><label>Nama lengkap *</label><input name="full_name" required><label>Username *</label><input name="username" required><label>Password awal *</label><input type="password" name="password" minlength="8" required><p class="muted small">Gunakan password kuat dan berikan akun hanya kepada pengelola tepercaya.</p><div class="form-actions"><a class="btn outline" href="dashboard.php">Batal</a><button class="btn primary">Buat Admin</button></div></form></div>
<?php require 'footer.php'; ?>
