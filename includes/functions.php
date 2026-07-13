<?php
// ============================================================
// functions.php — Reusable Helper Functions
// Mini Task Management System
// ============================================================

require_once __DIR__ . '/db.php';

// ──────────────────────────────────────────────────────────
// Fetch all tasks from DB, latest first
// Returns an array of associative arrays
// ──────────────────────────────────────────────────────────
function getAllTasks(mysqli $conn): array {
    $result = $conn->query("SELECT * FROM tasks ORDER BY created_at DESC");

    if (!$result) {
        return [];
    }

    return $result->fetch_all(MYSQLI_ASSOC);
}

// ──────────────────────────────────────────────────────────
// Sanitize user input — strip tags + trim + encode
// ──────────────────────────────────────────────────────────
function sanitize(string $data): string {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

// ──────────────────────────────────────────────────────────
// Return CSS badge class based on priority
// ──────────────────────────────────────────────────────────
function priorityBadge(string $priority): string {
    return match ($priority) {
        'High'   => 'b-high',
        'Medium' => 'b-medium',
        'Low'    => 'b-low',
        default  => ''
    };
}

// ──────────────────────────────────────────────────────────
// Return CSS badge class based on status
// ──────────────────────────────────────────────────────────
function statusBadge(string $status): string {
    return $status === 'Completed' ? 'b-done' : 'b-pending';
}

// ──────────────────────────────────────────────────────────
// Return CSS dot class based on priority
// ──────────────────────────────────────────────────────────
function priorityDot(string $priority): string {
    return match ($priority) {
        'High'   => 'dot-high',
        'Medium' => 'dot-medium',
        'Low'    => 'dot-low',
        default  => ''
    };
}
