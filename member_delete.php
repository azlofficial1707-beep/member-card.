<?php
require_once 'functions.php';require_admin();verify_csrf();
$id=(int)($_POST['id']??0);$st=$pdo->prepare("DELETE FROM users WHERE id=? AND role='member'");$st->execute([$id]);flash('Member dan riwayat transaksinya telah dihapus.');header('Location: members.php');exit;
