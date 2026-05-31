<?php
$page_title = "Detyrat";
include '../includes/header.php';
include '../includes/navigation.php';
include '../includes/functions.php';
require_once '../config/db.php';
requireLogin();

$message = "";
$error = "";


if(isset($_POST['add_task'])) {
    $title = cleanInput($_POST['title']);
    $description = cleanInput($_POST['description']);
    $priority = $_POST['priority'];
    $user_id = $_SESSION['user_id'];

    if(!validateTaskTitle($title)) {
        $error = "Titulli duhet 3-60 karaktere!";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO tasks (title, description, priority, user_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$title, $description, $priority, $user_id]);
            $task_id = $pdo->lastInsertId();

           
            if(isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = '../uploads/';
                if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                $file_name = time() . '_' . basename($_FILES['attachment']['name']);
                $target = $upload_dir . $file_name;
                if(move_uploaded_file($_FILES['attachment']['tmp_name'], $target)) {
                    $stmt2 = $pdo->prepare("INSERT INTO task_attachments (task_id, file_name, file_path) VALUES (?, ?, ?)");
                    $stmt2->execute([$task_id, $file_name, $target]);
                    $message = "Detyra u shtua dhe skeda u ngarkua!";
                } else {
                    $message = "Detyra u shtua pa skedar.";
                }
            } else {
                $message = "Detyra u shtua!";
            }
        } catch(PDOException $e) {
            $error = "Gabim: " . $e->getMessage();
        }
    }
}


if(isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        if(isAdmin()) {
            $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
            $stmt->execute([$id]);
        } else {
            $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
            $stmt->execute([$id, $_SESSION['user_id']]);
        }
        $message = "Detyra u fshi.";
    } catch(PDOException $e) {
        $error = "Nuk mund të fshihet.";
    }
}


if(isAdmin()) {
    $stmt = $pdo->query("SELECT t.*, u.name as assigned_name FROM tasks t LEFT JOIN users u ON t.user_id = u.id ORDER BY t.created_at DESC");
    $tasks = $stmt->fetchAll();
} else {
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$_SESSION['user_id']]);
    $tasks = $stmt->fetchAll();
}
?>

<?php if($message): ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>
<?php if($error): ?>
    <div class="alert alert-error"><?php echo $error; ?></div>
<?php endif; ?>

<div class="card">
    <h2>➕ Shto Detyrë të Re</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Titulli</label>
            <input type="text" name="title" required>
        </div>
        <div class="form-group">
            <label>Përshkrimi</label>
            <textarea name="description" rows="3"></textarea>
        </div>
        <div class="form-group">
            <label>Prioriteti</label>
            <select name="priority">
                <option value="Low">🟢 Low</option>
                <option value="Medium">🟡 Medium</option>
                <option value="High">🟠 High</option>
                <option value="Urgent">🔴 Urgent</option>
            </select>
        </div>
        <div class="form-group">
            <label>Skedari (opsional)</label>
            <input type="file" name="attachment">
        </div>
        <button type="submit" name="add_task" class="btn btn-primary">➕ Shto Detyrë</button>
    </form>
</div>

<div class="tasks-grid" id="tasksGrid">
    <?php foreach($tasks as $task): 
        $badgeClass = '';
        switch($task['status']) {
            case 'Pending': $badgeClass = 'badge-pending'; break;
            case 'In Progress': $badgeClass = 'badge-progress'; break;
            case 'Completed': $badgeClass = 'badge-completed'; break;
        }
        $priorityColor = match($task['priority']) {
            'Urgent' => '#ef4444', 'High' => '#f97316', 'Medium' => '#eab308', 'Low' => '#10b981'
        };
    ?>
        <div class="task-card" data-task-id="<?php echo $task['id']; ?>">
            <div style="display:flex; justify-content:space-between;">
                <h3><?php echo cleanInput($task['title']); ?></h3>
                <span class="badge <?php echo $badgeClass; ?>"><?php echo $task['status']; ?></span>
            </div>
            <p><?php echo nl2br(cleanInput($task['description'])); ?></p>
            <div><span style="display:inline-block; width:10px; height:10px; border-radius:50%; background:<?php echo $priorityColor; ?>; margin-right:5px;"></span> Prioriteti: <?php echo $task['priority']; ?></div>
            <?php if(isAdmin()): ?>
                <div><small>Përdoruesi: <?php echo cleanInput($task['assigned_name'] ?? 'N/A'); ?></small></div>
            <?php endif; ?>
            <div class="button-group">
                <a href="../send_email.php?task_id=<?php echo $task['id']; ?>" class="btn-small" style="background:#8b5cf6; color:white;">📧 Dërgo email</a>
                <button onclick="changeStatus(<?php echo $task['id']; ?>, 'Pending')" class="btn-small btn-warning">⏳ Në Pritje</button>
                <button onclick="changeStatus(<?php echo $task['id']; ?>, 'In Progress')" class="btn-small" style="background:#3b82f6; color:white;">🔄 Në Proces</button>
                <button onclick="changeStatus(<?php echo $task['id']; ?>, 'Completed')" class="btn-small btn-success">✅ Përfundo</button>
                <a href="?delete=<?php echo $task['id']; ?>" class="btn-small btn-danger" onclick="return confirm('Fshij detyrën?')">🗑️ Fshij</a>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if(count($tasks) == 0): ?>
        <div class="card" style="grid-column:1/-1; text-align:center;">📭 Nuk ke asnjë detyrë.</div>
    <?php endif; ?>
</div>

<script>
function changeStatus(taskId, newStatus) {
    fetch('../ajax_update_status.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({id: taskId, status: newStatus})
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            location.reload();
        } else {
            alert("Gabim: " + (data.error || "Nuk u ndryshua statusi"));
        }
    })
    .catch(err => alert("Problem me AJAX"));
}
</script>

<?php include '../includes/footer.php'; ?>
