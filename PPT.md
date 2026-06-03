
Berikut konten yang bisa kamu pakai untuk tiap slide PPT. Tiap bagian ada **narasi** + **kode yang perlu di-screenshot (SS)**.

---

# Slide 1 — Judul
**AttendEase**  
Sistem Manajemen Absensi Karyawan  
PHP + MySQL + Backstrap Template

---

# Slide 2 — Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Backend | PHP 8.0 Native (MySQLi) |
| Database | MySQL / MariaDB |
| Frontend | Bootstrap 4.6 + CoreUI 2 |
| Template | Backstrap (`@digitallyhappy/backstrap`) |
| Ikon | Font Awesome 4.7 |
| Font | Source Sans Pro |

**Narasi:**  
*"Project ini menggunakan PHP native tanpa framework. Tampilan menggunakan template Backstrap — yaitu Bootstrap 4 yang sudah dikombinasikan dengan CoreUI 2 untuk layout admin. Icon pakai Font Awesome, font-nya Source Sans Pro."*

---

# Slide 3 — Struktur Folder

```
Test FrontEnd/
├── assets/
│   ├── css/custom.css        ← gaya kustom
│   └── backstrap/            ← template Backstrap (vendor asli)
├── config/database.php       ← koneksi MySQL
├── database/attendance.sql   ← skema database
├── includes/
│   ├── header.php            ← head, CSS
│   ├── navbar.php            ← top navigation bar
│   ├── sidebar.php           ← sidebar kiri
│   └── footer.php            ← JS, mobile nav
├── pages/
│   ├── dashboard.php         ← halaman utama
│   ├── attendance-list.php   ← daftar absensi
│   ├── attendance-create.php ← tambah data
│   ├── attendance-edit.php   ← edit data
│   └── attendance-delete.php ← hapus data (handler)
├── login.php                 ← halaman login
├── setup.php                 ← inisialisasi admin
└── index.php                 ← router utama
```

**SS yang disarankan:** Screenshot struktur folder dari VS Code atau editor.

**Narasi:**  
*"Struktur project sederhana. Semua file PHP ada di root, kode template di `includes/`, halaman konten di `pages/`, aset statis di `assets/`."*

---

# Slide 4 — Database Design

Tabel `users`:
```sql
id         INT AUTO_INCREMENT PRIMARY KEY
username   VARCHAR(50) UNIQUE
password   VARCHAR(255)   -- bcrypt hash
full_name  VARCHAR(100)
photo      VARCHAR(255)
created_at TIMESTAMP
```

Tabel `attendance`:
```sql
id               INT AUTO_INCREMENT PRIMARY KEY
name             VARCHAR(100)
address          TEXT
gender           ENUM('Male','Female')
attendance_date  DATE
check_in_time    TIME
check_out_time   TIME
created_at       TIMESTAMP
```

**SS yang disarankan:** Screenshot struktur tabel dari phpMyAdmin.

**Narasi:**  
*"Cuma dua tabel. `users` untuk login, `attendance` untuk data absensi. Password di-hash pake bcrypt. Attendance date, check-in, check-out pake tipe DATE/TIME biar gampang dioperasikan."*

---

# Slide 5 — Router (index.php)

**Fungsi:** Entry point setelah login. Semua request lewat sini.

**Narasi:**  
*"`index.php` adalah front controller. Cek session dulu, kalau belum login lempar ke `login.php`. Parameter `?page=` menentukan halaman mana yang dimuat."*

**Kode yang di-SS:**
```php
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$validPages = ['dashboard', 'attendance-list', 'attendance-create', 'attendance-edit', 'attendance-delete'];
if (!in_array($page, $validPages)) {
    $page = 'dashboard';
}
require_once 'includes/header.php';
require_once 'includes/navbar.php';
require_once 'includes/sidebar.php';
if (file_exists($pageFile)) {
    include $pageFile;
}
require_once 'includes/footer.php';
```

---

# Slide 6 — Login Page

**SS yang disarankan:** Tampilan login page (browser, full screen).

**Narasi:**  
*"Halaman login pakai layout Backstrap bawaan. Background gradient purple, card login di tengah dengan shadow. Input username & password pakai icon Font Awesome di kiri."*

**Kode yang di-SS:** Bagian form login:
```php
<form method="POST" action="">
<div class="input-group mb-3">
<div class="input-group-prepend">
<span class="input-group-text"><i class="fa fa-user"></i></span>
</div>
<input type="text" name="username" class="form-control" placeholder="Username" required autofocus/>
</div>
<div class="input-group mb-4">
<div class="input-group-prepend">
<span class="input-group-text"><i class="fa fa-lock"></i></span>
</div>
<input type="password" name="password" class="form-control" placeholder="Password" required/>
</div>
<button type="submit" class="btn btn-primary px-4 btn-block">Sign In</button>
</form>
```

