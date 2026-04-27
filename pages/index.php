<?php
$page_title = "Ballina";
include '../includes/header.php';
include '../includes/navigation.php';
?>
<div class="card" style="text-align: center;">
    <h1 style="font-size: 48px; margin-bottom: 20px;">❃ Task Manager ❃</h1>  
    <p style="font-size: 18px; margin-bottom: 30px;">Sistemi profesional për menaxhimin e detyrave ditore</p>
    
    <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="dashboard.php" class="btn btn-primary">📊 Shko te Dashboard</a>
            <a href="tasks.php" class="btn btn-primary">✅ Menaxho Detyrat</a>
        <?php else: ?>
            <a href="login.php" class="btn btn-primary">🔐 Fillo Tani</a>
        <?php endif; ?>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 20px;">
    <div class="card">
        <h3>📋 Menaxho Detyrat</h3>
        <p>Shto, fshij dhe përditëso statusin e detyrave tua lehtësisht.</p>
    </div>
    <div class="card">
        <h3>📊 Statistikat</h3>
        <p>Shiko progresin tënd dhe statistika të detajuara.</p>
    </div>
    <div class="card">
        <h3>🎨 Dizajn Modern</h3>
        <p>Ndërfaqe e bukur dhe responsive për çdo pajisje.</p>
    </div>
</div>

<?php include '../includes/footer.php'; ?>