<?php
$totalRecords = $conn->query("SELECT COUNT(*) as count FROM attendance")->fetch_assoc()['count'];
$totalMale = $conn->query("SELECT COUNT(*) as count FROM attendance WHERE gender='Male'")->fetch_assoc()['count'];
$totalFemale = $conn->query("SELECT COUNT(*) as count FROM attendance WHERE gender='Female'")->fetch_assoc()['count'];
$todayDate = date('Y-m-d');
$attendanceToday = $conn->query("SELECT COUNT(*) as count FROM attendance WHERE attendance_date='$todayDate'")->fetch_assoc()['count'];
$recent = $conn->query("SELECT * FROM attendance ORDER BY created_at DESC LIMIT 5");
?>
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
<div>
<h2 class="font-weight-bold mb-1">Dashboard Overview</h2>
<p class="text-muted mb-0">Real-time attendance tracking and workforce analytics.</p>
</div>
<div class="d-flex mt-2 mt-md-0">
<a href="index.php?page=attendance-list" class="btn btn-outline-primary mr-2"><i class="fa fa-list-alt"></i> View Records</a>
<a href="index.php?page=attendance-create" class="btn btn-primary"><i class="fa fa-plus"></i> Add Record</a>
</div>
</div>

<div class="row mb-4">
<div class="col-sm-6 col-lg-3">
<div class="card">
<div class="card-body">
<div class="d-flex justify-content-between align-items-start mb-2">
<span class="stat-icon-box" style="background: #e8f0fe;"><i class="fa fa-users" style="color: #0d6efd;"></i></span>
<small class="text-muted font-weight-bold text-uppercase" style="font-size: 10px;">Total</small>
</div>
<div class="stat-label">Total Attendance Records</div>
<div class="stat-value"><?php echo $totalRecords; ?></div>
</div>
</div>
</div>
<div class="col-sm-6 col-lg-3">
<div class="card">
<div class="card-body">
<div class="d-flex justify-content-between align-items-start mb-2">
<span class="stat-icon-box" style="background: #e6f7ed;"><i class="fa fa-bar-chart" style="color: #198754;"></i></span>
<small class="text-muted font-weight-bold text-uppercase" style="font-size: 10px;">Male</small>
</div>
<div class="stat-label">Total Male Employees</div>
<div class="stat-value"><?php echo $totalMale; ?></div>
</div>
</div>
</div>
<div class="col-sm-6 col-lg-3">
<div class="card">
<div class="card-body">
<div class="d-flex justify-content-between align-items-start mb-2">
<span class="stat-icon-box" style="background: #f0f0f0;"><i class="fa fa-check-square-o" style="color: #6c757d;"></i></span>
<small class="text-muted font-weight-bold text-uppercase" style="font-size: 10px;">Female</small>
</div>
<div class="stat-label">Total Female Employees</div>
<div class="stat-value"><?php echo $totalFemale; ?></div>
</div>
</div>
</div>
<div class="col-sm-6 col-lg-3">
<div class="card">
<div class="card-body">
<div class="d-flex justify-content-between align-items-start mb-2">
<span class="stat-icon-box" style="background: #fde8e8;"><i class="fa fa-clock-o" style="color: #dc3545;"></i></span>
<small class="text-muted font-weight-bold text-uppercase" style="font-size: 10px;"><?php echo date('M d'); ?></small>
</div>
<div class="stat-label">Attendance Today</div>
<div class="stat-value"><?php echo $attendanceToday; ?></div>
</div>
</div>
</div>
</div>

<div class="card">
<div class="card-header d-flex justify-content-between align-items-center">
<h5 class="mb-0">Recent Attendance</h5>
<a href="index.php?page=attendance-list" class="text-primary font-weight-bold" style="font-size: 13px;">View All Records</a>
</div>
<div class="table-responsive">
<table class="table table-striped">
<thead>
<tr>
<th>Employee Name</th>
<th>Date</th>
<th>Check-In</th>
<th>Check-Out</th>
<th class="text-center">Actions</th>
</tr>
</thead>
<tbody>
<?php if ($recent->num_rows > 0): ?>
<?php while($row = $recent->fetch_assoc()): ?>
<tr>
<td>
<div class="d-flex align-items-center">
<span class="avatar-circle mr-2" style="background-color:#0d6efd;"><?php echo strtoupper(substr($row['name'], 0, 2)); ?></span>
<span class="font-weight-medium"><?php echo htmlspecialchars($row['name']); ?></span>
</div>
</td>
<td class="text-muted"><?php echo date('M d, Y', strtotime($row['attendance_date'])); ?></td>
<td><?php echo date('H:i', strtotime($row['check_in_time'])); ?></td>
<td><?php echo date('H:i', strtotime($row['check_out_time'])); ?></td>
<td class="text-center">
<a href="index.php?page=attendance-edit&id=<?php echo $row['id']; ?>" class="text-muted"><i class="fa fa-eye"></i></a>
</td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
<td colspan="5" class="text-center py-4 text-muted">No attendance records found.</td>
</tr>
<?php endif; ?>
</tbody>
</table>
</div>
</div>
