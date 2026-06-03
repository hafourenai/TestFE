<?php
$pageTitle = 'Add Attendance';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $gender = $_POST['gender'] ?? '';
    $attendance_date = $_POST['attendance_date'] ?? '';
    $check_in_time = $_POST['check_in_time'] ?? '';
    $check_out_time = $_POST['check_out_time'] ?? '';

    if ($name === '' || $gender === '' || $attendance_date === '' || $check_in_time === '' || $check_out_time === '') {
        $error = 'Please fill in all required fields.';
    } elseif (!in_array($gender, ['Male', 'Female'])) {
        $error = 'Invalid gender selected.';
    } else {
        $stmt = $conn->prepare("INSERT INTO attendance (name, address, gender, attendance_date, check_in_time, check_out_time) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $address, $gender, $attendance_date, $check_in_time, $check_out_time);
        if ($stmt->execute()) {
            header('Location: index.php?page=attendance-list&msg=' . urlencode('Attendance record added successfully.'));
            exit;
        } else {
            $error = 'Failed to save record: ' . $conn->error;
        }
        $stmt->close();
    }
}
?>
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
<div>
<h2 class="font-weight-bold mb-1">Add New Attendance</h2>
<p class="text-muted mb-0">Log daily attendance records for staff members efficiently.</p>
</div>
</div>

<?php if ($error !== ''): ?>
<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="row">
<div class="col-lg-8">
<div class="card">
<div class="card-header">
<h5 class="mb-0 text-primary">Attendance Record Form</h5>
</div>
<div class="card-body">
<form method="POST" action="index.php?page=attendance-create">
<div class="form-row">
<div class="form-group col-md-6">
<label class="small text-uppercase font-weight-bold">Employee Name <span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text"><i class="fa fa-user"></i></span>
</div>
<input type="text" name="name" class="form-control" placeholder="Enter full name" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"/>
</div>
</div>
<div class="form-group col-md-6">
<label class="small text-uppercase font-weight-bold">Gender <span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text"><i class="fa fa-venus-mars"></i></span>
</div>
<select name="gender" class="form-control" required>
<option value="" disabled selected>Select Gender</option>
<option value="Male" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
<option value="Female" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
</select>
</div>
</div>
<div class="form-group col-md-6">
<label class="small text-uppercase font-weight-bold">Attendance Date <span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text"><i class="fa fa-calendar"></i></span>
</div>
<input type="date" name="attendance_date" class="form-control" required value="<?php echo htmlspecialchars($_POST['attendance_date'] ?? date('Y-m-d')); ?>"/>
</div>
</div>
<div class="form-group col-md-6">
<label class="small text-uppercase font-weight-bold">Check-In Time <span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text"><i class="fa fa-clock-o"></i></span>
</div>
<input type="time" name="check_in_time" class="form-control" required value="<?php echo htmlspecialchars($_POST['check_in_time'] ?? ''); ?>"/>
</div>
</div>
<div class="form-group col-md-6">
<label class="small text-uppercase font-weight-bold">Check-Out Time <span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text"><i class="fa fa-sign-out"></i></span>
</div>
<input type="time" name="check_out_time" class="form-control" required value="<?php echo htmlspecialchars($_POST['check_out_time'] ?? ''); ?>"/>
</div>
</div>
<div class="form-group col-12">
<label class="small text-uppercase font-weight-bold">Address</label>
<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text"><i class="fa fa-map-marker"></i></span>
</div>
<textarea name="address" class="form-control" placeholder="Enter residential address" rows="3"><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>
</div>
</div>
</div>
<div class="d-flex justify-content-end">
<button type="reset" class="btn btn-outline-secondary mr-2">Reset Form</button>
<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Attendance</button>
</div>
</form>
</div>
</div>
</div>
<div class="col-lg-4">
<?php
$todayCount = $conn->query("SELECT COUNT(*) as c FROM attendance WHERE attendance_date=CURDATE()")->fetch_assoc()['c'];
?>
<div class="card">
<div class="card-body">
<h6 class="font-weight-bold mb-3">Quick Info</h6>
<div class="d-flex align-items-center justify-content-between p-2 bg-light rounded mb-2">
<div class="d-flex align-items-center">
<span class="d-inline-flex align-items-center justify-content-center rounded mr-2 text-white" style="width: 32px; height: 32px; background-color: #0086a0;"><i class="fa fa-check-square-o"></i></span>
<small>Records Today</small>
</div>
<span class="h5 font-weight-bold text-success mb-0"><?php echo $todayCount; ?></span>
</div>
<div class="d-flex align-items-center p-2 bg-light rounded">
<div class="d-flex align-items-center">
<span class="d-inline-flex align-items-center justify-content-center rounded mr-2 text-white" style="width: 32px; height: 32px; background-color: #575f67;"><i class="fa fa-info-circle"></i></span>
<small>All fields with * are required</small>
</div>
</div>
</div>
</div>
</div>
</div>
