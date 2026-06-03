<?php
$pageTitle = 'Edit Attendance';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: index.php?page=attendance-list&msg=' . urlencode('Invalid record ID.'));
    exit;
}

$record = $conn->query("SELECT * FROM attendance WHERE id=$id");
if ($record->num_rows === 0) {
    header('Location: index.php?page=attendance-list&msg=' . urlencode('Record not found.'));
    exit;
}
$row = $record->fetch_assoc();

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
        $stmt = $conn->prepare("UPDATE attendance SET name=?, address=?, gender=?, attendance_date=?, check_in_time=?, check_out_time=? WHERE id=?");
        $stmt->bind_param("ssssssi", $name, $address, $gender, $attendance_date, $check_in_time, $check_out_time, $id);
        if ($stmt->execute()) {
            header('Location: index.php?page=attendance-list&msg=' . urlencode('Attendance record updated successfully.'));
            exit;
        } else {
            $error = 'Failed to update record: ' . $conn->error;
        }
        $stmt->close();
    }
}
?>
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
<div>
<h2 class="fw-bold mb-1" style="font-size: 22px; color: var(--on-surface);">Edit Attendance</h2>
<p class="text-secondary mb-0" style="font-size: 14px;">Update attendance record #<?php echo $id; ?>.</p>
</div>
<a href="index.php?page=attendance-list" class="btn btn-backstrap btn-backstrap-outline d-flex align-items-center gap-1">
<span class="material-symbols-outlined" style="font-size: 18px;">arrow_back</span> Back to List
</a>
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
<h5 class="mb-0" style="color: var(--bs-primary); font-size: 16px;">Edit Record #<?php echo $id; ?></h5>
</div>
<div class="card-body">
<form method="POST" action="index.php?page=attendance-edit&id=<?php echo $id; ?>">
<div class="row g-3">
<div class="col-md-6">
<label class="form-label small text-uppercase fw-bold">Employee Name <span class="text-danger">*</span></label>
<div class="search-wrapper">
<span class="material-symbols-outlined search-icon" style="font-size: 18px;">person</span>
<input type="text" name="name" class="form-control form-backstrap" placeholder="Enter full name" required value="<?php echo htmlspecialchars($row['name']); ?>" style="padding-left: 36px;"/>
</div>
</div>
<div class="col-md-6">
<label class="form-label small text-uppercase fw-bold">Gender <span class="text-danger">*</span></label>
<div class="search-wrapper">
<span class="material-symbols-outlined search-icon" style="font-size: 18px;">wc</span>
<select name="gender" class="form-select form-backstrap" required style="padding-left: 36px;">
<option value="Male" <?php echo $row['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
<option value="Female" <?php echo $row['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
</select>
</div>
</div>
<div class="col-md-6">
<label class="form-label small text-uppercase fw-bold">Attendance Date <span class="text-danger">*</span></label>
<div class="search-wrapper">
<span class="material-symbols-outlined search-icon" style="font-size: 18px;">calendar_month</span>
<input type="date" name="attendance_date" class="form-control form-backstrap" required value="<?php echo $row['attendance_date']; ?>" style="padding-left: 36px;"/>
</div>
</div>
<div class="col-md-6">
<label class="form-label small text-uppercase fw-bold">Check-In Time <span class="text-danger">*</span></label>
<div class="search-wrapper">
<span class="material-symbols-outlined search-icon" style="font-size: 18px;">schedule</span>
<input type="time" name="check_in_time" class="form-control form-backstrap" required value="<?php echo $row['check_in_time']; ?>" style="padding-left: 36px;"/>
</div>
</div>
<div class="col-md-6">
<label class="form-label small text-uppercase fw-bold">Check-Out Time <span class="text-danger">*</span></label>
<div class="search-wrapper">
<span class="material-symbols-outlined search-icon" style="font-size: 18px;">logout</span>
<input type="time" name="check_out_time" class="form-control form-backstrap" required value="<?php echo $row['check_out_time']; ?>" style="padding-left: 36px;"/>
</div>
</div>
<div class="col-12">
<label class="form-label small text-uppercase fw-bold">Address</label>
<div class="search-wrapper">
<span class="material-symbols-outlined search-icon" style="font-size: 18px;">location_on</span>
<textarea name="address" class="form-control form-backstrap" placeholder="Enter residential address" rows="3" style="padding-left: 36px;"><?php echo htmlspecialchars($row['address']); ?></textarea>
</div>
</div>
</div>
<div class="d-flex justify-content-end gap-2 mt-4">
<a href="index.php?page=attendance-list" class="btn btn-backstrap btn-backstrap-outline">Cancel</a>
<button type="submit" class="btn btn-backstrap btn-backstrap-primary">Update Attendance</button>
</div>
</form>
</div>
</div>
</div>
<div class="col-lg-4">
<div class="card-backstrap">
<div class="card-body">
<h6 class="fw-bold mb-3" style="color: var(--on-surface); font-size: 15px;">Record Info</h6>
<div class="d-flex align-items-center justify-content-between p-2 rounded mb-2" style="background-color: var(--surface-container-low); border: 1px solid var(--outline-variant);">
<div class="d-flex align-items-center gap-2">
<div class="d-flex align-items-center justify-content-center rounded" style="width: 32px; height: 32px; background-color: var(--tertiary-container);">
<span class="material-symbols-outlined text-white" style="font-size: 16px;">calendar_today</span>
</div>
<small>Created</small>
</div>
<small class="fw-bold"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></small>
</div>
<div class="d-flex align-items-center justify-content-between p-2 rounded" style="background-color: var(--surface-container-low); border: 1px solid var(--outline-variant);">
<div class="d-flex align-items-center gap-2">
<div class="d-flex align-items-center justify-content-center rounded" style="width: 32px; height: 32px; background-color: var(--secondary-container);">
<span class="material-symbols-outlined" style="font-size: 16px;">badge</span>
</div>
<small>Record ID</small>
</div>
<small class="fw-bold">#<?php echo $id; ?></small>
</div>
</div>
</div>
</div>
</div>
