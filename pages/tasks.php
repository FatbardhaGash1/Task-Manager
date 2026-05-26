<?php
$page_title = "Detyrat";
include '../includes/header.php';
include '../includes/navigation.php';
include '../includes/functions.php';
include '../includes/db.php';

requireLogin();

$message = "";
$error = "";

if(isset($_POST['add_task'])) {

    $title = cleanInput($_POST['title']);

    $description =
    cleanInput($_POST['description']);

    $priority =
    $_POST['priority'] ?? 'Medium';

    if(validateTaskTitle($title)) {

        try {

            $stmt = $pdo->prepare(

            "INSERT INTO tasks
            (title,description,priority,assigned_to)

            VALUES

            (?,?,?,?)"

            );

            $stmt->execute([

                $title,

                $description,

                $priority,

                $_SESSION['user_id']

            ]);

            $message =
            "✅ Detyra u shtua me sukses!";

        } catch(PDOException $e) {

            $error =
            "❌ Gabim gjatë shtimit!";

        }

    } else {

        $error =
        "❌ Titulli duhet 3-60 karaktere!";

    }

}

if(isset($_GET['delete'])) {

    try {

        $stmt = $pdo->prepare(

        "DELETE FROM tasks
        WHERE id = ?"

        );

        $stmt->execute([
            $_GET['delete']
        ]);

        $message =
        "✅ Detyra u fshi!";

    } catch(PDOException $e) {

        $error =
        "❌ Gabim gjatë fshirjes!";

    }

}
if(isset($_GET['status']) && isset($_GET['id'])) {if(
    isset($_GET['status']) &&
    isset($_GET['id'])
) {

    try {

        $stmt = $pdo->prepare(

        "UPDATE tasks
        SET status = ?
        WHERE id = ?"

        );

        $stmt->execute([

            $_GET['status'],

            $_GET['id']

        ]);

        $message =
        "✅ Statusi u ndryshua!";

    } catch(PDOException $e) {

        $error =
        "❌ Gabim gjatë update!";

    }

}
}

try {

    if($_SESSION['user_role'] == 'admin') {

        $stmt = $pdo->query(

        "SELECT tasks.*,
        users.name AS user_name

        FROM tasks

        JOIN users
        ON tasks.assigned_to = users.id

        ORDER BY tasks.created_at DESC"

        );

    } else {

        $stmt = $pdo->prepare(

        "SELECT tasks.*,
        users.name AS user_name

        FROM tasks

        JOIN users
        ON tasks.assigned_to = users.id

        WHERE assigned_to = ?

        ORDER BY tasks.created_at DESC"

        );

        $stmt->execute([
            $_SESSION['user_id']
        ]);

    }

    $userTasks = $stmt->fetchAll();

} catch(PDOException $e) {

    $userTasks = [];

    $error =
    "❌ Gabim gjatë leximit!";

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
    <form method="POST">
        <div class="form-group">
            <label>Titulli</label>
            <input type="text" name="title" required placeholder="Shkruaj titullin e detyrës...">
        </div>
        <div class="form-group">
            <label>Përshkrimi</label>
            <textarea name="description" rows="3" placeholder="Përshkruaj detyrën..."></textarea>
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
        <button type="submit" name="add_task" class="btn btn-primary">➕ Shto Detyrë</button>
    </form>
</div>

<div class="tasks-grid">
    <?php if(count($userTasks) > 0): ?>
        <?php foreach($userTasks as $task): ?>
            <?php
            $badgeClass = '';
            switch($task['status']) {
                case 'Pending': $badgeClass = 'badge-pending'; break;
                case 'In Progress': $badgeClass = 'badge-progress'; break;
                case 'Completed': $badgeClass = 'badge-completed'; break;
            }
            
            $priorityColor = '';
            switch($task['priority']) {
                case 'Urgent': $priorityColor = '#ef4444'; break;
                case 'High': $priorityColor = '#f97316'; break;
                case 'Medium': $priorityColor = '#eab308'; break;
                case 'Low': $priorityColor = '#10b981'; break;
            }
            ?>
            
            <div class="task-card">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                    <h3><?php echo htmlspecialchars($task['title']); ?></h3>
                    <span class="badge <?php echo $badgeClass; ?>"><?php echo $task['status']; ?></span>
                </div>
                
                <p style="color: #64748b; margin-bottom: 10px;"><?php echo htmlspecialchars($task['description']); ?></p>
                
                <div style="margin-bottom: 10px;">
                    <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: <?php echo $priorityColor; ?>; margin-right: 5px;"></span>
                    <strong>Prioriteti:</strong> <?php echo $task['priority']; ?>
                </div>
                
                <div class="button-group">
                    <?php if($task['status'] != 'Pending'): ?>
                        <a href="?status=Pending&id=<?php echo $task['id']; ?>" class="btn-small btn-warning">⏳ Në Pritje</a>
                    <?php endif; ?>
                    
                    <?php if($task['status'] != 'In Progress'): ?>
                        <a href="?status=In%20Progress&id=<?php echo $task['id']; ?>" class="btn-small" style="background: #3b82f6; color: white;">🔄 Në Proces</a>
                    <?php endif; ?>
                    
                    <?php if($task['status'] != 'Completed'): ?>
                        <a href="?status=Completed&id=<?php echo $task['id']; ?>" class="btn-small btn-success">✅ Përfundo</a>
                    <?php endif; ?>
                    
                    <a href="?delete=<?php echo $task['id']; ?>" class="btn-small btn-danger" onclick="return confirm('A je i sigurt?')">🗑️ Fshij</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="card" style="grid-column: 1/-1; text-align: center;">
            <p>📭 Nuk ke asnjë detyrë për momentin.</p>
            <p>Shto detyrën tënde të parë duke përdorur formularin më lart!</p>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>