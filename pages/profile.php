<?php
$page_title = "Profili";
include '../includes/header.php';
include '../includes/navigation.php';
include '../includes/functions.php';
require_once '../config/db.php';
requireLogin();

$theme = $_COOKIE['theme'] ?? 'light';
$lastUser = $_COOKIE['last_user'] ?? 'Nuk ka';

if (isset($_POST['theme'])) {
    setcookie('theme', $_POST['theme'], time() + 86400 * 30, '/');
    header("Location: profile.php");
    exit();
}




$passwordMessage = '';
if (isset($_POST['change_password'])) {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];
    
    // Fetch current hashed password from DB
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    
    if (password_verify($current, $user['password'])) {
        if (strlen($new) >= 6 && $new === $confirm) {
            $newHash = password_hash($new, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update->execute([$newHash, $_SESSION['user_id']]);
            $passwordMessage = "Fjalëkalimi u ndryshua me sukses!";
        } else {
            $passwordMessage = "Fjalëkalimi i ri duhet të ketë të paktën 6 karaktere dhe të përputhet.";
        }
    } else {
        $passwordMessage = "Fjalëkalimi aktual është i pasaktë.";
    }
}
?>

<div class="card">
    <h2>👤 Profili im</h2>
    
    <?php if ($passwordMessage): ?>
        <div class="alert alert-success"><?php echo $passwordMessage; ?></div>
    <?php endif; ?>
    
    <div style="display: grid; gap: 20px;">
        <div>
            <label>Emri</label>
            <input type="text" value="<?php echo htmlspecialchars($_SESSION['user_name']); ?>" readonly>
        </div>
        
        <div>
            <label>Email</label>
            <input type="email" value="<?php echo htmlspecialchars($_SESSION['user_email']); ?>" readonly>
        </div>
        
        <div>
            <label>Roli</label>
            <input type="text" value="<?php echo $_SESSION['user_role']; ?>" readonly>
        </div>
        
        <div>
            <label>Përdoruesi i fundit nga cookie</label>
            <input type="text" value="<?php echo htmlspecialchars($lastUser); ?>" readonly>
        </div>
    </div>
</div>

<div class="card">
    <h3>🔐 Ndrysho Fjalëkalimin</h3>
    <form method="POST">
        <div class="form-group">
            <label>Fjalëkalimi aktual</label>
            <input type="password" name="current_password" required>
        </div>
        <div class="form-group">
            <label>Fjalëkalimi i ri</label>
            <input type="password" name="new_password" required>
        </div>
        <div class="form-group">
            <label>Konfirmo fjalëkalimin e ri</label>
            <input type="password" name="confirm_password" required>
        </div>
        <button type="submit" name="change_password" class="btn btn-primary">Ndrysho Fjalëkalimin</button>
    </form>
</div>

<div class="card">
    <h3>🎨 Preferencat e Temës</h3>
    <form method="POST">
        <div class="form-group">
            <label>Tema</label>
            <select name="theme">
                <option value="light" <?php echo $theme == 'light' ? 'selected' : ''; ?>>☀️ Light Mode</option>
                <option value="dark" <?php echo $theme == 'dark' ? 'selected' : ''; ?>>🌙 Dark Mode</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">💾 Ruaj Ndryshimet</button>
    </form>
</div>

<div class="card">
    <h3>📊 Statistikat e Sessionit</h3>
    <p><strong>Session ID:</strong> <?php echo session_id(); ?></p>
    <p><strong>User ID:</strong> <?php echo $_SESSION['user_id']; ?></p>
    <p><strong>Email:</strong> <?php echo $_SESSION['user_email']; ?></p>
    <p><strong>Roli:</strong> <?php echo $_SESSION['user_role']; ?></p>
</div>

<?php include '../includes/footer.php'; ?>
