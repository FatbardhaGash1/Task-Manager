<?php
$page_title = "Regjistrohu";
include '../includes/header.php';
include '../includes/navigation.php';
include '../includes/functions.php';
require_once '../config/db.php';

$error = "";
$success = "";

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = cleanInput($_POST['name']);
    $email = cleanInput($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if(empty($name) || strlen($name) < 3) {
        $error = "Emri duhet të ketë të paktën 3 karaktere.";
    } elseif(!validateEmail($email)) {
        $error = "Email adresa nuk është valid.";
    } elseif(strlen($password) < 6) {
        $error = "Fjalëkalimi duhet të ketë të paktën 6 karaktere.";
    } elseif($password !== $confirm) {
        $error = "Fjalëkalimet nuk përputhen.";
    } else {
        // Kontrollo nëse emaili ekziston
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if($stmt->fetch()) {
            $error = "Ky email është i regjistruar tashmë.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
            $stmt->execute([$name, $email, $hashed]);
            $success = "Regjistrimi u krye! Tani mund të kyçeni.";
        }
    }
}
?>

<div class="card" style="max-width: 500px; margin: 0 auto;">
    <h2 style="text-align: center;">📝 Regjistrohu</h2>
    <?php if($error): ?>
        <div class="alert alert-error">❌ <?php echo $error; ?></div>
    <?php endif; ?>
    <?php if($success): ?>
        <div class="alert alert-success">✅ <?php echo $success; ?> <a href="login.php">Kyçu këtu</a></div>
    <?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label>Emri i plotë</label>
            <input type="text" name="name" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Fjalëkalimi</label>
            <input type="password" name="password" required>
        </div>
        <div class="form-group">
            <label>Konfirmo fjalëkalimin</label>
            <input type="password" name="confirm_password" required>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%">Regjistrohu</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
