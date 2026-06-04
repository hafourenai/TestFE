<?php
$current_page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
function isActive($page) {
    global $current_page;
    return $current_page === $page;
}
function navActive($page) {
    return isActive($page) ? 'active' : '';
}
?>
<div class="app-body">
<div class="sidebar sidebar-pills bg-transparent">
<nav class="sidebar-nav">
<ul class="nav">
<li class="nav-title">Navigation</li>
<li class="nav-item">
<a class="nav-link <?php echo navActive('dashboard'); ?>" href="index.php?page=dashboard">
<i class="fa fa-tachometer"></i> Dashboard
</a>
</li>
<li class="nav-item">
<a class="nav-link <?php echo navActive('attendance-list'); ?>" href="index.php?page=attendance-list">
<i class="fa fa-calendar"></i> Attendance Data
</a>
</li>
<li class="nav-item">
<a class="nav-link <?php echo navActive('attendance-create'); ?>" href="index.php?page=attendance-create">
<i class="fa fa-user-plus"></i> Add Attendance
</a>
</li>
<li class="divider"></li>
<li class="nav-title">Account</li>
<li class="nav-item">
<a class="nav-link <?php echo navActive('settings'); ?>" href="index.php?page=settings">
<i class="fa fa-cog"></i> Settings
</a>
</li>
<li class="nav-item">
<a class="nav-link" href="logout.php">
<i class="fa fa-sign-out"></i> Logout
</a>
</li>
</ul>
</nav>
</div>
<main class="main">
<div class="container-fluid">
<div class="animated fadeIn">
