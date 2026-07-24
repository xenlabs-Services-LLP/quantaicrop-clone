<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/db.php';

qc_session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /contact');
    exit;
}

csrf_verify();

$fullName = post_str('full_name');
$email    = post_str('email');
$phone    = post_str('phone');
$company  = post_str('company');
$subject  = post_str('subject');
$message  = post_str('message');

$errors = [];
if ($fullName === '' || mb_strlen($fullName) > 120) {
    $errors[] = 'Please enter your full name.';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
}
if ($message === '') {
    $errors[] = 'Please include a message.';
}

if ($errors) {
    flash_set('error', implode(' ', $errors));
    header('Location: /contact#contact-form');
    exit;
}

try {
    $stmt = db()->prepare(
        'INSERT INTO contacts (full_name, email, phone, company, subject, message, ip_address)
         VALUES (:full_name, :email, :phone, :company, :subject, :message, :ip)'
    );
    $stmt->execute([
        'full_name' => $fullName,
        'email'     => $email,
        'phone'     => $phone ?: null,
        'company'   => $company ?: null,
        'subject'   => $subject ?: null,
        'message'   => $message,
        'ip'        => client_ip(),
    ]);
    flash_set('success', 'Thanks for reaching out — a member of our team will respond within one business day.');
} catch (Throwable $e) {
    error_log('[contact-submit] ' . $e->getMessage());
    flash_set('error', 'Something went wrong while sending your message. Please try again.');
}

header('Location: /contact#contact-form');
exit;
