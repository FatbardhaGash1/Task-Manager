<?php
session_start();
require_once 'config/db.php';
require_once 'includes/functions.php';

if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Jo i kyçur']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$id = (int)$input['id'];
$status = $input['status'];

$allowed = ['Pending', 'In Progress', 'Completed'];
if(!in_array($status, $allowed)) {
    echo json_encode(['success' => false, 'error' => 'Status i pavlefshëm']);
    exit();
}

try {
    if(isAdmin()) {
        $stmt = $pdo->prepare("UPDATE tasks SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE tasks SET status = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$status, $id, $_SESSION['user_id']]);
    }
    echo json_encode(['success' => true]);
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>