Dan bagian verifikasi password:
```php
if (password_verify($password, $user['password'])) {
    $_SESSION['logged_in'] = true;
    $_SESSION['user_id'] = $user['id'];
    // ...
    header('Location: index.php?page=dashboard');
}
```

---

# Slide 7 — Setup Page

**SS yang disarankan:** Tampilan setup page setelah berhasil bikin admin.

**Narasi:**  
*"`setup.php` diakses sekali saja saat pertama kali. Dia bikin tabel `users` kalau belum ada, lalu insert akun admin default. Kalau admin sudah ada, kasih pesan saja."*

**Kode yang di-SS:**
```php
$conn->query("CREATE TABLE IF NOT EXISTS users (...)"); 

$hash = password_hash('admin123', PASSWORD_BCRYPT);
$stmt = $conn->prepare("INSERT INTO users (username, password, full_name, photo) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $un, $hash, $fn, $ph);
$stmt->execute();
```

---

# Slide 8 — Dashboard

**SS yang disarankan:** Tampilan dashboard dengan 4 kartu statistik + tabel recent.

**Narasi:**  
*"Dashboard menampilkan 4 kartu statistik: total records, jumlah laki-laki, jumlah perempuan, dan absensi hari ini. Data diambil pake query `COUNT` langsung dari database. Jadi real-time berdasarkan data yang ada."*

**Kode yang di-SS:** Bagian query:
```php
$totalRecords = $conn->query("SELECT COUNT(*) as count FROM attendance")->fetch_assoc()['count'];
$totalMale = $conn->query("SELECT COUNT(*) as count FROM attendance WHERE gender='Male'")->fetch_assoc()['count'];
$attendanceToday = $conn->query("SELECT COUNT(*) as count FROM attendance WHERE attendance_date='$todayDate'")->fetch_assoc()['count'];
```

Bagian kartu statistik (pilih salah satu kartu):
```html
<div class="card">
<div class="card-body">
<div class="d-flex justify-content-between align-items-start mb-2">
<span class="stat-icon-box" style="background: #e8f0fe;">
<i class="fa fa-users" style="color: #0d6efd;"></i>
</span>
<small class="text-muted font-weight-bold text-uppercase">Total</small>
</div>
<div class="stat-label">Total Attendance Records</div>
<div class="stat-value"><?php echo $totalRecords; ?></div>
</div>
</div>
```

---

# Slide 9 — Halaman List (Attendance Data)

**SS yang disarankan:** Tampilan list page dengan tabel, search, sort, pagination, dan summary cards bawah.

**Narasi:**  
*"Halaman utama setelah dashboard. Ada fitur search berdasarkan nama, sort by kolom (klik header), pilih ascending/descending, dan pagination 10 data per halaman. Di bawah tabel ada ringkasan: total records, rata-rata jam masuk, dan jumlah yang terlambat hari ini."*

**Kode yang di-SS:** Bagian pagination:
```php
$pageNo = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$limit = 10;
$offset = ($pageNo - 1) * $limit;
$totalResult = $conn->query("SELECT COUNT(*) as count FROM attendance $searchCond");
$totalPages = ceil($totalRows / $limit);
$result = $conn->query("SELECT * FROM attendance $searchCond ORDER BY $sort $order LIMIT $offset, $limit");
```

Bagian tabel dengan sort link:
```html
<a href="index.php?page=attendance-list&sort=name&order=...">
Name <?php if ($sort === 'name'): ?><i class="fa fa-arrow-up"></i><?php endif; ?>
</a>
```

Bagian action edit/delete:
```html
<a href="index.php?page=attendance-edit&id=..." class="text-primary mr-2" title="Edit">
<i class="fa fa-pencil"></i>
</a>
<a href="#" onclick="confirmDelete(...)" class="text-danger" title="Delete">
<i class="fa fa-trash"></i>
</a>
```

Bagian summary cards:
```html
<div class="card summary-card text-white bg-primary">
<div class="card-body d-flex align-items-center">
<div class="mr-3"><i class="fa fa-users fa-2x"></i></div>
<div>
<div class="small text-uppercase">Total Records</div>
<div class="h3 font-weight-bold mb-0"><?php echo $totalRecords; ?></div>
</div>
</div>
</div>
```

---

# Slide 10 — Halaman Tambah Data (Create)

**SS yang disarankan:** Tampilan form create dengan input fields.

