<?php
// ============================================================
// update-task.php — Toggle Task Status (Pending ↔ Completed)
// Mini Task Management System
// ============================================================

session_start();
require_once '../includes/db.php';

// ── Only accept POST requests (prevents CSRF via URL) ──
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

// ── Validate task ID ──
$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

if ($id <= 0) {
    $_SESSION['errors'] = ['Invalid task ID.'];
    header('Location: ../index.php');
    exit;
}

// ── Toggle: Pending → Completed, Completed → Pending ──
$stmt = $conn->prepare(
    "UPDATE tasks
     SET status = IF(status = 'Pending', 'Completed', 'Pending')
     WHERE id = ?"
);
$stmt->bind_param('i', $id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    $_SESSION['success'] = 'Task status updated successfully.';
} else {
    $_SESSION['errors'] = ['Task not found or status could not be updated.'];
}

$stmt->close();
$conn->close();

header('Location: ../index.php');
exit;
