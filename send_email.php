<?php
session_start();
require_once 'includes/functions.php';
requireLogin();

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['task_id'])) {
    $task_id = (int)$_POST['task_id'];
    // merr detyrën nga DB
    require_once 'config/db.php';
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
    $stmt->execute([$task_id]);
    $task = $stmt->fetch();
    if($task && ($_SESSION['user_role'] == 'admin' || $task['user_id'] == $_SESSION['user_id'])) {
        $to = $_SESSION['user_email'];
        $subject = "Detyra: " . $task['title'];
        $message = "Përshkrimi: " . $task['description'] . "\nStatusi: " . $task['status'];
        $headers = "From: no-reply@taskmanager.com";
        if(mail($to, $subject, $message, $headers)) {
            $_SESSION['mail_sent'] = "Email u dërgua tek ju.";
        } else {
            $_SESSION['mail_error'] = "Dërgimi dështoi.";
        }
    }
}
header("Location: pages/tasks.php");
exit();
?>
