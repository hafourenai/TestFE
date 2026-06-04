# AttendaEase

> **English?** Lihat [README.md](README.md) — Documentation in English.

Sistem manajemen absensi karyawan sederhana dengan fitur CRUD lengkap, dibangun menggunakan PHP, MySQL, dan template Backstrap.

## Tech Stack

| Lapisan      | Teknologi                                    |
|-------------|----------------------------------------------|
| Backend     | PHP 8.0 (Murni)                              |
| Database    | MySQL / MariaDB via MySQLi                   |
| Frontend    | HTML5, CSS3, Bootstrap 4.6 + CoreUI 2       |
| Template    | Backstrap (`@digitallyhappy/backstrap`)     |
| Ikon        | Font Awesome 4.7 + CoreUI Icons             |
| Font        | Source Sans Pro (Google Fonts)              |

## Struktur Project

```
Test FrontEnd/
├── assets/
│   ├── css/
│   │   └── custom.css             # Gaya CSS kustom aplikasi
│   └── backstrap/                 # Aset template Backstrap asli
│       ├── css/
│       ├── js/
│       ├── img/
│       └── vendors/
├── config/
│   └── database.php           # Konfigurasi koneksi MySQL (port 8111)
├── database/
│   └── attendance.sql         # Skema database + data awal
├── includes/
│   ├── header.php             # <head>, CSS Backstrap + vendor
│   ├── navbar.php             # CoreUI .app-header (nav atas)
│   ├── sidebar.php            # CoreUI .sidebar + pembungkus .main
│   └── footer.php             # Nav mobile bawah, JS Backstrap
├── pages/
│   ├── dashboard.php          # Overview dengan kartu statistik
│   ├── attendance-list.php    # Daftar dengan pencarian, urut, halaman
│   ├── attendance-create.php  # Tambah data absensi baru
│   ├── attendance-edit.php    # Edit data absensi
│   └── attendance-delete.php  # Penghapus data
├── login.php                  # Halaman login (standalone)
├── logout.php                 # Hancurkan session + redirect
├── setup.php                  # Pembuat akun admin pertama kali
└── index.php                  # Pengontrol utama / router
```

## Fitur

- **Autentikasi** — Login/logout dengan keamanan berbasis session
- **Dashboard** — Ringkasan total data, jumlah karyawan pria/wanita, absensi hari ini
- **CRUD Absensi** — Buat, lihat, edit, dan hapus data absensi
- **Cari & Urut** — Filter berdasarkan nama, urut berdasarkan nama/tanggal/jam masuk
- **Paginasi** — 10 data per halaman dengan navigasi halaman
- **Responsif** — Layout sidebar untuk desktop, navigasi bawah untuk mobile
- **UI Berbasis Gender** — Badge gender berbeda warna + inisial avatar
- **Statistik Real-time** — Rata-rata jam masuk, jumlah keterlambatan, dan ringkasan

## Ringkasan Tugas

Project ini dibuat sebagai solusi dari tugas berikut:

> Buatkan halaman admin sederhana untuk absensi karyawan menggunakan template **Backstrap**.
>
> **a.** Halaman list data karyawan yang telah absen, dengan fitur update, delete, sort by & pagination
>
> **b.** Halaman input data absensi dengan field: Nama, Alamat, Jenis kelamin, Tanggal absen, Jam masuk, Jam keluar

### Status Pengerjaan

| Persyaratan                       | Status | Implementasi                                |
|-----------------------------------|--------|---------------------------------------------|
| a. Halaman list                   | ✅     | `pages/attendance-list.php`                 |
| a. Update (edit)                  | ✅     | Tombol Edit → `pages/attendance-edit.php`   |
| a. Delete                         | ✅     | Tombol Hapus + konfirmasi → `attendance-delete.php` |
| a. Sort by                        | ✅     | Dropdown + klik header kolom                |
| a. Pagination                     | ✅     | 10/halaman, nomor halaman, prev/next        |
| b. Field Nama                     | ✅     | Input teks dengan ikon                      |
| b. Field Alamat                   | ✅     | Textarea                                    |
| b. Field Jenis Kelamin            | ✅     | Select dropdown (Pria / Wanita)             |
| b. Field Tanggal absen            | ✅     | Date picker                                 |
| b. Field Jam masuk                | ✅     | Time picker                                 |
| b. Field Jam keluar               | ✅     | Time picker                                 |

Seluruh persyaratan tugas **telah selesai** dikerjakan.

## Cara Menjalankan

1. Import `database/attendance.sql` ke MySQL Anda 
2. Sesuaikan `config/database.php` jika kredensial MySQL berbeda
3. Akses `http://localhost/Test%20FrontEnd/setup.php` untuk membuat akun admin
4. Login dengan username **`admin`** dan password **`admin123`**
5. Mulai kelola data absensi

## Dokumentasi Lengkap

[Lihat di Canva](https://canva.link/ju13s9kxh7fl22t)
