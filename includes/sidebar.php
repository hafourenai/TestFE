<?php
$current_page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
function isActive($page) {
    global $current_page;
    return $current_page === $page;
}
?>
<div class="sidebar">
<div class="px-4 mb-4">
<h6 class="fw-bold mb-0" style="color: var(--on-surface); font-size: 16px;">Admin Panel</h6>
<small style="color: var(--on-surface-variant); opacity: 0.7;">HR Department</small>
</div>
<nav class="flex-grow-1 d-flex flex-column">
<a href="index.php?page=dashboard" class="nav-link <?php echo isActive('dashboard') ? 'active' : ''; ?>">
<span class="material-symbols-outlined">dashboard</span>
<span>Dashboard</span>
</a>
<a href="index.php?page=attendance-list" class="nav-link <?php echo isActive('attendance-list') ? 'active' : ''; ?>">
<span class="material-symbols-outlined">calendar_today</span>
<span>Attendance Data</span>
</a>
<a href="index.php?page=attendance-create" class="nav-link <?php echo isActive('attendance-create') ? 'active' : ''; ?>">
<span class="material-symbols-outlined">person_add</span>
<span>Add Attendance</span>
</a>
<div class="mt-auto px-3 pt-3 border-top" style="border-color: var(--outline-variant) !important;">
<a href="logout.php" class="nav-link logout-link">
<span class="material-symbols-outlined">logout</span>
<span>Logout</span>
</a>
</div>
</nav>
</div>
<div class="main-content">
