<?php
$conn->query("CREATE TABLE IF NOT EXISTS settings (
    setting_key VARCHAR(50) PRIMARY KEY,
    setting_value VARCHAR(255) NOT NULL
)");
$conn->query("INSERT IGNORE INTO settings (setting_key, setting_value) VALUES ('late_threshold', '09:00:00')");

$currentThreshold = '';
$result = $conn->query("SELECT setting_value FROM settings WHERE setting_key='late_threshold'");
if ($result && $result->num_rows > 0) {
    $currentThreshold = $result->fetch_assoc()['setting_value'];
}

$saved = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['late_threshold'])) {
    $newVal = trim($_POST['late_threshold']);
    if (preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $newVal)) {
        if (strlen($newVal) === 5) $newVal .= ':00';
        $stmt = $conn->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('late_threshold', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->bind_param("ss", $newVal, $newVal);
        $stmt->execute();
        $currentThreshold = $newVal;
        $saved = 'Late threshold updated successfully.';
    } else {
        $saved = 'Invalid time format. Use HH:MM or HH:MM:SS.';
    }
}
?>
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
<div>
<h2 class="font-weight-bold mb-1">Settings</h2>
<p class="text-muted mb-0">Configure application settings.</p>
</div>
</div>

<?php if ($saved): ?>
<div class="alert <?php echo strpos($saved, 'successfully') !== false ? 'alert-success' : 'alert-danger'; ?> alert-dismissible fade show">
<i class="fa <?php echo strpos($saved, 'successfully') !== false ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i> <?php echo $saved; ?>
<button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<?php endif; ?>

<div class="row">
<div class="col-lg-6">
<div class="card">
<div class="card-header">
<h5 class="mb-0"><i class="fa fa-clock-o mr-2"></i>Attendance Rules</h5>
</div>
<div class="card-body">
<form method="POST" action="">
<div class="form-group">
<label class="form-label">Late Check-In Threshold</label>
<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text"><i class="fa fa-hourglass-end"></i></span>
</div>
<input type="text" name="late_threshold" class="form-control" value="<?php echo htmlspecialchars($currentThreshold ?: '09:00:00'); ?>" placeholder="09:00:00" required/>
</div>
<small class="form-text text-muted mt-2">
Employees who check in after this time will be marked as late. Format: <code>HH:MM:SS</code> (e.g. <code>09:00:00</code>).
</small>
</div>
<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Changes</button>
</form>
</div>
</div>
</div>
<div class="col-lg-6">
<div class="card">
<div class="card-header">
<h5 class="mb-0"><i class="fa fa-info-circle mr-2"></i>Current Configuration</h5>
</div>
<div class="card-body">
<p class="mb-1"><strong>Late Threshold:</strong></p>
<p class="h3 font-weight-bold text-primary"><?php echo htmlspecialchars($currentThreshold ?: '09:00:00'); ?></p>
<p class="text-muted mt-3 mb-0">
<i class="fa fa-lightbulb-o"></i> This setting determines the cut-off time for daily attendance. Any check-in after this time counts as late in the Attendance Data report.
</p>
</div>
</div>
</div>
</div>
