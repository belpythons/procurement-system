# 🏗️ Permit-To-Work (PTW) System - PT. KMI

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![PHP](https://img.shields.io/badge/PHP_8.5+-777BB4?style=for-the-badge&logo=php&logoColor=white)

Sistem Informasi **Permit-To-Work (Izin Kerja)** modern dan terintegrasi yang dirancang khusus untuk memenuhi standar Keselamatan dan Kesehatan Kerja (K3) di lingkungan pabrik/industri berskala besar (studi kasus: PT. Kaltim Methanol Industri). 

Aplikasi ini telah dimodernisasi menggunakan arsitektur **Laravel 11** dengan antarmuka pengguna berbasis **Tailwind CSS & Flowbite** yang mengusung desain korporat *Neo-Brutalist* yang bersih, responsif, dan mudah dipindai (*scannable*).

---

## ✨ Fitur Unggulan

* 🔐 **Role-Based Access Control (RBAC) Ketat**: 4 Ekosistem antarmuka terpisah untuk **Admin**, **Pekerja**, **Supervisor**, dan **Safety Officer**.
* 🤖 **Auto-Assign Workflow**: Pekerja tidak perlu pusing memilih atasan. Sistem secara otomatis merutekan pengajuan permit ke Supervisor dan Safety Officer yang tepat berdasarkan *Department* pekerja.
* 📊 **Dashboard Dinamis**: Statistik *real-time* dan pelacakan status permit (*Pending, Disetujui, Ditolak, Selesai*).
* 📝 **Evaluasi K3 Terintegrasi**: Form khusus untuk Safety Officer memberikan penilaian tingkat risiko (Rendah/Sedang/Tinggi) dan rekomendasi APD.
* 🖨️ **Export Laporan (PDF & Excel)**: Cetak laporan permit dengan format lanskap presisi (DomPDF) atau *streaming* Excel ribuan baris tanpa *memory leak* (OpenSpout).
* 📥 **Import Data Massal**: Fitur eksklusif Admin untuk migrasi riwayat permit lama via template `.xlsx` dengan validasi otomatis.

---

## 🛠️ Tech Stack Utama

* **Backend:** Laravel 11 (PHP 8.5+)
* **Frontend:** Blade Templating, Tailwind CSS, Flowbite (UI Components)
* **Database:** MySQL
* **Packages/Dependencies:**
  * `barryvdh/laravel-dompdf` (Cetak Dokumen PDF)
  * `openspout/openspout` (Generator & Reader Excel super cepat)

---

## 🚀 Panduan Instalasi (Langkah demi Langkah)

Ikuti instruksi di bawah ini untuk menjalankan sistem PTW ini di mesin lokal Anda.

### 1. Persyaratan Sistem (Prerequisites)
Pastikan komputer Anda sudah terinstal perangkat lunak berikut:
* **PHP** (Minimal versi 8.5 untuk kompatibilitas OpenSpout)
* **Composer** (Dependency Manager PHP)
* **Node.js & NPM** (Untuk kompilasi Tailwind CSS)
* **MySQL** (Atau MariaDB via XAMPP/Laragon)

### 2. Kloning Repositori
Buka terminal/CMD Anda dan kloning repositori ini:
```bash
git clone [https://github.com/username-anda/permit-to-work.git](https://github.com/username-anda/permit-to-work.git)
cd permit-to-work

```

### 3. Konfigurasi Environment (`.env`)

Salin file konfigurasi bawaan dan sesuaikan kredensial database Anda:

```bash
cp .env.example .env

```

Buka file `.env` di *code editor* Anda, lalu sesuaikan bagian database berikut (pastikan Anda sudah membuat database kosong bernama `ptw_kmi` di MySQL):

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ptw_kmi
DB_USERNAME=root
DB_PASSWORD=

```

### 4. Instalasi Dependencies (Backend & Frontend)

Jalankan perintah berikut untuk mengunduh seluruh *library* PHP dan Node.js:

```bash
composer install
npm install

```

### 5. Generate Application Key & Storage Link

```bash
php artisan key:generate
php artisan storage:link

```

### 6. Migrasi Database & Seeding Data Dummy

> **⚠️ PENTING:** Perintah ini akan membangun tabel dari awal dan mengisi database dengan data *dummy* terstruktur (Departemen, Akun User, dan 19 Sampel Permit).

```bash
php artisan migrate:fresh --seed

```

### 7. Kompilasi Aset Visual (Tailwind & Flowbite)

Agar tampilan CSS dapat di-render dengan sempurna, jalankan *build* aset:

```bash
npm run build

```

*(Gunakan `npm run dev` jika Anda ingin melakukan perubahan kode sambil melihat hasilnya secara live).*

### 8. Jalankan Server Lokal

```bash
php artisan serve

```

Aplikasi sekarang dapat diakses melalui browser di alamat: **`http://localhost:8000`** 🎉

---

## 🔑 Akun Pengujian (Seeder Defaults)

Gunakan akun di bawah ini untuk mencoba alur kerja (*Walkthrough*) sistem. *(Catatan: Password default untuk seluruh akun hasil seeder adalah `password`)*.

| Role | Username | Departemen | Deskripsi Akses |
| --- | --- | --- | --- |
| **Admin** | `ayuu03` | IT / Sistem Informasi | Akses manajemen user & Import laporan. |
| **Pekerja** | `miraa01` | Maintenance Dept | Akses membuat permit baru. |
| **Supervisor** | `pitaa02` | Produksi / Maintenance | Akses menyetujui (Approve/Reject) permit. |
| **Safety Officer** | `dewii04` | K3 / Safety | Akses memberi evaluasi risiko K3. |

---

## 📸 Cuplikan Layar (Screenshots)

*(Ganti URL gambar di bawah ini dengan screenshot sistem Anda saat sudah berjalan)*

| Dasbor Admin | Dasbor Pekerja |
| --- | --- |
|  |  |

| Form Evaluasi (Safety Officer) | Cetak PDF Laporan |
| --- | --- |
|  |  |

---

## 🛡️ Keamanan & Penanganan Kendala

* **Memory Leak saat Ekspor:** Jika Anda memiliki puluhan ribu data riwayat permit, gunakan selalu format Ekspor Excel karena sistem menggunakan `OpenSpout` yang merender baris demi baris (*streaming*), bukan memuat semuanya di RAM.
* **Tidak Bisa Login:** Pastikan Anda telah menjalankan perintah *seeder* (`php artisan migrate:fresh --seed`).
