<?php
$pageTitle = 'Attendance Data';

$checkCol = $conn->query("SHOW COLUMNS FROM attendance LIKE 'updated_at'");
if ($checkCol->num_rows === 0) {
    $conn->query("ALTER TABLE attendance ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER created_at");
}

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
$lateThreshold = '09:00:00';
$settingsResult = $conn->query("SELECT setting_value FROM settings WHERE setting_key='late_threshold'");
if ($settingsResult && $settingsResult->num_rows > 0) {
    $lateThreshold = $settingsResult->fetch_assoc()['setting_value'];
}

$totalResult = $conn->query("SELECT COUNT(*) as count FROM attendance $searchCond");
$totalRows = $totalResult->fetch_assoc()['count'];
$totalPages = ceil($totalRows / $limit);

$result = $conn->query("SELECT * FROM attendance $searchCond ORDER BY $sort $order LIMIT $offset, $limit");

$todayDate = date('Y-m-d');
$totalRecords = $conn->query("SELECT COUNT(*) as count FROM attendance")->fetch_assoc()['count'];
$avgCheckIn = $conn->query("SELECT SEC_TO_TIME(AVG(TIME_TO_SEC(check_in_time))) as avg_time FROM attendance")->fetch_assoc()['avg_time'];
$lateToday = $conn->query("SELECT COUNT(*) as count FROM attendance WHERE attendance_date='$todayDate' AND check_in_time > '$lateThreshold'")->fetch_assoc()['count'];

$hasFilters = $search !== '' || $sort !== 'attendance_date' || $order !== 'DESC';
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

<div class="card mb-4">
<div class="card-body py-3">
<form method="GET" action="index.php" class="d-flex flex-wrap align-items-end">
<input type="hidden" name="page" value="attendance-list"/>
<input type="hidden" name="p" value="1"/>
<div class="mr-3 mb-2 mb-sm-0">
<label class="d-block text-uppercase font-weight-bold text-muted" style="font-size:0.7rem;line-height:1;margin-bottom:4px;">Search</label>
<div class="input-group input-group-sm">
<div class="input-group-prepend">
<span class="input-group-text"><i class="fa fa-search"></i></span>
</div>
<input type="text" name="search" class="form-control" placeholder="Employee name..." value="<?php echo htmlspecialchars($search); ?>" style="min-width:150px;"/>
</div>
</div>
<div class="mr-3 mb-2 mb-sm-0">
<label class="d-block text-uppercase font-weight-bold text-muted" style="font-size:0.7rem;line-height:1;margin-bottom:4px;">Sort By</label>
<select name="sort" class="form-control form-control-sm" style="min-width:140px;">
<option value="attendance_date" <?php echo $sort == 'attendance_date' ? 'selected' : ''; ?>>Date</option>
<option value="name" <?php echo $sort == 'name' ? 'selected' : ''; ?>>Name</option>
<option value="check_in_time" <?php echo $sort == 'check_in_time' ? 'selected' : ''; ?>>Check In</option>
</select>
</div>
<div class="mr-3 mb-2 mb-sm-0">
<label class="d-block text-uppercase font-weight-bold text-muted" style="font-size:0.7rem;line-height:1;margin-bottom:4px;">Order</label>
<select name="order" class="form-control form-control-sm" style="min-width:140px;">
<option value="DESC" <?php echo $order == 'DESC' ? 'selected' : ''; ?>>Newest</option>
<option value="ASC" <?php echo $order == 'ASC' ? 'selected' : ''; ?>>Oldest</option>
</select>
</div>
<div class="mr-2 mb-2 mb-sm-0">
<button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Apply</button>
</div>
<div class="mb-2 mb-sm-0">
<a href="index.php?page=attendance-list" class="btn btn-outline-secondary btn-sm"><i class="fa fa-times"></i> Reset</a>
</div>
</form>
</div>
</div>

