<?php
/**
 * QuantAI Corp — Database connection (PDO, prepared statements only).
 * Update the constants below to match your local MySQL / XAMPP setup.
 */

const DB_HOST = '127.0.0.1';
const DB_NAME = 'u997826032_cms_qua';
const DB_USER = 'u997826032_cms_quant_root';
const DB_PASS = 'UEglkqe!^#ksajqvdjw132';
const DB_CHARSET = 'utf8mb4';

/**
 * Returns a shared PDO instance. Fails safe: on connection error we log
 * server-side and show a generic message (no DB details leak to visitors).
 */
function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
        return $pdo;
    } catch (PDOException $e) {
        error_log('[QuantAI DB ERROR] ' . $e->getMessage());
        http_response_code(500);
        die('The site is temporarily unavailable. Please try again shortly.');
    }
}
