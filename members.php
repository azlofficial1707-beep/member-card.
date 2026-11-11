<?php
require_once 'functions.php'; require_admin();
$pageTitle='Data Member';$active='members';
$q=trim($_GET['q']??'');
$sql="SELECT u.*, (SELECT COUNT(*) FROM purchases p WHERE p.user_id=u.id) total FROM users u WHERE u.role='member'";
$args=[];
if($q!==''){ $sql.=" AND (u.full_name LIKE ? OR u.username LIKE ? OR u.phone LIKE ?)";$like="%$q%";$args=[$like,$like,$like]; }
$sql.=" ORDER BY u.created_at DESC";$st=$pdo->prepare($sql);$st->execute($args);$rows=$st->fetchAll();
require 'header.php';
?>
<div class="section-head"><div><p class="eyebrow">DATABASE PELANGGAN</p><h3>Semua Member</h3><p class="muted small">Cari pelanggan saat mereka lupa membawa kartu fisik.</p></div><a class="btn primary" href="member_form.php">＋ Tambah Member</a></div>
<form class="searchbar" method="get"><input name="q" value="<?=e($q)?>" placeholder="Cari nama, username, atau nomor WhatsApp..."><button class="btn dark">Cari Member</button><?php if($q!==''): ?><a class="btn outline" href="members.php">Reset</a><?php endif; ?></form>
<div class="table-wrap"><table><thead><tr><th>Nama Member</th><th>Username</th><th>WhatsApp</th><th>Pembelian</th><th>Aksi</th></tr></thead><tbody><?php foreach($rows as $r): ?><tr><td><b><?=e($r['full_name'])?></b><small class="subline">ID AP<?=str_pad((string)$r['id'],5,'0',STR_PAD_LEFT)?></small></td><td><?=e($r['username'])?></td><td><?=e($r['phone']?:'—')?></td><td><?=e($r['total'])?> kali</td><td class="actions"><a href="member_form.php?id=<?=$r['id']?>">Edit</a><a href="purchase_form.php?user_id=<?=$r['id']?>">＋ Transaksi</a><a href="password.php?user_id=<?=$r['id']?>">Reset password</a><form method="post" action="member_delete.php" onsubmit="return confirm('Hapus member dan seluruh riwayat pembeliannya?')"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><input type="hidden" name="id" value="<?=$r['id']?>"><button class="link-danger">Hapus</button></form></td></tr><?php endforeach; ?><?php if(!$rows): ?><tr><td colspan="5" class="empty">Member tidak ditemukan.</td></tr><?php endif; ?></tbody></table></div>
<?php require 'footer.php'; ?>
