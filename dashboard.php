<?php
require_once 'functions.php'; require_login();
$me=current_user(); $pageTitle='Dashboard'; $active='dashboard';
if($me['role']==='admin') {
    $members=(int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='member'")->fetchColumn();
    $admins=(int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetchColumn();
    $sales=(int)$pdo->query("SELECT COUNT(*) FROM purchases")->fetchColumn();
    $recent=$pdo->query("SELECT p.*,u.full_name FROM purchases p JOIN users u ON u.id=p.user_id ORDER BY p.purchase_date DESC,p.id DESC LIMIT 7")->fetchAll();
} else {
    $prog=member_progress($pdo,$me['id']);
    $st=$pdo->prepare("SELECT * FROM purchases WHERE user_id=? ORDER BY purchase_date DESC,id DESC LIMIT 7"); $st->execute([$me['id']]); $recent=$st->fetchAll();
}
require 'header.php';
?>
<?php if($me['role']==='admin'): ?>
<section class="welcome"><div><p class="eyebrow gold">ADMINISTRATION</p><h1>Selamat datang, <?=e($me['name'])?>.</h1><p>Kelola pelanggan dan program loyalitas Amanah Perfume.</p></div><div class="welcome-seal">AP</div></section>
<div class="stats"><article class="stat"><span>Total Member</span><strong><?=$members?></strong><small>Member terdaftar</small></article><article class="stat"><span>Total Admin</span><strong><?=$admins?></strong><small>Pengelola sistem</small></article><article class="stat"><span>Total Transaksi</span><strong><?=$sales?></strong><small>Seluruh pembelian tercatat</small></article></div>
<div class="section-head"><div><p class="eyebrow">AKTIVITAS</p><h3>Pembelian Terbaru</h3></div><a class="btn outline" href="purchases.php">Lihat semua</a></div>
<div class="table-wrap"><table><thead><tr><th>Member</th><th>Tanggal</th><th>Ukuran</th><th>Status</th></tr></thead><tbody><?php foreach($recent as $r): ?><tr><td><b><?=e($r['full_name'])?></b></td><td><?=e($r['purchase_date'])?></td><td><?=e(format_ml($r['ml']))?> ml</td><td><span class="pill">Tercatat</span></td></tr><?php endforeach; ?><?php if(!$recent): ?><tr><td colspan="4" class="empty">Belum ada transaksi.</td></tr><?php endif; ?></tbody></table></div>
<?php else: ?>
<?php $st=$pdo->prepare('SELECT * FROM users WHERE id=?');$st->execute([$me['id']]);$profile=$st->fetch(); ?>
<section class="member-card"><div class="member-card-top"><span>AMANAH PERFUME</span><span>✦ MEMBER CARD</span></div><div class="member-card-mid"><div><small>PEMILIK KARTU</small><h1><?=e($profile['full_name'])?></h1><small>MEMBER ID · AP<?=str_pad((string)$profile['id'],5,'0',STR_PAD_LEFT)?></small></div><div class="card-logo">AP</div></div><div class="member-card-bottom"><span>LOYALTY PROGRAM</span><span>EST. AMANAH</span></div></section>
<div class="stats"><article class="stat"><span>Total Pembelian</span><strong><?=$prog['total']?></strong><small>Transaksi tercatat</small></article><article class="stat"><span>Progres Reward</span><strong><?=$prog['progress']?>/5</strong><small><?=$prog['next']?> pembelian menuju gratis</small></article><article class="stat"><span>Hadiah Berikutnya</span><strong><?=($prog['next']===5?'5':'Ke-'.$prog['next'])?></strong><small>Pembelian ke-5 gratis</small></article></div>
<div class="progress-box"><div class="section-head"><div><p class="eyebrow">LOYALTY REWARD</p><h3>Perjalanan menuju hadiah</h3></div><b><?=$prog['progress']?> dari 5</b></div><div class="progress-track"><span style="width:<?=$prog['progress']*20?>%"></span></div><p class="muted small">Setiap pembelian ke-5 mendapatkan 1 transaksi gratis. Ukuran parfum dicatat sesuai transaksi.</p></div>
<div class="section-head"><div><p class="eyebrow">RIWAYAT</p><h3>Pembelian Terakhir</h3></div><a class="btn outline" href="my_purchases.php">Lihat semua</a></div><div class="table-wrap"><table><thead><tr><th>Tanggal</th><th>Ukuran</th><th>Keterangan</th></tr></thead><tbody><?php foreach($recent as $r): ?><tr><td><?=e($r['purchase_date'])?></td><td><?=e(format_ml($r['ml']))?> ml</td><td><?=e($r['notes']?:'Pembelian parfum')?></td></tr><?php endforeach; ?><?php if(!$recent): ?><tr><td colspan="3" class="empty">Belum ada pembelian.</td></tr><?php endif; ?></tbody></table></div>
<?php endif; ?>
<?php require 'footer.php'; ?>
