<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/db.php';

qc_session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /careers');
    exit;
}

csrf_verify();

$firstName  = post_str('first_name');
$lastName   = post_str('last_name');
$email      = post_str('email');
$mobile     = post_str('mobile');
$location   = post_str('location');
$education  = post_str('education');
$visaType   = post_str('visa_type');
$role       = post_str('role');
$tech       = post_str('technologies');
$experience = post_str('experience_years');

$errors = [];
foreach ([
    'First name' => $firstName, 'Last name' => $lastName, 'Location' => $location,
    'Education' => $education, 'Visa type' => $visaType, 'Role' => $role,
    'Technologies' => $tech, 'Years of experience' => $experience,
] as $label => $value) {
    if ($value === '') {
        $errors[] = "$label is required.";
    }
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
}
if (!in_array($visaType, VISA_TYPES, true)) {
    $errors[] = 'Please select a valid visa type.';
}

$storedResumeName = null;
$originalResumeName = $_FILES['resume']['name'] ?? '';

if (!$errors) {
    try {
        $storedResumeName = handle_resume_upload($_FILES['resume'] ?? []);
    } catch (RuntimeException $e) {
        $errors[] = $e->getMessage();
    }
}

if ($errors) {
    flash_set('error', implode(' ', $errors));
    header('Location: /careers#apply');
    exit;
}

try {
    $stmt = db()->prepare(
        'INSERT INTO applications
         (first_name, last_name, email, mobile, location, education, visa_type, role_applied, technologies, experience_years, resume_stored_name, resume_original_name, ip_address)
         VALUES (:first_name, :last_name, :email, :mobile, :location, :education, :visa_type, :role_applied, :technologies, :experience_years, :resume_stored, :resume_original, :ip)'
    );
    $stmt->execute([
        'first_name'       => $firstName,
        'last_name'        => $lastName,
        'email'            => $email,
        'mobile'           => $mobile,
        'location'         => $location,
        'education'        => $education,
        'visa_type'        => $visaType,
        'role_applied'     => $role,
        'technologies'     => $tech,
        'experience_years' => $experience,
        'resume_stored'    => $storedResumeName,
        'resume_original'  => $originalResumeName,
        'ip'               => client_ip(),
    ]);
    flash_set('success', 'Your application has been submitted. Our talent intelligence team will review it and reach out if there is a match.');
} catch (Throwable $e) {
    error_log('[application-submit] ' . $e->getMessage());
    flash_set('error', 'Something went wrong while submitting your application. Please try again.');
}

header('Location: /careers#apply');
exit;
