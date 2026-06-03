<?php
$pageTitle = 'Attendance Data';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$searchCond = '';
if ($search !== '') {
    $searchCond = "WHERE name LIKE '%" . $conn->real_escape_string($search) . "%'";
}

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'attendance_date';
$order = isset($_GET['order']) ? $_GET['order'] : 'DESC';
$allowedSorts = ['name', 'attendance_date', 'check_in_time'];
if (!in_array($sort, $allowedSorts)) $sort = 'attendance_date';
if (!in_array(strtoupper($order), ['ASC', 'DESC'])) $order = 'DESC';
$nextOrder = strtoupper($order) === 'ASC' ? 'DESC' : 'ASC';
$sortIcon = strtoupper($order) === 'ASC' ? 'arrow_upward' : 'arrow_downward';

$pageNo = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$limit = 10;
$offset = ($pageNo - 1) * $limit;
$totalResult = $conn->query("SELECT COUNT(*) as count FROM attendance $searchCond");
$totalRows = $totalResult->fetch_assoc()['count'];
$totalPages = ceil($totalRows / $limit);

$result = $conn->query("SELECT * FROM attendance $searchCond ORDER BY $sort $order LIMIT $offset, $limit");

$todayDate = date('Y-m-d');
$totalRecords = $conn->query("SELECT COUNT(*) as count FROM attendance")->fetch_assoc()['count'];
$avgCheckIn = $conn->query("SELECT SEC_TO_TIME(AVG(TIME_TO_SEC(check_in_time))) as avg_time FROM attendance")->fetch_assoc()['avg_time'];
$lateToday = $conn->query("SELECT COUNT(*) as count FROM attendance WHERE attendance_date='$todayDate' AND check_in_time > '09:00:00'")->fetch_assoc()['count'];
?>
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
<div>
<h2 class="fw-bold mb-1" style="font-size: 22px; color: var(--on-surface);">Attendance Data</h2>
<p class="text-secondary mb-0" style="font-size: 14px;">View and manage daily employee attendance records.</p>
</div>
<a href="index.php?page=attendance-create" class="btn btn-backstrap btn-backstrap-primary d-flex align-items-center gap-1">
<span class="material-symbols-outlined" style="font-size: 18px;">add</span> Add Record
</a>
</div>

<?php if (isset($_GET['msg'])): ?>
<div class="alert alert-backstrap-success d-flex align-items-center gap-2 mb-4">
<span class="material-symbols-outlined">check_circle</span>
<span><?php echo htmlspecialchars($_GET['msg']); ?></span>
</div>
<?php endif; ?>

<div class="card-backstrap p-3 mb-4">
<form method="GET" action="index.php" class="row g-3 align-items-end">
<input type="hidden" name="page" value="attendance-list"/>
<div class="col-md-3">
<label class="form-label small text-uppercase fw-bold text-secondary">Search Name</label>
<div class="search-wrapper">
<span class="material-symbols-outlined search-icon">search</span>
<input type="text" name="search" class="form-control form-backstrap" placeholder="Search employee..." value="<?php echo htmlspecialchars($search); ?>"/>
</div>
</div>
<div class="col-md-2">
<label class="form-label small text-uppercase fw-bold text-secondary">Sort By</label>
<select name="sort" class="form-select form-backstrap">
<option value="attendance_date" <?php echo $sort == 'attendance_date' ? 'selected' : ''; ?>>Date</option>
<option value="name" <?php echo $sort == 'name' ? 'selected' : ''; ?>>Name</option>
<option value="check_in_time" <?php echo $sort == 'check_in_time' ? 'selected' : ''; ?>>Check-In Time</option>
</select>
</div>
<div class="col-md-2">
<label class="form-label small text-uppercase fw-bold text-secondary">Order</label>
<select name="order" class="form-select form-backstrap">
<option value="DESC" <?php echo $order == 'DESC' ? 'selected' : ''; ?>>Newest / Z-A</option>
<option value="ASC" <?php echo $order == 'ASC' ? 'selected' : ''; ?>>Oldest / A-Z</option>
</select>
</div>
<div class="col-md-2 d-flex gap-1">
<button type="submit" class="btn btn-backstrap btn-backstrap-outline flex-fill d-flex align-items-center justify-content-center gap-1">
<span class="material-symbols-outlined" style="font-size: 18px;">filter_list</span> Apply
</button>
<a href="index.php?page=attendance-list" class="btn btn-backstrap btn-backstrap-outline flex-fill d-flex align-items-center justify-content-center gap-1">
<span class="material-symbols-outlined" style="font-size: 18px;">clear</span> Reset
</a>
</div>
</form>
</div>