**Narasi:**  
*"Form input absensi dengan field: Nama, Jenis Kelamin, Tanggal, Jam Masuk, Jam Keluar, dan Alamat. Masing-masing pakai icon Font Awesome di input group. Ada validasi required dan error handling."*

**Kode yang di-SS:** Bagian insert:
```php
$stmt = $conn->prepare("INSERT INTO attendance (name, address, gender, attendance_date, check_in_time, check_out_time) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $name, $address, $gender, $attendance_date, $check_in_time, $check_out_time);
$stmt->execute();
```

Contoh input group:
```html
<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text"><i class="fa fa-calendar"></i></span>
</div>
<input type="date" name="attendance_date" class="form-control" required/>
</div>
```

---

# Slide 11 — Halaman Edit

**SS yang disarankan:** Tampilan form edit (mirip create tapi data sudah terisi).

**Narasi:**  
*"Form edit mirip dengan create, tapi data diambil dari database dulu berdasarkan ID, lalu diisi ke input. Setelah disubmit, query UPDATE dijalankan."*

**Kode yang di-SS:**
```php
$record = $conn->query("SELECT * FROM attendance WHERE id=$id");
$row = $record->fetch_assoc();
// ... form dengan value="<?php echo $row['name']; ?>"
$stmt = $conn->prepare("UPDATE attendance SET name=?, address=?, gender=?, attendance_date=?, check_in_time=?, check_out_time=? WHERE id=?");
$stmt->bind_param("ssssssi", ...);
$stmt->execute();
```

---

# Slide 12 — Halaman Hapus (Delete Handler)

**Narasi:**  
*"Halaman hapus sederhana — ambil ID dari URL, cek apakah record ada, kalau ada jalankan DELETE, lalu redirect balik ke list page dengan pesan sukses."*

**Kode yang di-SS:**
```php
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
// validasi...
$stmt = $conn->prepare("DELETE FROM attendance WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
header('Location: index.php?page=attendance-list&msg=' . urlencode('Attendance record deleted successfully.'));
```

---

# Slide 13 — Navbar & Sidebar

**SS yang disaranan:** Screenshot navbar (atas) dan sidebar (kiri) secara bersamaan.

**Narasi:**  
*"Navbar pakai CoreUI `.app-header` dengan toggle button untuk sidebar, brand logo, notifikasi (placeholder), dan avatar user dropdown. Sidebar pakai `.sidebar-nav` dengan navigasi ke Dashboard, Attendance Data, dan Add Attendance."*

**Kode yang di-SS:** Sidebar navigation item:
```php
<li class="nav-item">
<a class="nav-link <?php echo navActive('dashboard'); ?>" href="index.php?page=dashboard">
<i class="fa fa-tachometer"></i> Dashboard
</a>
</li>
```

---

# Slide 14 — custom.css (Styling)

**Narasi:**  
*"Semua gaya kustom ada di `assets/css/custom.css`. Mulai dari kartu dengan border-radius 12px, button dengan efek hover lift dan shadow, badge dengan warna soft, sampai animasi fade-in."*

**Kode yang di-SS:** Pilih beberapa section:
```css
/* Card styling */
.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    transition: box-shadow 0.2s ease, transform 0.2s ease;
}
.card:hover {
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
}

/* Button primary gradient */
.btn-primary {
    background: linear-gradient(135deg, #0d6efd, #0a58ca);
    border: none;
}
.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

/* Fade-in animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(8px); }
    to   { opacity: 1; transform: translateY(0); }
}
```

---

# Slide 15 — Mobile Responsive

**SS yang disarankan:** Screenshot tampilan mobile (bisa pake Chrome DevTools mobile mode).

**Narasi:**  
*"Di mobile, sidebar bisa di-toggle lewat hamburger button. Ada juga bottom navigation bar fixed di bawah untuk akses cepat ke halaman utama."*

**Kode yang di-SS:**
```css
.mobile-bottom-nav {
    display: none;
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: #fff;
    border-top: 1px solid #e9ecef;
    z-index: 1020;
    justify-content: space-around;
    padding: 6px 0 env(safe-area-inset-bottom, 6px);
    box-shadow: 0 -4px 12px rgba(0,0,0,0.06);
}
@media (max-width: 991.98px) {
    .mobile-bottom-nav { display: flex; }
    .main { margin-bottom: 60px; }
}
```

---

# Slide 16 — Penutup

**Narasi:**  
*"Project ini memenuhi semua requirement tugas: list dengan search/sort/pagination, form input lengkap, edit, dan delete. Dibangun dengan PHP native dan template Backstrap (Bootstrap 4 + CoreUI 2) sehingga tampilannya rapi dan responsif."*

**Terima Kasih**
