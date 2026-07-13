<?php
// ============================================================
// index.php — Main Dashboard
// Mini Task Management System
// ============================================================

session_start();

// Retrieve and clear session flash messages (set by backend actions)
$success = $_SESSION['success'] ?? null;
$errors  = $_SESSION['errors']  ?? [];
unset($_SESSION['success'], $_SESSION['errors']);

// ── Temporary static tasks for frontend preview ──
// (Replace with DB fetch once backend is wired up)
$tasks = [
    [
        'id'          => 1,
        'title'       => 'Design the dashboard UI',
        'description' => 'Create a responsive layout using HTML, CSS.',
        'priority'    => 'High',
        'status'      => 'Completed',
        'created_at'  => '2026-07-13 08:00:00',
    ],
    [
        'id'          => 2,
        'title'       => 'Set up database connection',
        'description' => 'Configure MySQLi connection in db.php.',
        'priority'    => 'High',
        'status'      => 'Pending',
        'created_at'  => '2026-07-13 09:00:00',
    ],
    [
        'id'          => 3,
        'title'       => 'Implement add task feature',
        'description' => 'Build form and PHP backend to insert tasks.',
        'priority'    => 'Medium',
        'status'      => 'Pending',
        'created_at'  => '2026-07-13 09:30:00',
    ],
    [
        'id'          => 4,
        'title'       => 'Write README documentation',
        'description' => 'Add setup instructions and project overview.',
        'priority'    => 'Low',
        'status'      => 'Pending',
        'created_at'  => '2026-07-13 10:00:00',
    ],
];

// Stats counters
$totalTasks     = count($tasks);
$pendingTasks   = count(array_filter($tasks, fn($t) => $t['status'] === 'Pending'));
$completedTasks = count(array_filter($tasks, fn($t) => $t['status'] === 'Completed'));

// ── Helper: badge CSS class by priority ──
function priorityBadge(string $p): string {
    return match($p) {
        'High'   => 'badge-high',
        'Medium' => 'badge-medium',
        'Low'    => 'badge-low',
        default  => ''
    };
}

// ── Helper: badge CSS class by status ──
function statusBadge(string $s): string {
    return $s === 'Completed' ? 'badge-completed' : 'badge-pending';
}

