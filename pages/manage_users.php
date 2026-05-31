<?php
$page_title = "Menaxho Përdoruesit";
include '../includes/header.php';
include '../includes/navigation.php';
include '../includes/functions.php';
require_once '../config/db.php';
requireLogin();
if(!isAdmin()) { header("Location: dashboard.php"); exit(); }

$message = ""; $error = "";

if(isset($_POST['add_user'])) {
    $name = cleanInput($_POST['name']);
    $email = cleanInput($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];
    if(empty($name) || !validateEmail($email) || strlen($password) < 6) {
        $error = "Të dhëna të pavlefshme.";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $hashed, $role]);
            $message = "Përdoruesi u shtua.";
        } catch(PDOException $e) {
            $error = "Email ekziston ose gabim tjetër.";
        }
    }
}

if(isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if($id == $_SESSION['user_id']) {
        $error = "Nuk mund të fshish veten.";
    } else {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $message = "Përdoruesi u fshi.";
    }
}

$users = $pdo->query("SELECT * FROM users ORDER BY id")->fetchAll();
?>

<div class="card">
    <h2>➕ Shto Përdorues të Ri</h2>
    <?php if($message): ?><div class="alert alert-success"><?php echo $message; ?></div><?php endif; ?>
    <?php if($error): ?><div class="alert alert-error"><?php echo $error; ?></div><?php endif; ?>
    <form method="POST">
        <div class="form-group"><label>Emri</label><input type="text" name="name" required></div>
        <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
        <div class="form-group"><label>Fjalëkalimi</label><input type="password" name="password" required></div>
        <div class="form-group"><label>Roli</label><select name="role"><option value="user">User</option><option value="admin">Admin</option></select></div>
        <button type="submit" name="add_user" class="btn btn-primary">Shto</button>
    </form>
</div>

<div class="card">
    <h2>📋 Lista e Përdoruesve</h2>
    <table style="width:100%; border-collapse:collapse;">
        <tr style="background:#a5b4fc;"><th>ID</th><th>Emri</th><th>Email</th><th>Roli</th><th>Veprimi</th></tr>
        <?php foreach($users as $u): ?>
        <tr style="border-bottom:1px solid #ddd;">
            <td><?php echo $u['id']; ?></td><td><?php echo cleanInput($u['name']); ?></td><td><?php echo cleanInput($u['email']); ?></td><td><?php echo $u['role']; ?></td>
            <td><a href="?delete=<?php echo $u['id']; ?>" class="btn-small btn-danger" onclick="return confirm('Fshi?')">Fshij</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
