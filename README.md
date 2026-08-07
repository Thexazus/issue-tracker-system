# IT Ticketing System - Laravel 12 & Bootstrap 5

Sebuah sistem pelaporan bug, error, dan penugasan issue (IT Ticketing) yang dirancang untuk merampingkan kolaborasi antara Quality Assurance (QA), Developer, dan Administrator dalam tim pengembangan software.

Proyek ini dibangun menggunakan **Laravel 12**, **PHP 8.3**, **SQLite (Zero-Configuration)**, dan **Bootstrap 5** dengan fokus pada standar penulisan kode bersih (*Clean Code*), prinsip *SOLID*, *Form Request Validation*, dan *Row-Level Security* berbasis peran (*Role-Based Access*).

---

## 🚀 Fitur Utama
1. **Autentikasi & Otorisasi**: Login & logout terproteksi dengan pembatasan hak akses berbasis 3 Peran: **Admin**, **Developer**, dan **QA**.
2. **Dashboard Dinamis**: Menampilkan statistik counter tiket (Open, In Progress, Resolved, Closed), ringkasan tiket terbaru, dan histori log aktivitas terkini yang disaring otomatis berdasarkan peran pengguna yang masuk.
3. **CRUD Tiket dengan Auto-Number**: Manajemen siklus hidup tiket dari pelaporan awal (QA) hingga verifikasi selesai. Nomor tiket di-generate otomatis secara terenkapsulasi oleh Model dengan format: `TKT-YYYYMMDD-XXXX`.
4. **Penyimpanan Bukti Screenshot**: Unggah bukti error berupa file gambar dengan validasi ukuran file maksimal 2MB dan pembersihan otomatis berkas sampah di storage saat tiket diperbarui atau dihapus.
5. **Thread Diskusi (Comments)**: Kolaborasi tim secara real-time pada halaman detail tiket.
6. **Search & Multi-Filter**: Pencarian tiket berdasarkan judul, deskripsi, atau nomor tiket, serta filter status dan prioritas yang bertahan saat berpindah halaman (*Pagination Query Retention*).
7. **Audit Trail (Activity Log)**: Rekam jejak transparan yang mencatat detail mutasi tiket (pembuat, assignee baru, perubahan status lama ke baru, komentar) untuk keamanan audit sistem.
8. **Pengaturan Profil**: Pengguna dapat mengubah nama lengkap, email unik, mengunggah foto profil kustom, dan memperbarui kata sandi secara aman.

---

## 🛠️ Stack Teknologi
* **Backend**: Laravel 13.24 (Laravel 12+ standard), PHP 8.3
* **Database**: SQLite (Relational Database, zero-configuration)
* **Frontend**: Blade Templating Engine, Bootstrap 5, Bootstrap Icons
* **Desain UI**: Modern Blue & Indigo Theme, Glassmorphism, Responsive Mobile-First Layout

---

## 🔑 Akun Demo Pengujian (Password: `password`)
Untuk memudahkan pengujian dan demo selama interview, kami menyediakan tombol *autofill* di halaman login. Berikut adalah daftar akun bawaan:
* **Admin**: `rae@ticketing.com` (Mengelola semua tiket, menunjuk Developer penanggung jawab, memantau seluruh log sistem).
* **Developer**: `alex@ticketing.com` (Melihat tiket tugasnya, mengubah status pengerjaan, berdiskusi).
* **QA Specialist**: `sarah@ticketing.com` (Melaporkan tiket baru, mengunggah screenshot, mengedit tiket laporannya, verifikasi selesai).

---

## ⚡ Cara Menjalankan Project Secara Lokal

Ikuti langkah mudah berikut untuk menjalankan proyek di komputer Anda:

### 1. Kloning Project & Masuk ke Direktori
```bash
git clone <repository_url>
cd issue-tracker-system
```

### 2. Install Dependensi Composer
```bash
composer install
```

### 3. Salin Konfigurasi Environment
```bash
copy .env.example .env
```
*(Pastikan `DB_CONNECTION=sqlite` aktif di file `.env` Anda. File database `database/database.sqlite` akan dibuat dan digunakan secara otomatis).*

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Jalankan Migrasi & Database Seeder
```bash
php artisan migrate:fresh --seed
```
*(Perintah ini akan menyusun ulang tabel dan memasukkan akun demo beserta data tiket uji coba).*

### 6. Buat Symbolic Link untuk Storage
```bash
php artisan storage:link
```
*(Penting agar file screenshot yang diunggah dapat dirender di halaman web).*

### 7. Jalankan Server Lokal
```bash
php artisan serve
```
Buka browser dan akses **`http://127.0.0.1:8000`** untuk masuk ke sistem.

---

## 🧪 Menjalankan Unit & Feature Testing
Untuk memverifikasi keutuhan sistem dan memastikan tidak ada error regresi:
```bash
php artisan test
```
