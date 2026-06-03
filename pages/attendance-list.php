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
$sortIcon = strtoupper($order) === 'ASC' ? 'fa-arrow-up' : 'fa-arrow-down';

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
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
<div>
<h2 class="font-weight-bold mb-1">Attendance Data</h2>
<p class="text-muted mb-0">View and manage daily employee attendance records.</p>
</div>
<a href="index.php?page=attendance-create" class="btn btn-primary mt-2 mt-md-0"><i class="fa fa-plus"></i> Add Record</a>
</div>

<?php if (isset($_GET['msg'])): ?>
<div class="alert alert-success alert-dismissible fade show">
<i class="fa fa-check-circle"></i> <?php echo htmlspecialchars($_GET['msg']); ?>
<button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<?php endif; ?>

<div class="card p-3 mb-4">
<form method="GET" action="index.php" class="form-inline">
<input type="hidden" name="page" value="attendance-list"/>
<div class="form-group mr-3 mb-2 mb-md-0">
<label class="small text-uppercase font-weight-bold text-muted mr-2">Search</label>
<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text"><i class="fa fa-search"></i></span>
</div>
<input type="text" name="search" class="form-control" placeholder="Search employee..." value="<?php echo htmlspecialchars($search); ?>"/>
</div>
</div>
<div class="form-group mr-3 mb-2 mb-md-0">
<label class="small text-uppercase font-weight-bold text-muted mr-2">Sort</label>
<select name="sort" class="form-control">
<option value="attendance_date" <?php echo $sort == 'attendance_date' ? 'selected' : ''; ?>>Date</option>
<option value="name" <?php echo $sort == 'name' ? 'selected' : ''; ?>>Name</option>
<option value="check_in_time" <?php echo $sort == 'check_in_time' ? 'selected' : ''; ?>>Check-In Time</option>
</select>
</div>
<div class="form-group mr-3 mb-2 mb-md-0">
<label class="small text-uppercase font-weight-bold text-muted mr-2">Order</label>
<select name="order" class="form-control">
<option value="DESC" <?php echo $order == 'DESC' ? 'selected' : ''; ?>>Newest / Z-A</option>
<option value="ASC" <?php echo $order == 'ASC' ? 'selected' : ''; ?>>Oldest / A-Z</option>
</select>
</div>
<button type="submit" class="btn btn-outline-primary mr-1"><i class="fa fa-filter"></i> Apply</button>
<a href="index.php?page=attendance-list" class="btn btn-outline-secondary"><i class="fa fa-times"></i> Reset</a>
</form>
</div>

