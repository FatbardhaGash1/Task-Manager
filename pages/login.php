<?php

$page_title = "Kyçu";
include '../includes/header.php';
include '../includes/navigation.php';
include '../includes/functions.php';

$valid_users = [
    'admin@example.com' => [
        'password' => 'admin123',
        'role' => 'admin',
        'name' => 'Admin User'
    ],
    'user@example.com' => [
        'password' => 'user123',
        'role' => 'user',
        'name' => 'Regular User'
    ]
];

$error = "";

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = cleanInput($_POST['email']);
    $password = $_POST['password'];

    if(!validateEmail($email)) {
        $error = "Email nuk është valid!";
    } elseif(isset($valid_users[$email]) && $valid_users[$email]['password'] == $password) {
        $_SESSION['user_id'] = $email;
        $_SESSION['user_role'] = $valid_users[$email]['role'];
        $_SESSION['user_name'] = $valid_users[$email]['name'];

        setcookie('last_user', $email, time() + 86400 * 30, '/');
        setcookie('theme', 'light', time() + 86400 * 30, '/');

        header('Location: dashboard.php');
        exit();
    } else {
        $error = "Kredenciale të pasakta!";
    }
}
?>
