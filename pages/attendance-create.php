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
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
<div>
<h2 class="fw-bold mb-1" style="font-size: 22px; color: var(--on-surface);">Add New Attendance</h2>
<p class="text-secondary mb-0" style="font-size: 14px;">Log daily attendance records for staff members efficiently.</p>
</div>
</div>

<?php if ($error !== ''): ?>
<div class="alert alert-backstrap-error d-flex align-items-center gap-2 mb-4">
<span class="material-symbols-outlined">error</span>
<span><?php echo htmlspecialchars($error); ?></span>
</div>
<?php endif; ?>

<div class="row g-4">
<div class="col-lg-8">
<div class="card-backstrap">
<div class="card-header">
<h5 class="mb-0" style="color: var(--bs-primary); font-size: 16px;">Attendance Record Form</h5>
</div>
<div class="card-body">
<form method="POST" action="index.php?page=attendance-create">
<div class="row g-3">
<div class="col-md-6">
<label class="form-label small text-uppercase fw-bold">Employee Name <span class="text-danger">*</span></label>
<div class="search-wrapper">
<span class="material-symbols-outlined search-icon" style="font-size: 18px;">person</span>
<input type="text" name="name" class="form-control form-backstrap" placeholder="Enter full name" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" style="padding-left: 36px;"/>
</div>
</div>
<div class="col-md-6">
<label class="form-label small text-uppercase fw-bold">Gender <span class="text-danger">*</span></label>
<div class="search-wrapper">
<span class="material-symbols-outlined search-icon" style="font-size: 18px;">wc</span>
<select name="gender" class="form-select form-backstrap" required style="padding-left: 36px;">
<option value="" disabled selected>Select Gender</option>
<option value="Male" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
<option value="Female" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
</select>
</div>
</div>
<div class="col-md-6">
<label class="form-label small text-uppercase fw-bold">Attendance Date <span class="text-danger">*</span></label>
<div class="search-wrapper">
<span class="material-symbols-outlined search-icon" style="font-size: 18px;">calendar_month</span>
<input type="date" name="attendance_date" class="form-control form-backstrap" required value="<?php echo htmlspecialchars($_POST['attendance_date'] ?? date('Y-m-d')); ?>" style="padding-left: 36px;"/>
</div>
</div>
<div class="col-md-6">
<label class="form-label small text-uppercase fw-bold">Check-In Time <span class="text-danger">*</span></label>
<div class="search-wrapper">
<span class="material-symbols-outlined search-icon" style="font-size: 18px;">schedule</span>
<input type="time" name="check_in_time" class="form-control form-backstrap" required value="<?php echo htmlspecialchars($_POST['check_in_time'] ?? ''); ?>" style="padding-left: 36px;"/>
</div>
</div>
<div class="col-md-6">
<label class="form-label small text-uppercase fw-bold">Check-Out Time <span class="text-danger">*</span></label>
<div class="search-wrapper">
<span class="material-symbols-outlined search-icon" style="font-size: 18px;">logout</span>
<input type="time" name="check_out_time" class="form-control form-backstrap" required value="<?php echo htmlspecialchars($_POST['check_out_time'] ?? ''); ?>" style="padding-left: 36px;"/>
</div>
</div>
<div class="col-12">
<label class="form-label small text-uppercase fw-bold">Address</label>
<div class="search-wrapper">
<span class="material-symbols-outlined search-icon" style="font-size: 18px;">location_on</span>
<textarea name="address" class="form-control form-backstrap" placeholder="Enter residential address" rows="3" style="padding-left: 36px;"><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>
</div>
</div>
</div>
<div class="d-flex justify-content-end gap-2 mt-4">
<button type="reset" class="btn btn-backstrap btn-backstrap-outline">Reset Form</button>
<button type="submit" class="btn btn-backstrap btn-backstrap-primary">Save Attendance</button>
</div>
</form>
</div>
</div>
</div>
<div class="col-lg-4">
<?php
$todayCount = $conn->query("SELECT COUNT(*) as c FROM attendance WHERE attendance_date=CURDATE()")->fetch_assoc()['c'];
?>
<div class="card-backstrap">
<div class="card-body">
<h6 class="fw-bold mb-3" style="color: var(--on-surface); font-size: 15px;">Quick Info</h6>
<div class="d-flex align-items-center justify-content-between p-2 rounded mb-2" style="background-color: var(--surface-container-low); border: 1px solid var(--outline-variant);">
<div class="d-flex align-items-center gap-2">
<div class="d-flex align-items-center justify-content-center rounded" style="width: 32px; height: 32px; background-color: var(--tertiary-container);">
<span class="material-symbols-outlined text-white" style="font-size: 16px;">done_all</span>
</div>
<small>Records Today</small>
</div>
<span class="fw-bold" style="font-size: 18px; color: var(--bs-success);"><?php echo $todayCount; ?></span>
</div>
<div class="d-flex align-items-center p-2 rounded" style="background-color: var(--surface-container-low); border: 1px solid var(--outline-variant);">
<div class="d-flex align-items-center gap-2">
<div class="d-flex align-items-center justify-content-center rounded" style="width: 32px; height: 32px; background-color: var(--secondary-container);">
<span class="material-symbols-outlined" style="font-size: 16px;">info</span>
</div>
<small>All fields with * are required</small>
</div>
</div>
</div>
</div>
</div>
</div>
