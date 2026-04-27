<?php
function validateEmail($email) {
    return preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email);
}

function validateTaskTitle($title) {
    return preg_match('/^[a-zA-Z0-9 ëËçÇ.,!?-]{3,60}$/', $title);
}

function cleanInput($data) {
    return htmlspecialchars(trim($data));
}

function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
}