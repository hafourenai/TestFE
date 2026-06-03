<?php
session_start();
require_once 'config/database.php';

$message = '';
$error = '';

try {
    $conn->query("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        full_name VARCHAR(100) NOT NULL,
        photo VARCHAR(255) DEFAULT 'admin.jpg',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $result = $conn->query("SELECT COUNT(*) as count FROM users WHERE username = 'admin'");
    $row = $result->fetch_assoc();

    if ($row['count'] == 0) {
        $hash = password_hash('admin123', PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO users (username, password, full_name, photo) VALUES (?, ?, ?, ?)");
        $un = 'admin';
        $fn = 'Administrator';
        $ph = 'admin.jpg';
        $stmt->bind_param("ssss", $un, $hash, $fn, $ph);
        $stmt->execute();
        $message = 'Setup complete! Admin user created (username: <strong>admin</strong>, password: <strong>admin123</strong>).';
    } else {
        $message = 'Admin user already exists. No changes made.';
    }
} catch (Exception $e) {
    $error = 'Error: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no"/>
<title>Setup - AttendEase</title>
<link href="assets/backstrap/vendors/@coreui/icons/css/coreui-icons.min.css" rel="stylesheet"/>
<link href="assets/backstrap/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic" rel="stylesheet"/>
<link href="assets/backstrap/css/style.min.css" rel="stylesheet"/>
<link href="assets/css/custom.css" rel="stylesheet"/>
</head>
<body class="app flex-row align-items-center">
<div class="container">
<div class="row justify-content-center">
<div class="col-md-6 col-lg-5">
<div class="card-group">
<div class="card p-4">
<div class="card-body text-center">
<h1><i class="fa fa-cog fa-3x text-primary mb-3"></i></h1>
<h1 class="mb-1 font-weight-bold">Setup</h1>
<p class="text-muted">Initialize admin account</p>
<?php if ($message): ?>
<div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>
<?php if ($error): ?>
<div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<a href="login.php" class="btn btn-primary btn-block"><i class="fa fa-arrow-left"></i> Go to Login</a>
</div>
</div>
</div>
</div>
</div>
</div>
</body>
</html>