<div class="card-backstrap">
<div class="table-responsive">
<table class="table table-backstrap">
<thead>
<tr>
<th>#</th>
<th>
<a href="index.php?page=attendance-list&sort=name&order=<?php echo $sort === 'name' ? $nextOrder : 'ASC'; ?>&search=<?php echo urlencode($search); ?>" class="text-decoration-none d-flex align-items-center gap-1" style="color: inherit;">
Name <?php if ($sort === 'name'): ?><span class="material-symbols-outlined" style="font-size: 16px;"><?php echo $sortIcon; ?></span><?php endif; ?>
</a>
</th>
<th>Address</th>
<th>Gender</th>
<th>
<a href="index.php?page=attendance-list&sort=attendance_date&order=<?php echo $sort === 'attendance_date' ? $nextOrder : 'ASC'; ?>&search=<?php echo urlencode($search); ?>" class="text-decoration-none d-flex align-items-center gap-1" style="color: inherit;">
Date <?php if ($sort === 'attendance_date'): ?><span class="material-symbols-outlined" style="font-size: 16px;"><?php echo $sortIcon; ?></span><?php endif; ?>
</a>
</th>
<th>
<a href="index.php?page=attendance-list&sort=check_in_time&order=<?php echo $sort === 'check_in_time' ? $nextOrder : 'ASC'; ?>&search=<?php echo urlencode($search); ?>" class="text-decoration-none d-flex align-items-center gap-1" style="color: inherit;">
Check-In <?php if ($sort === 'check_in_time'): ?><span class="material-symbols-outlined" style="font-size: 16px;"><?php echo $sortIcon; ?></span><?php endif; ?>
</a>
</th>
<th>Check-Out</th>
<th class="text-center">Actions</th>
</tr>
</thead>
<tbody>
<?php if ($result->num_rows > 0): ?>
<?php $no = $offset + 1; ?>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
<td class="text-secondary"><?php echo $no++; ?></td>
<td>
<div class="d-flex align-items-center gap-2">
<div class="avatar <?php echo $row['gender'] === 'Male' ? 'bg-primary' : ''; ?>" style="<?php echo $row['gender'] === 'Male' ? '' : 'background-color: var(--tertiary-fixed); color: #004e5e;'; ?> <?php echo $row['gender'] === 'Male' ? 'color: #fff;' : ''; ?>"><?php echo strtoupper(substr($row['name'], 0, 2)); ?></div>
<span style="font-weight: 500;"><?php echo htmlspecialchars($row['name']); ?></span>
</div>
</td>
<td class="text-secondary"><?php echo htmlspecialchars($row['address']); ?></td>
<td><span class="<?php echo $row['gender'] === 'Male' ? 'gender-male' : 'gender-female'; ?>"><?php echo strtoupper($row['gender']); ?></span></td>
<td><?php echo date('M d, Y', strtotime($row['attendance_date'])); ?></td>
<td><?php echo date('h:i A', strtotime($row['check_in_time'])); ?></td>
<td><?php echo date('h:i A', strtotime($row['check_out_time'])); ?></td>
<td class="text-center">
<div class="d-flex justify-content-center gap-1">
<a href="index.php?page=attendance-edit&id=<?php echo $row['id']; ?>" class="material-symbols-outlined p-1 text-decoration-none action-edit" title="Edit">edit</a>
<a href="#" onclick="confirmDelete(<?php echo $row['id']; ?>)" class="material-symbols-outlined p-1 text-decoration-none action-delete" title="Delete">delete</a>
</div>
</td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
<td colspan="8" class="text-center py-4 text-secondary">No attendance records found.</td>
</tr>
<?php endif; ?>
</tbody>
</table>
</div>
<div class="d-flex justify-content-between align-items-center p-3 border-top" style="border-color: var(--outline-variant); background-color: var(--surface-container-low);">
<small class="text-secondary">Showing <?php echo $totalRows > 0 ? $offset + 1 : 0; ?> to <?php echo min($offset + $limit, $totalRows); ?> of <?php echo $totalRows; ?> entries</small>
<nav>
<ul class="pagination pagination-backstrap mb-0">
<li class="page-item <?php echo $pageNo <= 1 ? 'disabled' : ''; ?>">
<a class="page-link" href="index.php?page=attendance-list&p=<?php echo max(1, $pageNo-1); ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>&search=<?php echo urlencode($search); ?>">
<span class="material-symbols-outlined" style="font-size: 18px;">chevron_left</span>
</a>
</li>
<?php for ($i = 1; $i <= $totalPages; $i++): ?>
<li class="page-item <?php echo $i == $pageNo ? 'active' : ''; ?>">
<a class="page-link" href="index.php?page=attendance-list&p=<?php echo $i; ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
</li>
<?php endfor; ?>
<li class="page-item <?php echo $pageNo >= $totalPages ? 'disabled' : ''; ?>">
<a class="page-link" href="index.php?page=attendance-list&p=<?php echo min($totalPages, $pageNo+1); ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>&search=<?php echo urlencode($search); ?>">
<span class="material-symbols-outlined" style="font-size: 18px;">chevron_right</span>
</a>
</li>
</ul>
</nav>
</div>
</div>

<div class="row g-3 mt-3">
<div class="col-md-4">
<div class="summary-box" style="background-color: var(--primary-fixed); color: #001946;">
<div class="summary-icon" style="background-color: var(--primary-container); color: #fff;">
<span class="material-symbols-outlined">groups</span>
</div>
<div>
<div class="summary-label">Total Records</div>
<div class="summary-value"><?php echo $totalRecords; ?></div>
</div>
</div>
</div>
<div class="col-md-4">
<div class="summary-box" style="background-color: var(--tertiary-fixed); color: #004e5e;">
<div class="summary-icon" style="background-color: var(--tertiary-container); color: #fff;">
<span class="material-symbols-outlined">schedule</span>
</div>
<div>
<div class="summary-label">Avg. Check-In</div>
<div class="summary-value"><?php echo $avgCheckIn ? date('h:i A', strtotime($avgCheckIn)) : 'N/A'; ?></div>
</div>
</div>
</div>
<div class="col-md-4">
<div class="summary-box" style="background-color: var(--secondary-fixed); color: #141d23;">
<div class="summary-icon" style="background-color: var(--secondary-container); color: #fff;">
<span class="material-symbols-outlined">error_outline</span>
</div>
<div>
<div class="summary-label">Late Today</div>
<div class="summary-value" style="color: var(--bs-danger);"><?php echo $lateToday; ?></div>
</div>
</div>
</div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this attendance record?')) {
        window.location.href = 'index.php?page=attendance-delete&id=' + id;
    }
}
</script>
