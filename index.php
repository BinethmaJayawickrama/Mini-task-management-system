<?php
session_start();
require_once 'includes/functions.php';

$success = $_SESSION['success'] ?? null;
$errors  = $_SESSION['errors']  ?? [];
unset($_SESSION['success'], $_SESSION['errors']);

$tasks     = getAllTasks($conn);
$total     = count($tasks);
$pending   = count(array_filter($tasks, fn($t) => $t['status'] === 'Pending'));
$completed = count(array_filter($tasks, fn($t) => $t['status'] === 'Completed'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Mini Task Management System">
    <title>Task Management System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark-mode');
        }
    </script>
</head>
<body>

<header class="site-header">
    <h1><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; vertical-align: middle;"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>Task Manager</h1>
    <button id="themeToggle" class="btn btn-gray" style="padding: 8px 12px; display: inline-flex; align-items: center; justify-content: center; width: auto; height: auto;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="theme-icon"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
    </button>
</header>

<main class="container">

    <?php if ($success): ?>
        <div class="alert alert-ok">
            ✅ <?= htmlspecialchars($success) ?>
            <button class="alert-close">✕</button>
        </div>
    <?php endif; ?>

    <?php foreach ($errors as $error): ?>
        <div class="alert alert-err">
            ⚠️ <?= htmlspecialchars($error) ?>
            <button class="alert-close">✕</button>
        </div>
    <?php endforeach; ?>

    <!-- Stats -->
    <div class="stats-bar">
        <div class="stat-box total">
            <div class="num"><?= $total ?></div>
            <div class="lbl">Total</div>
        </div>
        <div class="stat-box pending">
            <div class="num"><?= $pending ?></div>
            <div class="lbl">Pending</div>
        </div>
        <div class="stat-box done">
            <div class="num"><?= $completed ?></div>
            <div class="lbl">Completed</div>
        </div>
    </div>

    <div class="grid">

        <!-- Add Task Form -->
        <section class="card">
            <div class="card-head">
                <h2>Add New Task</h2>
            </div>
            <div class="card-body">
                <form id="taskForm" action="actions/add-task.php" method="POST" novalidate>

                    <div class="form-group">
                        <label for="title">Task Title</label>
                        <input type="text" id="title" name="title" class="form-control" placeholder="e.g. Fix login bug" maxlength="255" autocomplete="off">
                        <span class="error-msg" id="titleError"></span>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="form-control" placeholder="What needs to be done?" rows="4"></textarea>
                        <span class="error-msg" id="descError"></span>
                    </div>

                    <div class="form-group">
                        <label for="priority">Priority</label>
                        <select id="priority" name="priority" class="form-control">
                            <option value="">Select</option>
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                        </select>
                        <span class="error-msg" id="priorityError"></span>
                    </div>

                    <button type="submit" id="submitBtn" class="btn btn-submit">Add Task</button>

                </form>
            </div>
        </section>

        <!-- Task List -->
        <section class="card">
            <div class="card-head">
                <h2>All Tasks</h2>
                <span style="font-size:0.75rem; color:#9ca3af;"><?= $total ?> task<?= $total !== 1 ? 's' : '' ?></span>
            </div>
            <div class="card-body" style="padding-bottom: 8px;">
                <div class="search-box">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:block;"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </span>
                    <input type="search" id="searchInput" class="form-control" placeholder="Search by title..." autocomplete="off">
                </div>
            </div>

            <?php if (empty($tasks)): ?>
                <div class="empty">
                    <span>📭</span>
                    <p>No tasks yet. Add one!</p>
                </div>
            <?php else: ?>
                <div style="overflow-x: auto;">
                    <table class="task-table">
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
                                <td data-label="#"><?= $i + 1 ?></td>
                                <td data-label="Task">
                                    <div class="task-name"><?= htmlspecialchars($task['title']) ?></div>
                                    <div class="task-desc"><?= htmlspecialchars($task['description']) ?></div>
                                </td>
                                <td data-label="Priority">
                                    <span class="badge <?= priorityBadge($task['priority']) ?>">
                                        <?= $task['priority'] ?>
                                    </span>
                                </td>
                                <td data-label="Status">
                                    <span class="badge <?= statusBadge($task['status']) ?>">
                                        <?= $task['status'] ?>
                                    </span>
                                </td>
                                <td data-label="Date"><?= date('M d, Y', strtotime($task['created_at'])) ?></td>
                                <td data-label="Actions">
                                    <div class="actions">
                                        <!-- Toggle status -->
                                        <form action="actions/update-task.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="id" value="<?= (int)$task['id'] ?>">
                                            <button class="btn btn-green" type="submit">
                                                <?= $task['status'] === 'Pending' ? '✓ Done' : '↺ Reopen' ?>
                                            </button>
                                        </form>

                                        <!-- Edit task -->
                                        <button class="btn btn-amber editBtn"
                                            data-id="<?= (int)$task['id'] ?>"
                                            data-title="<?= htmlspecialchars($task['title'], ENT_QUOTES) ?>"
                                            data-description="<?= htmlspecialchars($task['description'], ENT_QUOTES) ?>"
                                            data-priority="<?= $task['priority'] ?>">
                                            ✏️ Edit
                                        </button>

                                        <!-- Delete task -->
                                        <form action="actions/delete-task.php" method="POST" class="deleteForm"
                                            data-title="<?= htmlspecialchars($task['title']) ?>" style="display:inline;">
                                            <input type="hidden" name="id" value="<?= (int)$task['id'] ?>">
                                            <button class="btn btn-red" type="submit">🗑 Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div id="emptySearch" class="empty" style="display:none;">
                    <span>🔍</span>
                    <p>No tasks match your search.</p>
                </div>
            <?php endif; ?>
        </section>

    </div>
</main>

<footer class="site-footer">
    Mini Task Management System &copy; <?= date('Y') ?>
</footer>

<!-- Edit Modal -->
<div class="modal-overlay" id="editModal">
    <div class="modal">
        <div class="modal-head">
            <h3>Edit Task</h3>
            <button class="modal-close" id="modalClose">✕</button>
        </div>
        <form action="actions/edit-task.php" method="POST" id="editForm">
            <div class="modal-body">
                <input type="hidden" name="id" id="editId">

                <div class="form-group">
                    <label for="editTitle">Task Title</label>
                    <input type="text" id="editTitle" name="title" class="form-control" maxlength="255">
                    <span class="error-msg" id="editTitleError"></span>
                </div>

                <div class="form-group">
                    <label for="editDescription">Description</label>
                    <textarea id="editDescription" name="description" class="form-control" rows="3"></textarea>
                    <span class="error-msg" id="editDescError"></span>
                </div>

                <div class="form-group">
                    <label for="editPriority">Priority</label>
                    <select id="editPriority" name="priority" class="form-control">
                        <option value="">Select</option>
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                    </select>
                    <span class="error-msg" id="editPriorityError"></span>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn btn-gray" id="modalCancel">Cancel</button>
                <button type="submit" class="btn btn-submit" id="editSubmitBtn" style="width:auto;">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script src="js/app.js?v=<?= time() ?>"></script>
</body>
</html>
