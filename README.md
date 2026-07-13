# Mini Task Management System

A simple, responsive Task Management System built using Core PHP, HTML, CSS, JavaScript, and MySQL.

---

## Features

- **Add Tasks**: Create new tasks with Title, Description, and Priority.
- **View Tasks**: Display all tasks dynamically in a clean table (latest tasks first).
- **Edit Tasks**: Edit task Title, Description, and Priority in a modal form (Bonus Task).
- **Toggle Status**: Instantly toggle task status between Pending and Completed.
- **Delete Tasks**: Remove tasks with a confirmation dialog.
- **Dynamic Search**: Filter tasks by title in real-time without reloading the page.
- **Dark Mode**: Switch between light and dark modes with persistent browser storage (Bonus Task).
- **Validation**: Full frontend (JavaScript) and backend (PHP) validation.

---

## Folder Structure

```text
Mini-task-management-system/
├── actions/
│   ├── add-task.php         # Create new task
│   ├── delete-task.php      # Remove task
│   ├── edit-task.php        # Edit task details (Bonus)
│   └── update-task.php      # Toggle pending/completed status
├── css/
│   └── style.css            # Custom pastel styles and theme variables
├── includes/
│   ├── db.php               # MySQLi database connection
│   └── functions.php        # Fetch query and CSS badge utilities
├── js/
│   └── app.js               # Validation, search, delete, and theme toggle logic
├── database.sql             # SQL database schema and sample data
├── index.php                # Main dashboard page
└── README.md                # Project documentation
```

---

## Setup Instructions

### 1. Prerequisites
Ensure you have a local server environment installed, such as **XAMPP**, **WAMP**, **MAMP**, or **Laragon** (configured with PHP and MySQL).

### 2. Copy the Project
Copy the `Mini-task-management-system` folder and place it in your local server's web root:
- **XAMPP**: `C:\xampp\htdocs\Mini-task-management-system`
- **Laragon**: `C:\laragon\www\Mini-task-management-system`

### 3. Setup the Database
1. Open the **XAMPP Control Panel** and start both **Apache** and **MySQL**.
2. Go to **phpMyAdmin** in your browser: [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
3. Click on the **Import** tab at the top server level.
4. Choose the `database.sql` file located in the root of the project folder.
5. Click **Go** (this automatically creates the database `intern_task_system`, the `tasks` table, and inserts sample rows).

### 4. Configure Database Credentials (Optional)
If your local MySQL installation has a different username or password, modify them in `includes/db.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // Change if needed
define('DB_PASS', '');           // Change if needed
define('DB_NAME', 'intern_task_system');
```

### 5. Run the Application
Open your browser and navigate to:
```text
http://localhost/Mini-task-management-system/
```
