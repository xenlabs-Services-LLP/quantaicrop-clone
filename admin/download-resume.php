<?php
require_once __DIR__ . '/includes/auth.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare('SELECT resume_stored_name, resume_original_name FROM applications WHERE id = :id');
$stmt->execute(['id' => $id]);
$row = $stmt->fetch();

if (!$row || !$row['resume_stored_name']) {
    http_response_code(404);
    die('Resume not found.');
}

$path = __DIR__ . '/../uploads/resumes/' . $row['resume_stored_name'];
if (!is_file($path)) {
    http_response_code(404);
    die('Resume file is missing on disk.');
}

$downloadName = preg_replace('/[^A-Za-z0-9._-]/', '_', $row['resume_original_name'] ?: 'resume.pdf');

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $downloadName . '"');
header('Content-Length: ' . filesize($path));
header('X-Content-Type-Options: nosniff');
readfile($path);
exit;
