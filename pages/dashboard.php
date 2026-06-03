<?php
$totalRecords = $conn->query("SELECT COUNT(*) as count FROM attendance")->fetch_assoc()['count'];
$totalMale = $conn->query("SELECT COUNT(*) as count FROM attendance WHERE gender='Male'")->fetch_assoc()['count'];
$totalFemale = $conn->query("SELECT COUNT(*) as count FROM attendance WHERE gender='Female'")->fetch_assoc()['count'];
$todayDate = date('Y-m-d');
$attendanceToday = $conn->query("SELECT COUNT(*) as count FROM attendance WHERE attendance_date='$todayDate'")->fetch_assoc()['count'];
$recent = $conn->query("SELECT * FROM attendance ORDER BY created_at DESC LIMIT 5");
?>
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
<div>
<h2 class="fw-bold mb-1" style="font-size: 22px; color: var(--on-surface);">Dashboard Overview</h2>
<p class="text-secondary mb-0" style="font-size: 14px;">Real-time attendance tracking and workforce analytics.</p>
</div>
<div class="d-flex gap-2">
<a href="index.php?page=attendance-list" class="btn btn-backstrap btn-backstrap-outline d-flex align-items-center gap-1">
<span class="material-symbols-outlined" style="font-size: 18px;">list_alt</span> View Records
</a>
<a href="index.php?page=attendance-create" class="btn btn-backstrap btn-backstrap-primary d-flex align-items-center gap-1">
<span class="material-symbols-outlined" style="font-size: 18px;">add</span> Add Record
</a>
</div>
</div>

<div class="row g-3 mb-4">
<div class="col-sm-6 col-lg-3 d-flex">
<div class="stat-card h-100">
<div class="d-flex justify-content-between align-items-start mb-2">
<div class="stat-icon" style="background-color: rgba(0,87,205,0.08); color: var(--bs-primary);">
<span class="material-symbols-outlined">groups</span>
</div>
<small class="text-secondary fw-bold" style="font-size: 11px; text-transform: uppercase;">Total</small>
</div>
<div class="stat-label">Total Attendance Records</div>
<div class="stat-value"><?php echo $totalRecords; ?></div>
</div>
</div>
<div class="col-sm-6 col-lg-3 d-flex">
<div class="stat-card h-100">
<div class="d-flex justify-content-between align-items-start mb-2">
<div class="stat-icon" style="background-color: rgba(0,130,155,0.08); color: var(--bs-success);">
<span class="material-symbols-outlined">analytics</span>
</div>
<small class="text-secondary fw-bold" style="font-size: 11px; text-transform: uppercase;">Male</small>
</div>
<div class="stat-label">Total Male Employees</div>
<div class="stat-value"><?php echo $totalMale; ?></div>
</div>
</div>
<div class="col-sm-6 col-lg-3 d-flex">
<div class="stat-card h-100">
<div class="d-flex justify-content-between align-items-start mb-2">
<div class="stat-icon" style="background-color: rgba(87,95,103,0.1); color: var(--bs-secondary);">
<span class="material-symbols-outlined">how_to_reg</span>
</div>
<small class="text-secondary fw-bold" style="font-size: 11px; text-transform: uppercase;">Female</small>
</div>
<div class="stat-label">Total Female Employees</div>
<div class="stat-value"><?php echo $totalFemale; ?></div>
</div>
</div>
<div class="col-sm-6 col-lg-3 d-flex">
<div class="stat-card h-100">
<div class="d-flex justify-content-between align-items-start mb-2">
<div class="stat-icon" style="background-color: rgba(186,26,26,0.1); color: var(--bs-danger);">
<span class="material-symbols-outlined">alarm</span>
</div>
<small class="text-secondary fw-bold" style="font-size: 11px; text-transform: uppercase;"><?php echo date('M d'); ?></small>
</div>
<div class="stat-label">Attendance Today</div>
<div class="stat-value"><?php echo $attendanceToday; ?></div>
</div>
</div>
</div>

<div class="card-backstrap">
<div class="card-header d-flex justify-content-between align-items-center">
<h5 class="mb-0" style="font-size: 16px; color: var(--on-surface);">Recent Attendance</h5>
<a href="index.php?page=attendance-list" class="text-decoration-none" style="color: var(--bs-primary); font-size: 13px; font-weight: 500;">View All Records</a>
</div>
<div class="table-responsive">
<table class="table table-backstrap">
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
<div class="d-flex align-items-center gap-2">
<div class="avatar" style="background-color: rgba(0,87,205,0.08); color: var(--bs-primary);"><?php echo strtoupper(substr($row['name'], 0, 2)); ?></div>
<span style="font-weight: 500;"><?php echo htmlspecialchars($row['name']); ?></span>
</div>
</td>
<td class="text-secondary"><?php echo date('M d, Y', strtotime($row['attendance_date'])); ?></td>
<td><?php echo date('h:i A', strtotime($row['check_in_time'])); ?></td>
<td><?php echo date('h:i A', strtotime($row['check_out_time'])); ?></td>
<td class="text-center">
<a href="index.php?page=attendance-edit&id=<?php echo $row['id']; ?>" class="material-symbols-outlined text-decoration-none" style="color: var(--outline); font-size: 20px;">visibility</a>
</td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
<td colspan="5" class="text-center py-4 text-secondary">No attendance records found.</td>
</tr>
<?php endif; ?>
</tbody>
</table>
</div>
</div>
