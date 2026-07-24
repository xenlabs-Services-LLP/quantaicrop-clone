<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/db.php';

qc_session_start();

if (!empty($_SESSION['admin_id'])) {
    header('Location: /admin/index.php');
    exit;
}

$error = '';
$timeout = isset($_GET['timeout']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();

    // Simple brute-force throttle: max 6 attempts per 10 minutes per session.
    $_SESSION['login_attempts'] = $_SESSION['login_attempts'] ?? [];
    $_SESSION['login_attempts'] = array_filter($_SESSION['login_attempts'], fn($t) => $t > time() - 600);

    if (count($_SESSION['login_attempts']) >= 6) {
        $error = 'Too many login attempts. Please wait a few minutes and try again.';
    } else {
        $username = post_str('username');
        $password = (string)($_POST['password'] ?? '');

        $stmt = db()->prepare('SELECT * FROM admin_users WHERE username = :u LIMIT 1');
        $stmt->execute(['u' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id']          = $user['id'];
            $_SESSION['admin_username']    = $user['username'];
            $_SESSION['admin_name']        = $user['full_name'];
            $_SESSION['admin_last_active'] = time();
            unset($_SESSION['login_attempts']);

            $upd = db()->prepare('UPDATE admin_users SET last_login = NOW() WHERE id = :id');
            $upd->execute(['id' => $user['id']]);

            header('Location: /admin/index.php');
            exit;
        }

        $_SESSION['login_attempts'][] = time();
        $error = 'Invalid username or password.';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login — QuantAI Corp</title>
<meta name="robots" content="noindex, nofollow">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css">
<link rel="stylesheet" href="/admin/assets/css/admin.css">
</head>
<body class="admin-auth-body bg-mesh">
<div class="auth-wrap">
  <div class="card auth-card">
    <div class="brand" style="justify-content:center;margin-bottom:8px">
      <span class="brand-mark"><?= svg_icon('brand') ?></span> QuantAI Corp
    </div>
    <p class="text-center" style="color:var(--text-3);margin-bottom:28px">CMS Admin Panel</p>

    <?php if ($timeout): ?>
      <div class="alert alert-error">Your session expired. Please sign in again.</div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="alert alert-error"><?= h($error) ?></div>
    <?php endif; ?>

    <form method="post" novalidate>
      <?= csrf_field() ?>
      <div class="field">
        <input type="text" name="username" id="username" placeholder=" " required autofocus autocomplete="username">
        <label for="username">Username</label>
      </div>
      <div class="field">
        <input type="password" name="password" id="password" placeholder=" " required autocomplete="current-password">
        <label for="password">Password</label>
      </div>
      <button type="submit" class="btn btn-primary btn-block">Sign In <span class="arrow"><?= svg_icon('arrow') ?></span></button>
    </form>
    <p class="text-center" style="margin-top:24px;font-size:.8rem"><a href="/index.php" style="color:var(--text-3)">← Back to site</a></p>
  </div>
</div>
</body>
</html>
