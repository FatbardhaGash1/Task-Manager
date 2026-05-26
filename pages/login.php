<?php

$page_title = "Kyçu";

include '../includes/header.php';
include '../includes/navigation.php';
include '../includes/functions.php';
include '../includes/db.php';

$error = "";

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = cleanInput($_POST['email']);
    $password = $_POST['password'];

    if(!validateEmail($email)) {

        $error = "Email nuk është valid!";

    } else {

        try {

            $stmt = $pdo->prepare(
                "SELECT * FROM users WHERE email = ?"
            );

            $stmt->execute([$email]);

            $user = $stmt->fetch();

            if(
                $user &&
                $password == '123456'
            ) {

                $_SESSION['user_id'] = $user['id'];

                $_SESSION['user_role'] = $user['role'];

                $_SESSION['user_name'] = $user['name'];

                setcookie(
                    'last_user',
                    $email,
                    time() + 86400 * 30,
                    '/'
                );

                if(!isset($_COOKIE['theme'])) {

                    setcookie(
                        'theme',
                        'light',
                        time() + 86400 * 30,
                        '/'
                    );

                }

                header('Location: dashboard.php');

                exit();

            } else {

                $error = "Kredenciale të pasakta!";

            }

        } catch(PDOException $e) {

            $error = "Gabim në databazë!";

        }

    }

}
?>

<div class="card" style="max-width: 500px; margin: 0 auto;">

    <h2 style="text-align: center; margin-bottom: 30px;">
        🔐 Hyr në llogarinë tënde
    </h2>

    <?php if($error): ?>

        <div class="alert alert-error">
            ❌ <?php echo $error; ?>
        </div>

    <?php endif; ?>

    <form method="POST">

        <div class="form-group">

            <label>📧 Adresa Email</label>

            <input
                type="email"
                name="email"
                required
                placeholder="email@example.com"
            >

        </div>

        <div class="form-group">

            <label>🔒 Fjalëkalimi</label>

            <input
                type="password"
                name="password"
                required
                placeholder="••••••••"
            >

        </div>

        <button
            type="submit"
            class="btn btn-primary"
            style="width: 100%;"
        >
            Kyçu
        </button>

    </form>

    <div
        style="
            margin-top: 30px;
            padding: 20px;
            background: #f0fdf4;
            border-radius: 12px;
        "
    >

        <p style="font-weight: 600; margin-bottom: 10px;">
            🔑 Të dhënat për testim:
        </p>

        <p>
            <strong>Admin:</strong>
            admin@example.com / admin123
        </p>

        <p>
            <strong>User:</strong>
            user@example.com / user123
        </p>

    </div>

</div>

<?php include '../includes/footer.php'; ?>