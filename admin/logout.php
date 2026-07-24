<?php
require_once __DIR__ . '/../includes/functions.php';
qc_session_start();
session_unset();
session_destroy();
header('Location: /admin/login');
exit;
