-- ============================================================
-- database.sql — Mini Task Management System
-- Run this file in phpMyAdmin to set up the database
-- ============================================================

-- Create and select the database
CREATE DATABASE IF NOT EXISTS intern_task_system
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE intern_task_system;

-- -----------------------------------------------
-- Table: tasks
-- -----------------------------------------------
CREATE TABLE IF NOT EXISTS tasks (
    id          INT          NOT NULL AUTO_INCREMENT,
    title       VARCHAR(255) NOT NULL,
    description TEXT,
    priority    ENUM('Low','Medium','High')     NOT NULL DEFAULT 'Low',
    status      ENUM('Pending','Completed')     NOT NULL DEFAULT 'Pending',
    created_at  TIMESTAMP                       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------
-- Sample data (for testing — optional)
-- -----------------------------------------------
INSERT INTO tasks (title, description, priority, status) VALUES
('Design the dashboard UI',      'Create a responsive layout using HTML and CSS.',       'High',   'Completed'),
('Set up database connection',   'Configure MySQLi connection in db.php.',               'High',   'Pending'),
('Implement add task feature',   'Build the form and PHP backend to insert tasks.',      'Medium', 'Pending'),
('Write README documentation',   'Add setup instructions and project overview.',         'Low',    'Pending');
