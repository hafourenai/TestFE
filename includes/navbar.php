<nav class="navbar-backstrap d-flex align-items-center justify-content-between sticky-top">
<div class="d-flex align-items-center gap-3">
<a href="index.php?page=dashboard" class="brand">AttendEase</a>
</div>
<div class="d-none d-md-flex flex-grow-1" style="max-width: 400px; margin: 0 24px;">
<div class="search-wrapper w-100">
<span class="material-symbols-outlined search-icon">search</span>
<input type="text" class="form-control form-backstrap" placeholder="Search records..." style="padding-left: 40px;"/>
</div>
</div>
<div class="d-flex align-items-center gap-3">
<button class="btn p-1 text-secondary position-relative">
<span class="material-symbols-outlined">notifications</span>
</button>
<div class="d-flex align-items-center gap-2">
<img src="assets/admin.jpg" alt="Admin" class="rounded-circle" width="32" height="32" style="border: 1px solid var(--outline-variant); object-fit: cover;"/>
<span class="d-none d-md-inline text-secondary" style="font-size: 13px; font-weight: 500;"><?php echo isset($_SESSION['full_name']) ? htmlspecialchars($_SESSION['full_name']) : 'Admin User'; ?></span>
</div>
</div>
</nav>
