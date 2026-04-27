<?php
$page_title = "Dashboard";
include '../includes/header.php';
include '../includes/navigation.php';
include '../includes/functions.php';

requireLogin();


if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [];
    $_SESSION['next_task_id'] = 1;
}


$total = 0;
$completed = 0;
$pending = 0;
$progress = 0;

foreach($_SESSION['tasks'] as $task) {
    if($_SESSION['user_role'] == 'admin' || $task['assignedTo'] == $_SESSION['user_id']) {
        $total++;
        if($task['status'] == 'Completed') $completed++;
        elseif($task['status'] == 'Pending') $pending++;
        elseif($task['status'] == 'In Progress') $progress++;
    }
}

$completion_rate = $total > 0 ? round(($completed / $total) * 100) : 0;
?>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number">📋 <?php echo $total; ?></div>
        <div class="stat-label">Total Detyra</div>
    </div>
    <div class="stat-card" style="background: linear-gradient(135deg, #10b981, #059669);">
        <div class="stat-number">✅ <?php echo $completed; ?></div>
        <div class="stat-label">Të Përfunduara</div>
    </div>
    <div class="stat-card" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
        <div class="stat-number">🔄 <?php echo $progress; ?></div>
        <div class="stat-label">Në Proces</div>
    </div>
    <div class="stat-card" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
        <div class="stat-number">⏳ <?php echo $pending; ?></div>
        <div class="stat-label">Në Pritje</div>
    </div>
</div>

<div class="card">
    <h2>👋 Mirë se vini, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
    
    <div style="margin: 20px 0; background: #e0e0e0; border-radius: 20px; overflow: hidden;">
        <div style="width: <?php echo $completion_rate; ?>%; background: linear-gradient(90deg, #10b981, #3b82f6); height: 40px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
            <?php echo $completion_rate; ?>
        </div>
    </div>
    
    <div style="display: flex; gap: 15px; margin-top: 20px;">
        <a href="tasks.php" class="btn btn-primary">➕ Shto Detyrë të Re</a>
        <a href="profile.php" class="btn" style="background: #64748b; color: white;">👤 Menaxho Profilin</a>
    </div>
</div>

<div class="card">
    <h3>📊 Përmbledhje e Shpejtë</h3>
    <p>🔹 Roli juaj: <strong><?php echo $_SESSION['user_role']; ?></strong></p>
    <p>🔹 Email: <strong><?php echo $_SESSION['user_id']; ?></strong></p>
    <?php if($total > 0): ?>
        <p>🔹 Ke përfunduar <strong><?php echo $completed; ?></strong> nga <strong><?php echo $total; ?></strong> detyra</p>
    <?php else: ?>
        <p>🔹 Nuk ke asnjë detyrë për momentin. <a href="tasks.php">Shto një tani!</a></p>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>