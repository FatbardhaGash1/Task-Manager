<?php
$page_title = "Dashboard";
include '../includes/header.php';
include '../includes/navigation.php';
include '../includes/functions.php';
require_once '../config/db.php';
requireLogin();

// Get statistics based on role
if (isAdmin()) {
    // Admin sees all tasks
    $total = $pdo->query("SELECT COUNT(*) FROM tasks")->fetchColumn();
    $completed = $pdo->query("SELECT COUNT(*) FROM tasks WHERE status = 'Completed'")->fetchColumn();
    $inProgress = $pdo->query("SELECT COUNT(*) FROM tasks WHERE status = 'In Progress'")->fetchColumn();
    $pending = $pdo->query("SELECT COUNT(*) FROM tasks WHERE status = 'Pending'")->fetchColumn();
    
    // Recent tasks (last 5)
    $recentStmt = $pdo->query("SELECT t.*, u.name AS user_name FROM tasks t 
                               LEFT JOIN users u ON t.user_id = u.id 
                               ORDER BY t.created_at DESC LIMIT 5");
    $recentTasks = $recentStmt->fetchAll();
} else {
    // Regular user sees only their tasks
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]); $total = $stmt->fetchColumn();
    
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE user_id = ? AND status = 'Completed'");
    $stmt->execute([$_SESSION['user_id']]); $completed = $stmt->fetchColumn();
    
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE user_id = ? AND status = 'In Progress'");
    $stmt->execute([$_SESSION['user_id']]); $inProgress = $stmt->fetchColumn();
    
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE user_id = ? AND status = 'Pending'");
    $stmt->execute([$_SESSION['user_id']]); $pending = $stmt->fetchColumn();
    
    // Recent tasks (last 5)
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
    $stmt->execute([$_SESSION['user_id']]);
    $recentTasks = $stmt->fetchAll();
}

$completionRate = $total > 0 ? round(($completed / $total) * 100) : 0;
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
        <div class="stat-number">🔄 <?php echo $inProgress; ?></div>
        <div class="stat-label">Në Proces</div>
    </div>
    <div class="stat-card" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
        <div class="stat-number">⏳ <?php echo $pending; ?></div>
        <div class="stat-label">Në Pritje</div>
    </div>
</div>

<div class="card">
    <h2>👋 Mirë se vini, <?php echo cleanInput($_SESSION['user_name']); ?>!</h2>
    
    <div class="progress-bar">
        <div class="progress-fill" style="width: <?php echo $completionRate; ?>%;">
            <?php echo $completionRate; ?>% e përfunduar
        </div>
    </div>
    
    <div style="display: flex; gap: 15px; margin-top: 20px; flex-wrap: wrap;">
        <a href="tasks.php" class="btn btn-primary">➕ Shto Detyrë të Re</a>
        <?php if (isAdmin()): ?>
            <a href="manage_users.php" class="btn" style="background: #64748b; color: white;">👥 Menaxho Përdoruesit</a>
        <?php endif; ?>
        <a href="profile.php" class="btn" style="background: #8b5cf6; color: white;">👤 Menaxho Profilin</a>
    </div>
</div>

<div class="card">
    <h3>📋 Detyrat e Fundit (5 të fundit)</h3>
    <?php if (count($recentTasks) > 0): ?>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: var(--primary-light);">
                    <th style="padding: 10px; text-align: left;">Titulli</th>
                    <th style="padding: 10px; text-align: left;">Statusi</th>
                    <th style="padding: 10px; text-align: left;">Prioriteti</th>
                    <?php if (isAdmin()): ?>
                        <th style="padding: 10px; text-align: left;">Përdoruesi</th>
                    <?php endif; ?>
                    <th style="padding: 10px; text-align: left;">Veprimi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentTasks as $task): ?>
                    <tr style="border-bottom: 1px solid #ddd;">
                        <td style="padding: 8px;"><?php echo cleanInput($task['title']); ?></td>
                        <td style="padding: 8px;">
                            <?php
                            $badge = '';
                            if ($task['status'] == 'Completed') $badge = 'badge-completed';
                            elseif ($task['status'] == 'In Progress') $badge = 'badge-progress';
                            else $badge = 'badge-pending';
                            ?>
                            <span class="badge <?php echo $badge; ?>"><?php echo $task['status']; ?></span>
                        </td>
                        <td style="padding: 8px;"><?php echo $task['priority']; ?></td>
                        <?php if (isAdmin()): ?>
                            <td style="padding: 8px;"><?php echo cleanInput($task['user_name'] ?? 'N/A'); ?></td>
                        <?php endif; ?>
                        <td style="padding: 8px;">
                            <a href="tasks.php" class="btn-small" style="background: #3b82f6; color: white;">Shiko</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nuk ke asnjë detyrë ende. <a href="tasks.php">Shto detyrën e parë!</a></p>
    <?php endif; ?>
</div>

<div class="card">
    <h3>💡 Citati i ditës</h3>
    <p id="quote">Loading...</p>
</div>

<script>
fetch('https://api.quotable.io/random')
    .then(res => res.json())
    .then(data => {
        document.getElementById('quote').innerHTML = `“${data.content}” — ${data.author}`;
    })
    .catch(() => document.getElementById('quote').innerText = 'Nuk u ngarkua citati.');
</script>

<?php include '../includes/footer.php'; ?>
