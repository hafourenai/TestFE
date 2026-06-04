<?php
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

$stmt = $conn->prepare("DELETE FROM attendance WHERE id=?");
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    $conn->query("UPDATE attendance SET id = id - 1 WHERE id > $id");
    $result = $conn->query("SELECT COALESCE(MAX(id), 0) + 1 AS next_id FROM attendance");
    $row = $result->fetch_assoc();
    $conn->query("ALTER TABLE attendance AUTO_INCREMENT = " . $row['next_id']);
    header('Location: index.php?page=attendance-list&msg=' . urlencode('Attendance record deleted successfully.'));
} else {
    header('Location: index.php?page=attendance-list&msg=' . urlencode('Failed to delete record.'));
}
$stmt->close();
exit;
