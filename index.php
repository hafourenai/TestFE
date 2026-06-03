<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$validPages = ['dashboard', 'attendance-list', 'attendance-create', 'attendance-edit', 'attendance-delete'];
if (!in_array($page, $validPages)) {
    $page = 'dashboard';
}
$pageFile = 'pages/' . $page . '.php';
$titles = [
    'dashboard' => 'Dashboard',
    'attendance-list' => 'Attendance Data',
    'attendance-create' => 'Add Attendance',
    'attendance-edit' => 'Edit Attendance',
    'attendance-delete' => 'Delete Attendance'
];
$pageTitle = $titles[$page] ?? ucwords(str_replace('-', ' ', $page));

require_once 'config/database.php';
require_once 'includes/header.php';
require_once 'includes/navbar.php';
require_once 'includes/sidebar.php';

if (file_exists($pageFile)) {
    include $pageFile;
} else {
    echo '<div class="p-lg"><div class="bg-error-container text-on-error-container p-md rounded-lg">Page not found.</div></div>';
}

require_once 'includes/footer.php';
