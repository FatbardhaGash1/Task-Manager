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
