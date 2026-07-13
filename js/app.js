document.addEventListener('DOMContentLoaded', function () {

    // Auto-dismiss alerts
    document.querySelectorAll('.alert').forEach(function (alert) {
        setTimeout(function () {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.4s';
            setTimeout(function () { alert.remove(); }, 400);
        }, 4000);

        const btn = alert.querySelector('.alert-close');
        if (btn) btn.addEventListener('click', function () { alert.remove(); });
    });


    // Add task form validation
    const taskForm = document.getElementById('taskForm');
    if (taskForm) {
        taskForm.addEventListener('submit', function (e) {
            let valid = true;

            const title = document.getElementById('title');
            const titleErr = document.getElementById('titleError');
            if (title.value.trim() === '') {
                showErr(title, titleErr, 'Title is required.'); valid = false;
            } else if (title.value.trim().length < 3) {
                showErr(title, titleErr, 'Title must be at least 3 characters.'); valid = false;
            } else {
                clearErr(title, titleErr);
            }

            const desc = document.getElementById('description');
            const descErr = document.getElementById('descError');
            if (desc.value.trim() === '') {
                showErr(desc, descErr, 'Description is required.'); valid = false;
            } else {
                clearErr(desc, descErr);
            }

            const priority = document.getElementById('priority');
            const priorityErr = document.getElementById('priorityError');
            if (priority.value === '') {
                showErr(priority, priorityErr, 'Please select a priority.'); valid = false;
            } else {
                clearErr(priority, priorityErr);
            }

            if (!valid) { e.preventDefault(); return; }

            const btn = document.getElementById('submitBtn');
            btn.innerHTML = '<span class="spinner"></span> Adding...';
            btn.disabled = true;
        });

        // Clear errors on input
        ['title', 'description', 'priority'].forEach(function (id) {
            const el = document.getElementById(id);
            if (el) el.addEventListener('input', function () {
                clearErr(el, document.getElementById(id + 'Error'));
            });
        });
    }


    // Live search
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const q = this.value.toLowerCase().trim();
            const rows = document.querySelectorAll('.task-row');
            let count = 0;

            rows.forEach(function (row) {
                const match = row.getAttribute('data-title').toLowerCase().includes(q);
                row.style.display = match ? '' : 'none';
                if (match) count++;
            });

            const empty = document.getElementById('emptySearch');
            if (empty) empty.style.display = count === 0 ? 'block' : 'none';
        });
    }


    // Delete confirmation
    document.querySelectorAll('.deleteForm').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            if (!confirm('Delete "' + form.getAttribute('data-title') + '"? This cannot be undone.')) {
                e.preventDefault();
            }
        });
    });


    // Edit modal
    const modal = document.getElementById('editModal');

    document.querySelectorAll('.editBtn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('editId').value          = btn.dataset.id;
            document.getElementById('editTitle').value       = btn.dataset.title;
            document.getElementById('editDescription').value = btn.dataset.description;
            document.getElementById('editPriority').value    = btn.dataset.priority;
            modal.classList.add('active');
            document.getElementById('editTitle').focus();
        });
    });

    function closeModal() {
        if (modal) modal.classList.remove('active');
    }

    document.getElementById('modalClose')?.addEventListener('click', closeModal);
    document.getElementById('modalCancel')?.addEventListener('click', closeModal);
    modal?.addEventListener('click', function (e) { if (e.target === modal) closeModal(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeModal(); });

    // Edit form validation
    const editForm = document.getElementById('editForm');
    if (editForm) {
        editForm.addEventListener('submit', function (e) {
            let valid = true;

            const t = document.getElementById('editTitle');
            const tErr = document.getElementById('editTitleError');
            if (t.value.trim().length < 3) {
                showErr(t, tErr, 'Title must be at least 3 characters.'); valid = false;
            } else { clearErr(t, tErr); }

            const d = document.getElementById('editDescription');
            const dErr = document.getElementById('editDescError');
            if (d.value.trim() === '') {
                showErr(d, dErr, 'Description is required.'); valid = false;
            } else { clearErr(d, dErr); }

            const p = document.getElementById('editPriority');
            const pErr = document.getElementById('editPriorityError');
            if (p.value === '') {
                showErr(p, pErr, 'Please select a priority.'); valid = false;
            } else { clearErr(p, pErr); }

            if (!valid) { e.preventDefault(); return; }

            const saveBtn = document.getElementById('editSubmitBtn');
            saveBtn.innerHTML = '<span class="spinner"></span> Saving...';
            saveBtn.disabled = true;
        });
    }


    // Helpers
    function showErr(input, el, msg) {
        input.classList.add('is-invalid');
        if (el) { el.textContent = msg; el.style.display = 'block'; }
    }

    function clearErr(input, el) {
        input.classList.remove('is-invalid');
        if (el) { el.textContent = ''; el.style.display = 'none'; }
    }
    // Theme toggle (Dark Mode / Light Mode)
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        const sunSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="theme-icon"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>';
        const moonSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="theme-icon"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>';

        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark-mode');
            themeToggle.innerHTML = sunSvg;
        }

        themeToggle.addEventListener('click', function () {
            const isDark = document.documentElement.classList.toggle('dark-mode');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            themeToggle.innerHTML = isDark ? sunSvg : moonSvg;
        });
    }

});
