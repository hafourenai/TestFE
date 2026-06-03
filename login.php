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
<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no"/>
<title>Login - AttendEase</title>
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
<h1><i class="fa fa-id-badge fa-3x text-primary mb-3"></i></h1>
<h1 class="mb-1 font-weight-bold">AttendEase</h1>
<p class="text-muted">Sign in to your account</p>
<?php if ($error): ?>
<div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
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
<div class="row">
<div class="col-12">
<button type="submit" class="btn btn-primary px-4 btn-block"><i class="fa fa-sign-in"></i> Sign In</button>
</div>
</div>
</form>
<div class="mt-3">
<small class="text-muted">First time? Run <a href="setup.php">setup.php</a> to create admin account.</small>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</body>
</html>
