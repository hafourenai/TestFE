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
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Setup - AttendancePro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="assets/css/backstrap.css" rel="stylesheet"/>
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">
                    <span class="material-symbols-outlined">settings</span>
                </div>
                <h1>Setup</h1>
                <p>Initialize admin account</p>
            </div>

            <?php if ($message): ?>
                <div class="alert-backstrap-success mb-3"><?php echo $message; ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert-backstrap-error mb-3"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <a href="login.php" class="btn btn-backstrap-primary btn-backstrap w-100">Go to Login</a>
        </div>
    </div>
</body>
</html>
