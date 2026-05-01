# CRM PT. Smart

**Live Demo:** [https://smart-crm.up.railway.app](https://smart-crm.up.railway.app)

### Tech Stack

- **Framework:** Laravel 13
- **Admin Panel:** Filament v5
- **Styling:** Tailwind CSS 4
- **Database:** MySQL
- **Export:** OpenSpout (Native PHP 8.4 Compatible)

### Fitur Utama

- **Multi-role Dashboard:** Tampilan dinamis sesuai peran pengguna.
- **Data Isolation:** Implementasi keamanan di mana Sales hanya bisa melihat data miliknya sendiri, sementara Manager memiliki akses menyeluruh.
- **Deal Pipeline & Approval:** Sistem konversi prospek menjadi klien dengan fitur negosiasi harga dan persetujuan manajer jika harga di bawah margin.
- **Reporting:** Laporan penjualan yang dapat difilter berdasarkan periode dan diunduh ke Excel.

## Panduan Instalasi Lokal

### 1. Persyaratan Sistem

- PHP >= 8.4
- Composer 2.x
- Node.js & NPM
- MySQL atau MariaDB

### 2. Langkah Instalasi

```bash
# 1. Clone repositori
git clone https://github.com/dzraka/mochamad_crm.git
cd mochamad_crm

# 2. Install dependensi PHP
composer install

# 3. Install & Build dependensi Frontend (Tailwind/Filament)
npm install
npm run build

# 4. Konfigurasi Environment
cp .env.example .env
php artisan key:generate

# 5. Konfigurasi Database
# Buka file .env dan sesuaikan DB_DATABASE, DB_USERNAME, DB_PASSWORD
```

### 3. Migrasi & Seeding

Sistem sudah dilengkapi dengan data bawaan (dummy users & produk).

```bash
php artisan migrate --seed
```

### 4. Jalankan Aplikasi

```bash
php artisan serve
```

Akses aplikasi melalui: `http://localhost:8000/admin`

### 5. Akun Testing

Gunakan kredensial berikut untuk masuk ke dalam panel admin:

| Peran       | Email               | Password   |
| ----------- | ------------------- | ---------- |
| **Manager** | manager@example.com | `password` |
| **Sales 1** | andi@example.com    | `password` |
| **Sales 2** | budi@example.com    | `password` |

---
