<?php
$page_title = "Dashboard";
include '../includes/header.php';
include '../includes/navigation.php';
include '../includes/functions.php';
require_once '../config/db.php';
requireLogin();

// Statistikat
if(isAdmin()) {
    $total = $pdo->query("SELECT COUNT(*) FROM tasks")->fetchColumn();
    $completed = $pdo->query("SELECT COUNT(*) FROM tasks WHERE status='Completed'")->fetchColumn();
    $progress = $pdo->query("SELECT COUNT(*) FROM tasks WHERE status='In Progress'")->fetchColumn();
    $pending = $pdo->query("SELECT COUNT(*) FROM tasks WHERE status='Pending'")->fetchColumn();
} else {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]); $total = $stmt->fetchColumn();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE user_id = ? AND status='Completed'");
    $stmt->execute([$_SESSION['user_id']]); $completed = $stmt->fetchColumn();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE user_id = ? AND status='In Progress'");
    $stmt->execute([$_SESSION['user_id']]); $progress = $stmt->fetchColumn();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE user_id = ? AND status='Pending'");
    $stmt->execute([$_SESSION['user_id']]); $pending = $stmt->fetchColumn();
}
$completion_rate = $total > 0 ? round(($completed/$total)*100) : 0;
?>

<div class="stats-grid">
    <div class="stat-card"><div class="stat-number">📋 <?php echo $total; ?></div><div>Total Detyra</div></div>
    <div class="stat-card" style="background: linear-gradient(135deg,#10b981,#059669);"><div class="stat-number">✅ <?php echo $completed; ?></div><div>Të Përfunduara</div></div>
    <div class="stat-card" style="background: linear-gradient(135deg,#f59e0b,#d97706);"><div class="stat-number">🔄 <?php echo $progress; ?></div><div>Në Proces</div></div>
    <div class="stat-card" style="background: linear-gradient(135deg,#ef4444,#dc2626);"><div class="stat-number">⏳ <?php echo $pending; ?></div><div>Në Pritje</div></div>
</div>

<div class="card">
    <h2>👋 Mirë se vini, <?php echo cleanInput($_SESSION['user_name']); ?>!</h2>
    <div class="progress-bar"><div class="progress-fill" style="width:<?php echo $completion_rate; ?>%"><?php echo $completion_rate; ?>%</div></div>
    <a href="tasks.php" class="btn btn-primary">➕ Shto Detyrë</a>
    <?php if(isAdmin()): ?><a href="manage_users.php" class="btn" style="background:#64748b; color:white;">👥 Menaxho Përdoruesit</a><?php endif; ?>
</div>

<div class="card">
    <h3>💡 Citati i ditës</h3>
    <p id="quote">Loading...</p>
</div>

<script>
fetch('https://api.quotable.io/random')
    .then(res => res.json())
    .then(data => document.getElementById('quote').innerHTML = `“${data.content}” — ${data.author}`)
    .catch(() => document.getElementById('quote').innerText = 'Nuk u ngarkua citati.');
</script>

<?php include '../includes/footer.php'; ?>
