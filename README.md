# Kartu Member Amanah Perfume

Aplikasi member digital berbasis PHP + MySQL untuk XAMPP.

## Fitur
- Login admin dan member dengan role.
- Admin membuat, mengedit, mencari, dan menghapus akun member.
- Admin dapat membuat transaksi dengan tanggal dan ukuran ML manual.
- Admin dapat mengubah/menghapus transaksi.
- Pembelian ke-5 dalam urutan tanggal/ID otomatis ditandai GRATIS.
- Member hanya dapat melihat kartu digital, progres, dan riwayat miliknya.
- Password menggunakan `password_hash()` dan `password_verify()`. Password asli tidak dapat dilihat siapa pun; admin dapat meresetnya.

## Instalasi di XAMPP
1. Install dan jalankan Apache serta MySQL dari XAMPP.
2. Salin folder `amanah_member_system` ke `C:\xampp\htdocs\`.
3. Buka `http://localhost/phpmyadmin`.
4. Pilih tab Import, lalu impor file `database.sql`.
5. Pastikan pengaturan database di `config.php` sesuai. Default XAMPP: user `root`, password kosong.
6. Buka `http://localhost/amanah_member_system/setup_admin.php`.
7. Buat akun admin pertama.
8. Setelah berhasil, hapus `setup_admin.php` dari folder proyek.
9. Login melalui `http://localhost/amanah_member_system/login.php`.

## Catatan penting tentang reward
Setiap member memiliki urutan transaksi sendiri. Transaksi ke-5, 10, 15, dan seterusnya ditandai GRATIS. Perhitungan memakai urutan tanggal pembelian lalu ID transaksi. Jika admin mengedit tanggal atau menghapus transaksi lama, urutan reward dapat berubah. Sistem ini mengasumsikan setiap transaksi yang dicatat adalah satu kunjungan/pembelian; nilai ML dicatat terpisah dan tidak menentukan jumlah poin.

## Hosting
GitHub hanya untuk menyimpan source code. Untuk menjalankan aplikasi, gunakan hosting yang mendukung PHP dan MySQL (misalnya shared hosting/cPanel atau VPS). Setelah upload ke hosting:
- Buat database MySQL dan user database.
- Import `database.sql`.
- Ubah kredensial di `config.php`.
- Gunakan HTTPS.
- Hapus `setup_admin.php` setelah membuat admin pertama.
- Jangan unggah password database atau file konfigurasi rahasia ke repositori publik. Sebelum publikasi, pindahkan kredensial ke environment variables atau file konfigurasi di luar web root.

## Catatan keamanan
- Gunakan password unik dan kuat untuk admin.
- Jangan membagikan akun admin kepada kasir/member.
- Lakukan backup database secara berkala melalui phpMyAdmin > Export.
- Untuk produksi, tambahkan pembatasan percobaan login, audit log, dan kebijakan backup terjadwal.
