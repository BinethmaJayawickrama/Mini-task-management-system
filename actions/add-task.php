<?php
session_start();
require_once '../includes/db.php';

// ── Only accept POST requests ──
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

// ── Retrieve raw inputs ──
$title       = trim($_POST['title']       ?? '');
$description = trim($_POST['description'] ?? '');
$priority    = trim($_POST['priority']    ?? '');

// ── Server-side Validation ──
$errors = [];

if ($title === '') {
    $errors[] = 'Task title is required.';
} elseif (strlen($title) < 3) {
    $errors[] = 'Task title must be at least 3 characters long.';
} elseif (strlen($title) > 255) {
    $errors[] = 'Task title must not exceed 255 characters.';
}

if ($description === '') {
    $errors[] = 'Task description is required.';
}

$allowedPriorities = ['Low', 'Medium', 'High'];
if (!in_array($priority, $allowedPriorities, true)) {
    $errors[] = 'Please select a valid priority (Low, Medium, or High).';
}

// ── If validation fails, redirect back with errors ──
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header('Location: ../index.php');
    exit;
}

// ── Insert using prepared statement (prevents SQL injection) ──
$stmt = $conn->prepare(
    "INSERT INTO tasks (title, description, priority, status)
     VALUES (?, ?, ?, 'Pending')"
);
$stmt->bind_param('sss', $title, $description, $priority);

if ($stmt->execute()) {
    $_SESSION['success'] = 'Task "' . htmlspecialchars($title) . '" added successfully!';
} else {
    $_SESSION['errors'] = ['Failed to add task. Please try again.'];
}

$stmt->close();
$conn->close();

header('Location: ../index.php');
exit;
