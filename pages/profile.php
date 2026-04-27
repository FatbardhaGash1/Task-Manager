<?php
$page_title = "Profili";
include '../includes/header.php';
include '../includes/navigation.php';
include '../includes/functions.php';

requireLogin();

$theme = $_COOKIE['theme'] ?? 'light';
$lastUser = $_COOKIE['last_user'] ?? 'Nuk ka';

if(isset($_POST['theme'])) {
    setcookie('theme', $_POST['theme'], time() + 86400 * 30, '/');
    header("Location: profile.php");
    exit();
}
?>

<div class="card">
    <h2>👤 Profili im</h2>
    
    <div style="display: grid; gap: 20px;">
        <div>
            <label>Emri</label>
            <input type="text" value="<?php echo htmlspecialchars($_SESSION['user_name']); ?>" readonly>
        </div>
        
        <div>
            <label>Email</label>
            <input type="email" value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>" readonly>
        </div>
        
        <div>
            <label>Roli</label>
            <input type="text" value="<?php echo $_SESSION['user_role']; ?>" readonly>
        </div>
        
        <div>
            <label>Përdoruesi i fundit nga cookie</label>
            <input type="text" value="<?php echo $lastUser; ?>" readonly>
        </div>
    </div>
</div>

<div class="card">
    <h3>🎨 Preferencat</h3>
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
    <p><strong>Roli:</strong> <?php echo $_SESSION['user_role']; ?></p>
</div>

<?php include '../includes/footer.php'; ?>