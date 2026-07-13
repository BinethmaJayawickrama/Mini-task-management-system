<?php
session_start();
require_once '../includes/db.php';

// ── Only accept POST requests (prevents accidental deletion via URL) ──
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

// ── Validate task ID ──
$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

if ($id <= 0) {
    $_SESSION['errors'] = ['Invalid task ID provided.'];
    header('Location: ../index.php');
    exit;
}

// ── Delete using prepared statement ──
$stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
$stmt->bind_param('i', $id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    $_SESSION['success'] = 'Task deleted successfully.';
} else {
    $_SESSION['errors'] = ['Task not found or could not be deleted.'];
}

$stmt->close();
$conn->close();

header('Location: ../index.php');
exit;
