<?php
session_start();
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: index.php?page=dashboard');
    exit;
}

require_once 'config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Please fill in all fields.';
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['user_photo'] = $user['photo'];
                header('Location: index.php?page=dashboard');
                exit;
            }
        }
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login - AttendancePro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link href="assets/css/backstrap.css" rel="stylesheet"/>
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">
                    <span class="material-symbols-outlined">badge</span>
                </div>
                <h1>AttendancePro</h1>
                <p>Sign in to your account</p>
            </div>

            <?php if ($error): ?>
                <div class="alert-backstrap-error mb-3"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label login-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text login-input-icon">
                            <span class="material-symbols-outlined">person</span>
                        </span>
                        <input type="text" name="username" class="form-control login-input" placeholder="Enter your username" required autofocus/>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label login-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text login-input-icon">
                            <span class="material-symbols-outlined">lock</span>
                        </span>
                        <input type="password" name="password" class="form-control login-input" placeholder="Enter your password" required/>
                    </div>
                </div>
                <button type="submit" class="btn btn-backstrap-primary btn-backstrap w-100" style="padding: 12px 16px;">Sign In</button>
            </form>

            <div class="login-footer">
                <small class="text-secondary">First time? Run <a href="setup.php">setup.php</a> to create admin account.</small>
            </div>
        </div>
    </div>
</body>
</html>
