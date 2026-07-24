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
$service  = post_str('service_interest');
$budget   = post_str('budget_range');
$message  = post_str('message');

$errors = [];
if ($fullName === '' || mb_strlen($fullName) > 120) {
    $errors[] = 'Please enter your full name.';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid work email address.';
}
if ($service === '') {
    $errors[] = 'Please select a service you are interested in.';
}
if ($message === '') {
    $errors[] = 'Please tell us a bit about your project.';
}

if ($errors) {
    flash_set('error', implode(' ', $errors));
    header('Location: /contact#enquiry-form');
    exit;
}

try {
    $stmt = db()->prepare(
        'INSERT INTO enquiries (full_name, email, phone, company, service_interest, budget_range, message, ip_address)
         VALUES (:full_name, :email, :phone, :company, :service_interest, :budget_range, :message, :ip)'
    );
    $stmt->execute([
        'full_name'        => $fullName,
        'email'            => $email,
        'phone'            => $phone ?: null,
        'company'          => $company ?: null,
        'service_interest' => $service,
        'budget_range'     => $budget ?: null,
        'message'          => $message,
        'ip'               => client_ip(),
    ]);
    flash_set('success', 'Thanks — your consultation request has been received. Our advisory team will follow up within one business day.');
} catch (Throwable $e) {
    error_log('[enquiry-submit] ' . $e->getMessage());
    flash_set('error', 'Something went wrong while sending your request. Please try again.');
}

header('Location: /contact#enquiry-form');
exit;
