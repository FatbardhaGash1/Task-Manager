<?php
$page_title = "Kyçu";
include '../includes/header.php';
include '../includes/navigation.php';
include '../includes/functions.php';
require_once '../config/db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = cleanInput($_POST['email']);
    $password = $_POST['password'];

    if (!validateEmail($email)) {
        $error = "Email nuk është valid!";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])){
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            setcookie('last_user', $email, time() + 86400 * 30, '/');

            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Email ose fjalëkalim i gabuar!";
        }
    }
}
?>

<div class="card" style="max-width: 500px; margin: 0 auto;">
    <h2 style="text-align: center;">🔐 Hyr në llogarinë tënde</h2>
    
    <?php if ($error): ?>
        <div class="alert alert-error">❌ <?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label>📧 Adresa Email</label>
            <input type="email" name="email" required placeholder="email@example.com">
        </div>
        
        <div class="form-group">
            <label>🔒 Fjalëkalimi</label>
            <input type="password" name="password" required placeholder="••••••••">
        </div>
        
        <button type="submit" class="btn btn-primary" style="width: 100%;">Kyçu</button>
    </form>
    
    <div style="margin-top: 20px; text-align: center;">
        <p>Nuk ke llogari? <a href="register.php">Regjistrohu këtu</a></p>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
