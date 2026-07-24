<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/db.php';

qc_session_start();

$referer = $_SERVER['HTTP_REFERER'] ?? '/index.php';
$redirectTo = parse_url($referer, PHP_URL_PATH) ?: '/index.php';
if (!preg_match('#^/[a-z0-9_-]*\.php$#', $redirectTo)) {
    $redirectTo = '/index.php';
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $redirectTo);
    exit;
}

csrf_verify();

$email = post_str('email');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    flash_set('error', 'Please enter a valid email address to subscribe.');
    header('Location: ' . $redirectTo . '#newsletter');
    exit;
}

try {
    // Newsletter signups are stored alongside contact messages so they
    // show up in the existing admin > Contacts inbox (subject flags them).
    $stmt = db()->prepare(
        'INSERT INTO contacts (full_name, email, subject, message, ip_address)
         VALUES (:full_name, :email, :subject, :message, :ip)'
    );
    $stmt->execute([
        'full_name' => 'Newsletter Subscriber',
        'email'     => $email,
        'subject'   => 'Newsletter Signup',
        'message'   => 'Requested to join the QuantAI Corp newsletter from the site footer.',
        'ip'        => client_ip(),
    ]);
    flash_set('success', 'You are subscribed — thanks for staying in the loop.');
} catch (Throwable $e) {
    error_log('[newsletter-submit] ' . $e->getMessage());
    flash_set('error', 'Something went wrong. Please try again.');
}

header('Location: ' . $redirectTo . '#newsletter');
exit;
