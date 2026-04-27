<div class="header">
    <div class="logo">
        <h1>❃ Task Manager</h1>
        <p>Menaxho detyrat tua me efikasitet</p>
    </div>
    <div class="nav">
        <a href="index.php">🏠 Ballina</a>
        <a href="dashboard.php">📊 Dashboard</a>
        <a href="tasks.php">✅ Detyrat</a>
        <a href="profile.php">👤 Profili</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="logout.php">🚪 Dil</a>
        <?php else: ?>
            <a href="login.php">🔐 Kyçu</a>
        <?php endif; ?>
    </div>
</div>