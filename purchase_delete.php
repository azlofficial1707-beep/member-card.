<?php
require_once 'functions.php';require_admin();verify_csrf();$id=(int)($_POST['id']??0);$pdo->prepare('DELETE FROM purchases WHERE id=?')->execute([$id]);flash('Transaksi dihapus. Perhitungan reward diperbarui otomatis.');header('Location: purchases.php');exit;
