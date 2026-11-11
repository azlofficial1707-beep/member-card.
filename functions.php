<?php
require_once __DIR__ . '/config.php';

function e($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
function format_ml($value): string {
    $formatted = number_format((float)$value, 2, '.', '');
    return rtrim(rtrim($formatted, '0'), '.');
}
function require_login(): void {
    if (empty($_SESSION['user'])) {
        header('Location: login.php'); exit;
    }
}
function require_admin(): void {
    require_login();
    if ($_SESSION['user']['role'] !== 'admin') {
        http_response_code(403); exit('Akses ditolak.');
    }
}
function current_user(): array { return $_SESSION['user'] ?? []; }
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function verify_csrf(): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
        !hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(419); exit('Sesi formulir tidak valid. Muat ulang halaman.');
    }
}
function flash(string $message, string $type='success'): void {
    $_SESSION['flash'] = ['message'=>$message, 'type'=>$type];
}
function get_flash(): ?array {
    $f = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); return $f;
}
function member_progress(PDO $pdo, int $userId): array {
    $q = $pdo->prepare('SELECT COUNT(*) FROM purchases WHERE user_id=?');
    $q->execute([$userId]);
    $total = (int)$q->fetchColumn();
    // Setiap transaksi ke-5 adalah gratis. Siklus otomatis berulang.
    return ['total'=>$total, 'progress'=>$total % 5, 'next'=>5 - ($total % 5)];
}
function purchase_rows(PDO $pdo, ?int $userId=null): array {
    $sql = "SELECT p.*, u.full_name, u.username,
            ROW_NUMBER() OVER (PARTITION BY p.user_id ORDER BY p.purchase_date, p.id) AS cycle_order
            FROM purchases p JOIN users u ON u.id=p.user_id";
    if ($userId !== null) $sql .= " WHERE p.user_id = " . (int)$userId;
    $sql .= " ORDER BY p.purchase_date DESC, p.id DESC";
    $rows = $pdo->query($sql)->fetchAll();
    foreach ($rows as &$r) {
        // ROW_NUMBER is overall lifetime order; modulo 5 identifies free purchase.
        $r['is_free'] = ((int)$r['cycle_order'] % 5 === 0);
    }
    return $rows;
}
