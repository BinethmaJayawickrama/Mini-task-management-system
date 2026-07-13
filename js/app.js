// ============================================================
// app.js — Mini Task Management System
// Frontend: Validation · Search · Delete Confirm · Loading
// ============================================================

document.addEventListener('DOMContentLoaded', function () {

    /* --------------------------------------------------------
       1. AUTO-DISMISS FLASH ALERTS after 4 seconds
    -------------------------------------------------------- */
    document.querySelectorAll('.alert').forEach(function (alert) {
        // Auto-dismiss
        setTimeout(function () { dismissAlert(alert); }, 4000);

        // Manual close button
        const closeBtn = alert.querySelector('.alert-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', function () {
                dismissAlert(alert);
            });
        }
    });

    function dismissAlert(alert) {
        alert.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
        alert.style.opacity    = '0';
        alert.style.transform  = 'translateY(-8px)';
        setTimeout(function () { alert.remove(); }, 400);
    }


    /* --------------------------------------------------------
       2. ADD TASK FORM — Frontend Validation
    -------------------------------------------------------- */
    const taskForm = document.getElementById('taskForm');

    if (taskForm) {
        taskForm.addEventListener('submit', function (e) {
            let valid = true;

            // ── Title ──
            const titleInput = document.getElementById('title');
            const titleError = document.getElementById('titleError');
            const titleVal   = titleInput.value.trim();

            if (titleVal === '') {
                showError(titleInput, titleError, 'Task title is required.');
                valid = false;
            } else if (titleVal.length < 3) {
                showError(titleInput, titleError, 'Title must be at least 3 characters long.');
                valid = false;
            } else {
                clearError(titleInput, titleError);
            }

            // ── Description ──
            const descInput = document.getElementById('description');
            const descError = document.getElementById('descError');

            if (descInput.value.trim() === '') {
                showError(descInput, descError, 'Description is required.');
                valid = false;
            } else {
                clearError(descInput, descError);
            }

            // ── Priority ──
            const priorityInput = document.getElementById('priority');
            const priorityError = document.getElementById('priorityError');

            if (priorityInput.value === '') {
                showError(priorityInput, priorityError, 'Please select a priority level.');
                valid = false;
            } else {
                clearError(priorityInput, priorityError);
            }

            // ── Stop submission if invalid ──
            if (!valid) {
                e.preventDefault();
                // Scroll to first error
                const firstError = taskForm.querySelector('.is-invalid');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
                return;
            }

            // ── Show loading state on submit button ──
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.innerHTML = '<span class="spinner"></span> Adding Task...';
            submitBtn.disabled  = true;
        });

        // Clear error on input
        ['title', 'description', 'priority'].forEach(function (id) {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('input', function () {
                    clearError(el, document.getElementById(id + 'Error'));
                });
            }
        });
    }


    /* --------------------------------------------------------
       3. REAL-TIME SEARCH — Filter tasks by title (no reload)
    -------------------------------------------------------- */
    const searchInput = document.getElementById('searchInput');

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const query       = this.value.toLowerCase().trim();
            const rows        = document.querySelectorAll('.task-row');
            const emptySearch = document.getElementById('emptySearchMsg');
            let   visible     = 0;

            rows.forEach(function (row) {
                const title = row.getAttribute('data-title').toLowerCase();
                if (title.includes(query)) {
                    row.style.display = '';
                    visible++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Show/hide "no results" message
            if (emptySearch) {
                emptySearch.style.display = visible === 0 ? 'block' : 'none';
            }

            // Update visible count label
            const countLabel = document.getElementById('taskCount');
            if (countLabel) {
                countLabel.textContent = query === ''
                    ? rows.length + ' task' + (rows.length !== 1 ? 's' : '')
                    : visible + ' result' + (visible !== 1 ? 's' : '');
            }
        });
    }


    /* --------------------------------------------------------
       4. DELETE CONFIRMATION
    -------------------------------------------------------- */
    document.querySelectorAll('.deleteForm').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            const title     = form.getAttribute('data-title') || 'this task';
            const confirmed = confirm(
                '🗑️  Delete "' + title + '"?\n\nThis action cannot be undone.'
            );
            if (!confirmed) {
                e.preventDefault();
            }
        });
    });


    /* --------------------------------------------------------
       Helpers
    -------------------------------------------------------- */
    function showError(input, errorEl, message) {
        input.classList.add('is-invalid');
        if (errorEl) {
            errorEl.textContent    = message;
            errorEl.style.display  = 'block';
        }
    }

    function clearError(input, errorEl) {
        input.classList.remove('is-invalid');
        if (errorEl) {
            errorEl.textContent   = '';
            errorEl.style.display = 'none';
        }
    }

});
