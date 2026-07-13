<?php
// ============================================================
// db.php — Database Connection
// Mini Task Management System
// ============================================================

// ── Connection credentials ──
define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // Change if your MySQL username is different
define('DB_PASS', '');           // Change if your MySQL password is set
define('DB_NAME', 'intern_task_system');

// ── Create MySQLi connection ──
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// ── Check connection ──
if ($conn->connect_error) {
    // Stop execution and show a friendly error
    die('
        <div style="font-family:sans-serif;max-width:500px;margin:60px auto;padding:24px;
                    border:1px solid #fca5a5;border-radius:10px;background:#fee2e2;color:#991b1b;">
            <strong>&#9888; Database Connection Failed</strong><br><br>
            ' . htmlspecialchars($conn->connect_error) . '<br><br>
            <small>Check your credentials in <code>includes/db.php</code> and make sure MySQL is running in XAMPP.</small>
        </div>
    ');
}

// ── Set character encoding ──
$conn->set_charset('utf8mb4');
