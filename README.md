# Permit to Work (PTW) Management System 🏭👷‍♂️

> **Status:** Minimum Viable Product (MVP) - Proyek sistem informasi manajemen keselamatan kerja industri.

Permit to Work (PTW) System adalah aplikasi web berbasis **Laravel** yang dirancang untuk mendigitalkan proses pengajuan, evaluasi, dan persetujuan Izin Kerja Aman di lingkungan industri. Sistem ini menggantikan formulir kertas tradisional dengan alur kerja digital yang terintegrasi dan aman, memastikan semua standar *Health, Safety, and Environment* (HSE) terpenuhi sebelum pekerjaan berisiko tinggi dimulai.

## ✨ Fitur Utama & Role-Based Access (RBAC)
Sistem ini membagi akses pengguna ke dalam 4 peran utama dengan hak istimewa masing-masing:

1. **👷 Pekerja (Worker):**
   - Mengajukan formulir *Permit to Work* baru.
   - Mengunggah dokumen persyaratan keselamatan.
   - Melacak status persetujuan izin secara *real-time*.

2. **👨‍💼 Supervisor:**
   - Meninjau dan memvalidasi pengajuan izin kerja dari pekerja.
   - Meneruskan izin yang disetujui ke tahapan *Safety Officer*.

3. **🛡️ Safety Officer (HSE):**
   - Melakukan evaluasi keselamatan akhir pada dokumen *permit*.
   - Memonitoring seluruh izin kerja yang sedang aktif di lapangan.
   - Memberikan persetujuan akhir atau penolakan dengan catatan.

4. **💻 Administrator:**
   - Mengelola data master (Manajemen Pengguna & Akun).
   - Melihat laporan (*Reports*) dan riwayat aktivitas (*Activity Logs*).
   - Mengatur departemen dan struktur organisasi.

## 🛠️ Tech Stack Architecture
- **Backend Framework:** Laravel (PHP)
- **Database:** MySQL / MariaDB (Terintegrasi dengan Eloquent ORM)
- **Frontend / Views:** Laravel Blade Templating Engine
- **Styling:** CSS / Framework bawaan (Vite terintegrasi)
- **Authentication:** Custom Laravel Auth Middleware dengan validasi *Role*

## 🚀 Cara Instalasi & Menjalankan Proyek Lokal

Ikuti langkah-langkah berikut untuk menjalankan sistem ini di lingkungan pengembangan (*local environment*):

```bash
# 1. Clone repositori ini
git clone [URL-REPOSITORI]
cd permit-to-work

# 2. Instalasi dependensi PHP & Node.js
composer install
npm install

# 3. Setup Environment Variables
cp .env.example .env

# Konfigurasikan koneksi database Anda di file .env
# DB_DATABASE=nama_database_anda
# DB_USERNAME=root
# DB_PASSWORD=

# 4. Generate Application Key
php artisan key:generate

# 5. Jalankan Migrasi Database dan Seeder (Untuk akun default)
php artisan migrate --seed

# 6. Build aset frontend & Jalankan server lokal
npm run dev
php artisan serve
