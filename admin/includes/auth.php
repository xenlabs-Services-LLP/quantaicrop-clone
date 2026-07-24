<?php
/**
 * Admin authentication guard. Require this at the very top of every
 * protected admin page (before any output).
 */
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../config/db.php';

qc_session_start();

if (empty($_SESSION['admin_id'])) {
    header('Location: /admin/login');
    exit;
}

// Basic idle timeout (60 minutes)
$idleLimit = 60 * 60;
if (!empty($_SESSION['admin_last_active']) && (time() - $_SESSION['admin_last_active']) > $idleLimit) {
    session_unset();
    session_destroy();
    header('Location: /admin/login?timeout=1');
    exit;
}
$_SESSION['admin_last_active'] = time();

function current_admin(): array
{
    return [
        'id'       => $_SESSION['admin_id'] ?? null,
        'username' => $_SESSION['admin_username'] ?? '',
        'name'     => $_SESSION['admin_name'] ?? '',
    ];
}
