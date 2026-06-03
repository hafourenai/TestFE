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
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
<div>
<h2 class="font-weight-bold mb-1">Edit Attendance</h2>
<p class="text-muted mb-0">Update attendance record #<?php echo $id; ?>.</p>
</div>
<a href="index.php?page=attendance-list" class="btn btn-outline-secondary mt-2 mt-md-0"><i class="fa fa-arrow-left"></i> Back to List</a>
</div>

<?php if ($error !== ''): ?>
<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="row">
<div class="col-lg-8">
<div class="card">
<div class="card-header">
<h5 class="mb-0 text-primary">Edit Record #<?php echo $id; ?></h5>
</div>
<div class="card-body">
<form method="POST" action="index.php?page=attendance-edit&id=<?php echo $id; ?>">
<div class="form-row">
<div class="form-group col-md-6">
<label class="small text-uppercase font-weight-bold">Employee Name <span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text"><i class="fa fa-user"></i></span>
</div>
<input type="text" name="name" class="form-control" placeholder="Enter full name" required value="<?php echo htmlspecialchars($row['name']); ?>"/>
</div>
</div>
<div class="form-group col-md-6">
<label class="small text-uppercase font-weight-bold">Gender <span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text"><i class="fa fa-venus-mars"></i></span>
</div>
<select name="gender" class="form-control" required>
<option value="Male" <?php echo $row['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
<option value="Female" <?php echo $row['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
</select>
</div>
</div>
<div class="form-group col-md-6">
<label class="small text-uppercase font-weight-bold">Attendance Date <span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text"><i class="fa fa-calendar"></i></span>
</div>
<input type="date" name="attendance_date" class="form-control" required value="<?php echo $row['attendance_date']; ?>"/>
</div>
</div>
<div class="form-group col-md-6">
<label class="small text-uppercase font-weight-bold">Check-In Time <span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text"><i class="fa fa-clock-o"></i></span>
</div>
<input type="time" name="check_in_time" class="form-control" required value="<?php echo $row['check_in_time']; ?>"/>
</div>
</div>
<div class="form-group col-md-6">
<label class="small text-uppercase font-weight-bold">Check-Out Time <span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text"><i class="fa fa-sign-out"></i></span>
</div>
<input type="time" name="check_out_time" class="form-control" required value="<?php echo $row['check_out_time']; ?>"/>
</div>
</div>
<div class="form-group col-12">
<label class="small text-uppercase font-weight-bold">Address</label>
<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text"><i class="fa fa-map-marker"></i></span>
</div>
<textarea name="address" class="form-control" placeholder="Enter residential address" rows="3"><?php echo htmlspecialchars($row['address']); ?></textarea>
</div>
</div>
</div>
<div class="d-flex justify-content-end">
<a href="index.php?page=attendance-list" class="btn btn-outline-secondary mr-2">Cancel</a>
<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update Attendance</button>
</div>
</form>
</div>
</div>
</div>
<div class="col-lg-4">
<div class="card">
<div class="card-body">
<h6 class="font-weight-bold mb-3">Record Info</h6>
<div class="d-flex align-items-center justify-content-between p-2 bg-light rounded mb-2">
<div class="d-flex align-items-center">
<span class="d-inline-flex align-items-center justify-content-center rounded mr-2 text-white" style="width: 32px; height: 32px; background-color: #0086a0;"><i class="fa fa-calendar"></i></span>
<small>Created</small>
</div>
<small class="font-weight-bold"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></small>
</div>
<div class="d-flex align-items-center justify-content-between p-2 bg-light rounded">
<div class="d-flex align-items-center">
<span class="d-inline-flex align-items-center justify-content-center rounded mr-2 text-white" style="width: 32px; height: 32px; background-color: #575f67;"><i class="fa fa-id-badge"></i></span>
<small>Record ID</small>
</div>
<small class="font-weight-bold">#<?php echo $id; ?></small>
</div>
</div>
</div>
</div>
</div>