<div class="card">
<div class="table-responsive">
<table class="table table-striped">
<thead>
<tr>
<th>#</th>
<th>
<a href="index.php?page=attendance-list&sort=name&order=<?php echo $sort === 'name' ? $nextOrder : 'ASC'; ?>&search=<?php echo urlencode($search); ?>" class="text-dark d-flex align-items-center">
Name <?php if ($sort === 'name'): ?><i class="fa <?php echo $sortIcon; ?> ml-1" style="font-size:12px;"></i><?php endif; ?>
</a>
</th>
<th>Address</th>
<th>Gender</th>
<th>
<a href="index.php?page=attendance-list&sort=attendance_date&order=<?php echo $sort === 'attendance_date' ? $nextOrder : 'ASC'; ?>&search=<?php echo urlencode($search); ?>" class="text-dark d-flex align-items-center">
Date <?php if ($sort === 'attendance_date'): ?><i class="fa <?php echo $sortIcon; ?> ml-1" style="font-size:12px;"></i><?php endif; ?>
</a>
</th>
<th>
<a href="index.php?page=attendance-list&sort=check_in_time&order=<?php echo $sort === 'check_in_time' ? $nextOrder : 'ASC'; ?>&search=<?php echo urlencode($search); ?>" class="text-dark d-flex align-items-center">
Check-In <?php if ($sort === 'check_in_time'): ?><i class="fa <?php echo $sortIcon; ?> ml-1" style="font-size:12px;"></i><?php endif; ?>
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
<td class="text-muted"><?php echo $no++; ?></td>
<td>
<div class="d-flex align-items-center">
<span class="avatar-circle mr-2" style="<?php echo $row['gender'] === 'Male' ? 'background-color:#0d6efd;' : 'background-color:#0086a0;'; ?>"><?php echo strtoupper(substr($row['name'], 0, 2)); ?></span>
<span class="font-weight-medium"><?php echo htmlspecialchars($row['name']); ?></span>
</div>
</td>
<td class="text-muted"><?php echo htmlspecialchars($row['address']); ?></td>
<td><span class="badge <?php echo $row['gender'] === 'Male' ? 'badge-primary' : 'badge-info'; ?>"><?php echo strtoupper($row['gender']); ?></span></td>
<td><?php echo date('M d, Y', strtotime($row['attendance_date'])); ?></td>
<td><?php echo date('H:i', strtotime($row['check_in_time'])); ?></td>
<td><?php echo date('H:i', strtotime($row['check_out_time'])); ?></td>
<td class="text-center">
<a href="index.php?page=attendance-edit&id=<?php echo $row['id']; ?>" class="text-primary mr-2" title="Edit"><i class="fa fa-pencil"></i></a>
<a href="#" onclick="confirmDelete(<?php echo $row['id']; ?>)" class="text-danger" title="Delete"><i class="fa fa-trash"></i></a>
</td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
<td colspan="8" class="text-center py-4 text-muted">No attendance records found.</td>
</tr>
<?php endif; ?>
</tbody>
</table>
</div>
<div class="d-flex justify-content-between align-items-center p-3 border-top">
<small class="text-muted">Showing <?php echo $totalRows > 0 ? $offset + 1 : 0; ?> to <?php echo min($offset + $limit, $totalRows); ?> of <?php echo $totalRows; ?> entries</small>
<nav>
<ul class="pagination mb-0">
<li class="page-item <?php echo $pageNo <= 1 ? 'disabled' : ''; ?>">
<a class="page-link" href="index.php?page=attendance-list&p=<?php echo max(1, $pageNo-1); ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>&search=<?php echo urlencode($search); ?>">
<i class="fa fa-chevron-left"></i>
</a>
</li>
<?php for ($i = 1; $i <= $totalPages; $i++): ?>
<li class="page-item <?php echo $i == $pageNo ? 'active' : ''; ?>">
<a class="page-link" href="index.php?page=attendance-list&p=<?php echo $i; ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
</li>
<?php endfor; ?>
<li class="page-item <?php echo $pageNo >= $totalPages ? 'disabled' : ''; ?>">
<a class="page-link" href="index.php?page=attendance-list&p=<?php echo min($totalPages, $pageNo+1); ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>&search=<?php echo urlencode($search); ?>">
<i class="fa fa-chevron-right"></i>
</a>
</li>
</ul>
</nav>
</div>
</div>

<div class="row mt-4">
<div class="col-md-4">
<div class="card summary-card text-white bg-primary">
<div class="card-body d-flex align-items-center">
<div class="mr-3"><i class="fa fa-users fa-2x"></i></div>
<div>
<div class="small text-uppercase">Total Records</div>
<div class="h3 font-weight-bold mb-0"><?php echo $totalRecords; ?></div>
</div>
</div>
</div>
</div>
<div class="col-md-4">
<div class="card summary-card text-white bg-info">
<div class="card-body d-flex align-items-center">
<div class="mr-3"><i class="fa fa-clock-o fa-2x"></i></div>
<div>
<div class="small text-uppercase">Avg. Check-In</div>
<div class="h3 font-weight-bold mb-0"><?php echo $avgCheckIn ? date('H:i', strtotime($avgCheckIn)) : 'N/A'; ?></div>
</div>
</div>
</div>
</div>
<div class="col-md-4">
<div class="card summary-card text-white bg-danger">
<div class="card-body d-flex align-items-center">
<div class="mr-3"><i class="fa fa-exclamation-circle fa-2x"></i></div>
<div>
<div class="small text-uppercase">Late Today</div>
<div class="h3 font-weight-bold mb-0"><?php echo $lateToday; ?></div>
</div>
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