// ── Helper: priority dot class ──
function priorityDot(string $p): string {
    return match($p) {
        'High'   => 'dot-high',
        'Medium' => 'dot-medium',
        'Low'    => 'dot-low',
        default  => ''
    };
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Mini Task Management System — Add, track, and manage your tasks easily.">
    <title>Task Manager — Mini Task Management System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- ============================================================
     HEADER
============================================================ -->
<header class="site-header">
    <div class="header-brand">
        <div class="logo-icon">📋</div>
        <h1>Task Manager</h1>
    </div>
    <span class="header-badge" id="headerBadge">
        <?= $totalTasks ?> Task<?= $totalTasks !== 1 ? 's' : '' ?>
    </span>
</header>

<!-- ============================================================
     MAIN CONTENT
============================================================ -->
<main class="container">

    <!-- Flash Messages -->
    <?php if ($success): ?>
        <div class="alert alert-success" role="alert">
            <span class="alert-icon">✅</span>
            <?= htmlspecialchars($success) ?>
            <button class="alert-close" aria-label="Close">✕</button>
        </div>
    <?php endif; ?>

    <?php foreach ($errors as $error): ?>
        <div class="alert alert-error" role="alert">
            <span class="alert-icon">⚠️</span>
            <?= htmlspecialchars($error) ?>
            <button class="alert-close" aria-label="Close">✕</button>
        </div>
    <?php endforeach; ?>

    <!-- Stats Bar -->
    <div class="stats-bar">
        <div class="stat-card stat-total">
            <div class="stat-value"><?= $totalTasks ?></div>
            <div class="stat-label">Total Tasks</div>
        </div>
        <div class="stat-card stat-pending">
            <div class="stat-value"><?= $pendingTasks ?></div>
            <div class="stat-label">Pending</div>
        </div>
        <div class="stat-card stat-done">
            <div class="stat-value"><?= $completedTasks ?></div>
            <div class="stat-label">Completed</div>
        </div>
    </div>

    <!-- Two-column Grid: Form | Task List -->
    <div class="grid-2">

        <!-- ================================================
             LEFT — ADD TASK FORM
        ================================================ -->
        <section class="card" aria-labelledby="formHeading">
            <div class="card-header">
                <h2 id="formHeading">➕ Add New Task</h2>
            </div>
            <div class="card-body">
                <form id="taskForm" action="actions/add-task.php" method="POST" novalidate>

                    <!-- Title -->
                    <div class="form-group">
                        <label for="title">Task Title <span style="color:var(--danger)">*</span></label>
                        <input
                            type="text"
                            id="title"
                            name="title"
                            class="form-control"
                            placeholder="e.g. Design landing page"
                            maxlength="255"
                            autocomplete="off"
                        >
                        <span class="invalid-feedback" id="titleError"></span>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="description">Description <span style="color:var(--danger)">*</span></label>
                        <textarea
                            id="description"
                            name="description"
                            class="form-control"
                            placeholder="Briefly describe the task..."
                            rows="4"
                        ></textarea>
                        <span class="invalid-feedback" id="descError"></span>
                    </div>

                    <!-- Priority -->
                    <div class="form-group">
                        <label for="priority">Priority <span style="color:var(--danger)">*</span></label>
                        <select id="priority" name="priority" class="form-control">
                            <option value="">— Select Priority —</option>
                            <option value="Low">🟢 Low</option>
                            <option value="Medium">🟡 Medium</option>
                            <option value="High">🔴 High</option>
                        </select>
                        <span class="invalid-feedback" id="priorityError"></span>
                    </div>

                    <!-- Submit -->
                    <button type="submit" id="submitBtn" class="btn btn-primary">
                        ➕ Add Task
                    </button>

                </form>
            </div>
        </section>

        <!-- ================================================
             RIGHT — TASK LIST
        ================================================ -->
        <section class="card" aria-labelledby="listHeading">
            <div class="card-header">
                <h2 id="listHeading">📂 All Tasks</h2>
                <span id="taskCount" style="font-size:0.8rem; color:var(--text-muted); font-weight:600;">
                    <?= $totalTasks ?> task<?= $totalTasks !== 1 ? 's' : '' ?>
                </span>
            </div>
            <div class="card-body" style="padding-bottom:0.5rem;">

                <!-- Search Bar -->
                <div class="form-group search-wrapper">
                    <span class="search-icon">🔍</span>
                    <input
                        type="search"
                        id="searchInput"
                        class="form-control"
                        placeholder="Search tasks by title..."
                        autocomplete="off"
                    >
                </div>

            </div>

            <?php if (empty($tasks)): ?>
                <!-- Empty state -->
                <div class="empty-state">
                    <span class="empty-icon">📭</span>
                    <p>No tasks yet!</p>
                    <small>Use the form on the left to add your first task.</small>
                </div>
            <?php else: ?>
                <div class="task-table-wrapper">
                    <table class="task-table" role="table" aria-label="Task list">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Task</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($tasks as $i => $task): ?>
                            <tr class="task-row" data-title="<?= htmlspecialchars($task['title']) ?>">

                                <td data-label="#">
                                    <?= $i + 1 ?>
                                </td>

                                <td data-label="Task">
                                    <div class="task-title"><?= htmlspecialchars($task['title']) ?></div>
                                    <div class="task-desc"><?= htmlspecialchars($task['description']) ?></div>
                                </td>

                                <td data-label="Priority">
                                    <span class="badge <?= priorityBadge($task['priority']) ?>">
                                        <span class="priority-dot <?= priorityDot($task['priority']) ?>"></span>
                                        <?= htmlspecialchars($task['priority']) ?>
                                    </span>
                                </td>

                                <td data-label="Status">
                                    <span class="badge <?= statusBadge($task['status']) ?>">
                                        <?= $task['status'] === 'Completed' ? '✓' : '○' ?>
                                        <?= htmlspecialchars($task['status']) ?>
                                    </span>
                                </td>

                                <td data-label="Date">
                                    <?= date('M d, Y', strtotime($task['created_at'])) ?>
                                </td>

                                <td data-label="Actions">
                                    <div class="actions-cell">
                                        <!-- Toggle Status -->
                                        <form action="actions/update-task.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="id" value="<?= (int)$task['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-success" title="Toggle status">
                                                <?= $task['status'] === 'Pending' ? '✓ Done' : '↺ Reopen' ?>
                                            </button>
                                        </form>

                                        <!-- Delete Task -->
                                        <form
                                            action="actions/delete-task.php"
                                            method="POST"
                                            class="deleteForm"
                                            data-title="<?= htmlspecialchars($task['title']) ?>"
                                            style="display:inline;"
                                        >
                                            <input type="hidden" name="id" value="<?= (int)$task['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete task">
                                                🗑 Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Shown when search has no results -->
                <div id="emptySearchMsg" class="empty-state" style="display:none;">
                    <span class="empty-icon">🔍</span>
                    <p>No tasks match your search.</p>
                    <small>Try a different keyword.</small>
                </div>
            <?php endif; ?>
        </section>

    </div><!-- /.grid-2 -->
</main>

<!-- ============================================================
     FOOTER
============================================================ -->
<footer class="site-footer">
    <p>Mini Task Management System &copy; <?= date('Y') ?> &mdash; Built with PHP &amp; Vanilla JS</p>
</footer>

<script src="js/app.js"></script>
</body>
</html>
