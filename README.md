# SIGAP — Instalasi

1. Buat project: `composer create-project laravel/laravel sigap && cd sigap`
2. Salin isi folder ini (app, database, resources, routes) ke project, timpa file yang sudah ada.
   (Laravel 10: hapus migration users bawaan `2014_10_12_000000_create_users_table.php`
   agar tidak bentrok dengan `0001_01_01_000000_create_users_table.php`.)
3. Atur `.env` (DB_*), lalu set zona waktu ke Asia/Jakarta.
4. Jalankan:
   php artisan migrate:fresh --seed
   php artisan storage:link
   php artisan serve

Akun testing (password: password123):
- admin@sigap.id
- warga@sigap.id
- petugas.drainase@sigap.id   (Drainase & Saluran Air)
- petugas.jalan@sigap.id      (Infrastruktur & Jalan)
- petugas.kebersihan@sigap.id (Kebersihan & Sampah)
- petugas.lampu@sigap.id      (Penerangan Jalan)

Alur: warga lapor (category_id) -> admin validasi (VALIDATED, masuk antrean kategori)
-> salah satu petugas kategori itu menekan "Ambil Tugas" (IN_PROGRESS, terkunci atas nama
petugas tsb) -> petugas yang sama menyelesaikan (COMPLETED) -> warga beri rating.
Petugas bisa "Lepas Tugas" untuk mengembalikan laporan ke antrean.

Admin dapat menambah, mengubah kategori/password, dan menonaktifkan akun petugas lewat menu "Kelola Petugas".
Setelah menimpa file, jalankan: php artisan migrate && php artisan optimize:clear