<?php if ($hasFilters): ?>
<div class="mb-3 d-flex flex-wrap align-items-center">
<small class="text-muted mr-2 font-weight-bold">Active Filters:</small>
<?php if ($search !== ''): ?>
<a href="index.php?page=attendance-list&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>" class="badge badge-primary mr-1 mb-1 d-inline-flex align-items-center" style="padding:0.35rem 0.6rem;font-size:0.75rem;">
<i class="fa fa-search mr-1"></i> "<?php echo htmlspecialchars($search); ?>"
<i class="fa fa-times ml-1" style="font-size:0.6rem;"></i>
</a>
<?php endif; ?>
<?php if ($sort !== 'attendance_date' || $order !== 'DESC'): ?>
<span class="badge badge-info mr-1 mb-1 d-inline-flex align-items-center" style="padding:0.35rem 0.6rem;font-size:0.75rem;">
<i class="fa fa-sort mr-1"></i> <?php echo ucfirst($sort); ?> (<?php echo strtoupper($order); ?>)
</span>
<?php endif; ?>
<a href="index.php?page=attendance-list" class="text-danger ml-1 small font-weight-bold"><i class="fa fa-times-circle"></i> Clear all</a>
</div>
<?php endif; ?>

<div class="card">
<div class="table-responsive">
<table class="table table-hover">
<thead>
<tr>
<th>#</th>
<th>
<a href="index.php?page=attendance-list&sort=name&order=<?php echo $sort === 'name' ? $nextOrder : 'ASC'; ?>&search=<?php echo urlencode($search); ?>" class="text-dark d-flex align-items-center">
Name <?php if ($sort === 'name'): ?><i class="fa <?php echo $sortIcon; ?> ml-1" style="font-size:11px;"></i><?php endif; ?>
</a>
</th>
<th>Address</th>
<th>Gender</th>
<th>
<a href="index.php?page=attendance-list&sort=attendance_date&order=<?php echo $sort === 'attendance_date' ? $nextOrder : 'ASC'; ?>&search=<?php echo urlencode($search); ?>" class="text-dark d-flex align-items-center">
Date <?php if ($sort === 'attendance_date'): ?><i class="fa <?php echo $sortIcon; ?> ml-1" style="font-size:11px;"></i><?php endif; ?>
</a>
</th>
<th>
<a href="index.php?page=attendance-list&sort=check_in_time&order=<?php echo $sort === 'check_in_time' ? $nextOrder : 'ASC'; ?>&search=<?php echo urlencode($search); ?>" class="text-dark d-flex align-items-center">
Check-In <?php if ($sort === 'check_in_time'): ?><i class="fa <?php echo $sortIcon; ?> ml-1" style="font-size:11px;"></i><?php endif; ?>
</a>
</th>
<th>Check-Out</th>
<th>Created</th>
<th>Updated</th>
<th class="text-center">Actions</th>
</tr>
</thead>
<tbody>
<?php if ($result->num_rows > 0): ?>
<?php $no = $offset + 1; ?>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
<td class="text-muted font-weight-bold"><?php echo $no++; ?></td>
<td>
<div class="d-flex align-items-center">
<span class="avatar-circle mr-2" style="<?php echo $row['gender'] === 'Male' ? 'background-color:#0d6efd;' : 'background-color:#0086a0;'; ?>"><?php echo strtoupper(substr($row['name'], 0, 2)); ?></span>
<span class="font-weight-medium"><?php echo htmlspecialchars($row['name']); ?></span>
</div>
</td>
<td class="text-muted small"><?php echo htmlspecialchars($row['address']); ?></td>
<td><span class="badge <?php echo $row['gender'] === 'Male' ? 'badge-primary' : 'badge-info'; ?>"><?php echo strtoupper($row['gender']); ?></span></td>
<td class="text-nowrap"><?php echo date('M d, Y', strtotime($row['attendance_date'])); ?></td>
<td class="text-nowrap"><?php echo date('H:i', strtotime($row['check_in_time'])); ?></td>
<td class="text-nowrap"><?php echo date('H:i', strtotime($row['check_out_time'])); ?></td>
<td class="small text-nowrap"><span class="text-muted"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></span></td>
<td class="small text-nowrap">
<?php if (strtotime($row['updated_at']) > strtotime($row['created_at']) + 2): ?>
<span class="text-muted"><?php echo date('M d, Y', strtotime($row['updated_at'])); ?></span>
<?php else: ?>
<span class="text-muted" style="font-style:italic;">never</span>
<?php endif; ?>
</td>
<td class="text-center text-nowrap">
<a href="index.php?page=attendance-edit&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary mr-1" title="Edit"><i class="fa fa-pencil"></i></a>
<a href="#" onclick="confirmDelete(<?php echo $row['id']; ?>)" class="btn btn-sm btn-outline-danger" title="Delete"><i class="fa fa-trash"></i></a>
</td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
<td colspan="10" class="text-center py-5">
<div class="text-muted">
<i class="fa fa-inbox fa-3x mb-3" style="opacity:0.4;"></i>
<p class="mb-0">No attendance records found.</p>
<?php if ($search !== ''): ?>
<small>Try adjusting your search criteria.</small>
<?php endif; ?>
</div>
</td>
</tr>
<?php endif; ?>
</tbody>
</table>
</div>
<div class="d-flex justify-content-between align-items-center p-3 border-top">
<small class="text-muted">Showing <?php echo $totalRows > 0 ? $offset + 1 : 0; ?> to <?php echo min($offset + $limit, $totalRows); ?> of <?php echo $totalRows; ?> entries</small>
<nav>
<ul class="pagination pagination-sm mb-0">
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
<div class="card border-0 summary-card" style="background: linear-gradient(135deg, #0d6efd, #0a58ca);">
<div class="card-body d-flex align-items-center text-white">
<div class="mr-3 d-flex align-items-center justify-content-center rounded" style="width:48px;height:48px;background:rgba(255,255,255,0.15);">
<i class="fa fa-users fa-lg"></i>
</div>
<div>
<div class="small text-uppercase" style="opacity:0.85;">Total Records</div>
<div class="h3 font-weight-bold mb-0"><?php echo $totalRecords; ?></div>
</div>
</div>
</div>
</div>
<div class="col-md-4">
<div class="card border-0 summary-card" style="background: linear-gradient(135deg, #0dcaf0, #0aa2c0);">
<div class="card-body d-flex align-items-center text-white">
<div class="mr-3 d-flex align-items-center justify-content-center rounded" style="width:48px;height:48px;background:rgba(255,255,255,0.15);">
<i class="fa fa-clock-o fa-lg"></i>
</div>
<div>
<div class="small text-uppercase" style="opacity:0.85;">Avg. Check-In</div>
<div class="h3 font-weight-bold mb-0"><?php echo $avgCheckIn ? date('H:i', strtotime($avgCheckIn)) : 'N/A'; ?></div>
</div>
</div>
</div>
</div>
<div class="col-md-4">
<div class="card border-0 summary-card" style="background: linear-gradient(135deg, #dc3545, #b02a37);">
<div class="card-body d-flex align-items-center text-white">
<div class="mr-3 d-flex align-items-center justify-content-center rounded" style="width:48px;height:48px;background:rgba(255,255,255,0.15);">
<i class="fa fa-exclamation-circle fa-lg"></i>
</div>
<div>
<div class="small text-uppercase" style="opacity:0.85;">Late Today (after <?php echo date('H:i', strtotime($lateThreshold)); ?>)</div>
<div class="h3 font-weight-bold mb-0"><?php echo $lateToday; ?></div>
</div>
</div>
</div>
</div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered" role="document">
<div class="modal-content border-0" style="border-radius:12px;box-shadow:0 8px 32px rgba(0,0,0,0.12);">
<div class="modal-body text-center py-5">
<div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width:64px;height:64px;background:#fce4ec;">
<i class="fa fa-trash text-danger" style="font-size:1.5rem;"></i>
</div>
<h5 class="font-weight-bold mb-2">Confirm Delete</h5>
<p class="text-muted mb-0">Are you sure you want to delete this attendance record? This action cannot be undone.</p>
</div>
<div class="modal-footer border-0 justify-content-center pb-4 pt-0">
<button type="button" class="btn btn-outline-secondary px-4" data-dismiss="modal">Cancel</button>
<a href="#" id="confirmDeleteBtn" class="btn btn-danger px-4"><i class="fa fa-trash"></i> Delete</a>
</div>
</div>
</div>
</div>

<script>
var deleteId = null;
function confirmDelete(id) {
    deleteId = id;
    $('#deleteModal').modal('show');
}
$(document).ready(function() {
    $('#confirmDeleteBtn').on('click', function(e) {
        e.preventDefault();
        if (deleteId) {
            window.location.href = 'index.php?page=attendance-delete&id=' + deleteId;
        }
    });
});
</script>
