<?php
/**
 * QuantAI Corp — Shared helper functions.
 */

// ---------------------------------------------------------------------
// Site-wide constants (single source of truth for business info)
// ---------------------------------------------------------------------
const SITE_NAME        = 'QuantAI Corp';
const SITE_TAGLINE      = 'AI-Driven IT Consulting, Software Solutions & Talent Intelligence';
const SITE_URL          = 'https://www.quantaicorp.com';
const COMPANY_LEGAL     = 'QuantAI Corp LLC';
const COMPANY_ADDRESS_1 = '10926 David Taylor Drive';
const COMPANY_ADDRESS_2 = 'Suite 120 PMB1334';
const COMPANY_CITY      = 'Charlotte, NC 28262';
const COMPANY_PHONE     = '+1 980-272-8321';
const COMPANY_EMAIL     = 'hr@quantaicorp.com';
const SOCIAL_LINKEDIN   = 'https://www.linkedin.com/company/quantaicorp-llc/about/';
const SOCIAL_FACEBOOK   = 'https://www.facebook.com/profile.php?id=61587669915928';
const SOCIAL_INSTAGRAM  = 'https://www.instagram.com/quantaicorp/';

/**
 * Emit security headers, including a Content-Security-Policy with a
 * per-request nonce for inline JSON-LD <script> blocks. Call once, before
 * any HTML output. Returns the nonce so callers can attach it to any
 * inline <script> tags on the page (script-src stays strict — no
 * 'unsafe-inline' — everything else must be an external file).
 */
function send_security_headers(): string
{
    // The session MUST be started before any HTML is echoed — on hosts
    // with output_buffering off (common on shared hosting), starting it
    // later (e.g. inside csrf_field() deep in the page body) triggers
    // "headers already sent" warnings and silently breaks the session.
    // This function runs first thing in every page template, so it's the
    // one safe, guaranteed-early place to do it.
    qc_session_start();

    static $nonce = null;
    if ($nonce === null) {
        $nonce = base64_encode(random_bytes(16));
    }
    if (!headers_sent()) {
        header(
            "Content-Security-Policy: default-src 'self'; " .
            "script-src 'self' 'nonce-$nonce'; " .
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
            "font-src 'self' https://fonts.gstatic.com; " .
            "img-src 'self' data:; " .
            "connect-src 'self'; " .
            "frame-ancestors 'self'; base-uri 'self'; form-action 'self'"
        );
    }
    return $nonce;
}

/** Escape output for safe HTML rendering. */
function h(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/** Start a hardened session (call once, before any output). */
function qc_session_start(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_start();
    }
}

/** Generate (or reuse) a CSRF token for the current session. */
function csrf_token(): string
{
    qc_session_start();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/** Render a hidden CSRF input field. */
function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . h(csrf_token()) . '">';
}

/** Validate a submitted CSRF token; halts request on mismatch. */
function csrf_verify(): void
{
    qc_session_start();
    $submitted = $_POST['csrf_token'] ?? '';
    if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $submitted)) {
        http_response_code(403);
        die('Security check failed. Please go back, refresh the page, and try again.');
    }
}

/** Trim + collapse whitespace on a raw POST field. */
function post_str(string $key): string
{
    return trim((string)($_POST[$key] ?? ''));
}

/** Simple flash-message helper (stored in session, shown once). */
function flash_set(string $type, string $message): void
{
    qc_session_start();
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function flash_get(): ?array
{
    qc_session_start();
    if (empty($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

/** Basic client IP lookup (best-effort; not spoof-proof). */
function client_ip(): string
{
    return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
}

/**
 * Validate + move an uploaded résumé file. Returns the stored filename on
 * success, or throws a RuntimeException with a user-facing message.
 */
function handle_resume_upload(array $file): string
{
    $allowedExt  = ['pdf', 'doc', 'docx'];
    $allowedMime = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    ];
    $maxBytes = 5 * 1024 * 1024; // 5MB

    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Resume upload failed. Please choose a PDF or Word file and try again.');
    }
    if ($file['size'] > $maxBytes) {
        throw new RuntimeException('Resume file is too large (5MB max).');
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExt, true)) {
        throw new RuntimeException('Resume must be a PDF or Word document (.pdf, .doc, .docx).');
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    if (!in_array($mime, $allowedMime, true)) {
        throw new RuntimeException('That file does not look like a valid PDF or Word document.');
    }

    $uploadDir = __DIR__ . '/../uploads/resumes/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $storedName = bin2hex(random_bytes(16)) . '.' . $ext;
    if (!move_uploaded_file($file['tmp_name'], $uploadDir . $storedName)) {
        throw new RuntimeException('Could not save the uploaded resume. Please try again.');
    }

    return $storedName;
}

/** Turn a string into a URL-safe slug. */
function slugify(string $text): string
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    return trim($text, '-');
}

/** Format a datetime string for display. */
function fmt_date(string $datetime, string $format = 'M j, Y'): string
{
    $ts = strtotime($datetime);
    return $ts ? date($format, $ts) : $datetime;
}

/** Truncate plain text to a max length on a word boundary. */
function excerpt_text(string $text, int $length = 160): string
{
    $text = trim(strip_tags($text));
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    return mb_substr($text, 0, $length) . '…';
}

/** Curated navigation used across header + admin breadcrumbs. */
function main_nav(): array
{
    return [
        ['label' => 'Home', 'href' => '/index.php'],
        ['label' => 'About', 'href' => '/about.php'],
        ['label' => 'IT Consulting', 'href' => '/it-consulting.php'],
        ['label' => 'Software Engineering', 'href' => '/software-engineering.php'],
        ['label' => 'Careers', 'href' => '/careers.php'],
        ['label' => 'Insights', 'href' => '/blog.php'],
        ['label' => 'Contact', 'href' => '/contact.php'],
    ];
}

/** Static job openings config — edit this array to add/remove roles. */
function job_openings(): array
{
    return [
        ['title' => 'Senior Cloud Architect', 'location' => 'Charlotte, NC', 'experience' => '8+ years', 'type' => 'Full-time'],
        ['title' => 'AI/ML Engineer', 'location' => 'Remote', 'experience' => '5+ years', 'type' => 'Full-time'],
        ['title' => 'DevOps Engineer', 'location' => 'Charlotte, NC', 'experience' => '4+ years', 'type' => 'Full-time'],
        ['title' => 'Full Stack Developer', 'location' => 'Remote', 'experience' => '3+ years', 'type' => 'Contract'],
        ['title' => 'Cybersecurity Analyst', 'location' => 'Charlotte, NC', 'experience' => '5+ years', 'type' => 'Full-time'],
        ['title' => 'Data Engineer', 'location' => 'Remote', 'experience' => '4+ years', 'type' => 'Full-time'],
        ['title' => 'Solutions Architect', 'location' => 'Charlotte, NC', 'experience' => '7+ years', 'type' => 'Full-time'],
        ['title' => 'QA Engineer', 'location' => 'Remote', 'experience' => '3+ years', 'type' => 'Contract'],
        ['title' => 'Project Manager', 'location' => 'Charlotte, NC', 'experience' => '6+ years', 'type' => 'Full-time'],
        ['title' => 'Business Analyst', 'location' => 'Remote', 'experience' => '4+ years', 'type' => 'Full-time'],
    ];
}

const VISA_TYPES = ['H-1B', 'L-1A', 'L-1B', 'O-1', 'TN', 'E-2', 'H-4 EAD', 'OPT', 'CPT', 'Other'];

/**
 * Placeholder "trusted by" wordmarks for the homepage logo marquee.
 * These are original, generic, fictitious enterprise names/marks —
 * NOT real client logos. Swap in real client names + logo files once
 * client permission/agreements are in place.
 */
function partner_logos(): array
{
    $names = [
        ['Nova Systems', 'hex'], ['Meridian Group', 'circle'], ['Vertex Analytics', 'triangle'],
        ['Ironclad Holdings', 'square'], ['Cobalt Dynamics', 'diamond'], ['Northbridge Capital', 'hex'],
        ['Aegis Financial', 'circle'], ['Solstice Health', 'triangle'], ['Pinnacle Logistics', 'square'],
        ['Blueline Manufacturing', 'diamond'], ['Summit Retail Group', 'hex'], ['Cascade Energy', 'circle'],
        ['Harbor Insurance', 'triangle'], ['Redstone Media', 'square'], ['Lumen Biotech', 'diamond'],
        ['Anchor Telecom', 'hex'], ['Fairway Real Estate', 'circle'], ['Granite Infrastructure', 'triangle'],
        ['Orbit Aerospace', 'square'], ['Wells & Carter Legal', 'diamond'],
    ];
    $colors = ['chip-blue', 'chip-cyan', 'chip-violet'];
    $logos = [];
    foreach ($names as $i => [$name, $glyph]) {
        $logos[] = ['name' => $name, 'glyph' => $glyph, 'color' => $colors[$i % 3]];
    }
    return $logos;
}

/** Small monochrome glyph used inside each logo-marquee wordmark. */
function logo_glyph(string $shape): string
{
    $shapes = [
        'hex'      => '<path d="M12 3l7.5 4.33v9.34L12 21l-7.5-4.33V7.33L12 3z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>',
        'circle'   => '<circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="1.6"/>',
        'triangle' => '<path d="M12 4l8.5 15H3.5L12 4z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>',
        'square'   => '<rect x="4.5" y="4.5" width="15" height="15" rx="3" stroke="currentColor" stroke-width="1.6"/>',
        'diamond'  => '<path d="M12 3l9 9-9 9-9-9 9-9z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>',
    ];
    $body = $shapes[$shape] ?? $shapes['circle'];
    return '<svg class="logo-glyph" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">' . $body . '</svg>';
}

/**
 * Original, minimal stroke-icon set (24x24, currentColor) used across the
 * site. Keeping icons inline as SVG avoids external icon-font/library
 * downloads and keeps the visual language perfectly consistent.
 */
function svg_icon(string $name): string
{
    $icons = [
        'brand' => '<path d="M12 3l7.5 4.33v9.34L12 21l-7.5-4.33V7.33L12 3z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M12 12l7.5-4.34M12 12v9M12 12L4.5 7.66" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>',
        'ai' => '<circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.6"/><path d="M12 2v4M12 18v4M4.9 4.9l2.8 2.8M16.3 16.3l2.8 2.8M2 12h4M18 12h4M4.9 19.1l2.8-2.8M16.3 7.7l2.8-2.8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>',
        'cloud' => '<path d="M7 18h10a4 4 0 0 0 .5-7.97A5.5 5.5 0 0 0 7.1 9.05 4 4 0 0 0 7 18z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>',
        'shield' => '<path d="M12 3l7 3v6c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6l7-3z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>',
        'devops' => '<path d="M12 4a8 8 0 1 1-6.93 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><path d="M5 4v4h4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>',
        'chip' => '<rect x="7" y="7" width="10" height="10" rx="1.5" stroke="currentColor" stroke-width="1.6"/><path d="M9 3v3M15 3v3M9 18v3M15 18v3M3 9h3M3 15h3M18 9h3M18 15h3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>',
        'target' => '<circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="12" r=".8" fill="currentColor"/>',
        'people' => '<circle cx="9" cy="8" r="3" stroke="currentColor" stroke-width="1.6"/><path d="M3.5 19c.6-3 2.7-4.8 5.5-4.8s4.9 1.8 5.5 4.8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><path d="M15.5 6a3 3 0 1 1 0 5.98M17.5 19c-.3-1.9-1.1-3.3-2.4-4.2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>',
        'scale' => '<path d="M12 3v18M7 7l-4 8a4 4 0 0 0 8 0l-4-8zM17 7l-4 8a4 4 0 0 0 8 0l-4-8z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M4 21h16" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>',
        'gauge' => '<path d="M4 15a8 8 0 1 1 16 0" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><path d="M12 15l4-5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><path d="M12 15h.01" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>',
        'api' => '<rect x="3" y="9" width="6" height="6" rx="1.4" stroke="currentColor" stroke-width="1.6"/><rect x="15" y="9" width="6" height="6" rx="1.4" stroke="currentColor" stroke-width="1.6"/><path d="M9 12h6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>',
        'layers' => '<path d="M12 3l8 4.5L12 12 4 7.5 12 3z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M4 12l8 4.5L20 12M4 16.5L12 21l8-4.5" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>',
        'mail' => '<rect x="3" y="5" width="18" height="14" rx="2" stroke="currentColor" stroke-width="1.6"/><path d="M3 7l9 6 9-6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>',
        'phone' => '<path d="M5 4h3.5l1.5 4-2 1.5a12 12 0 0 0 6 6l1.5-2 4 1.5V19a2 2 0 0 1-2 2A16 16 0 0 1 3 5a2 2 0 0 1 2-1z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>',
        'pin' => '<path d="M12 21s7-6.3 7-11.5A7 7 0 0 0 5 9.5C5 14.7 12 21 12 21z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><circle cx="12" cy="9.5" r="2.4" stroke="currentColor" stroke-width="1.6"/>',
        'check' => '<path d="M4 12l5.5 5.5L20 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
        'arrow' => '<path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>',
        'linkedin' => '<path d="M6.94 8.5H4V20h2.94V8.5zM5.47 4a1.7 1.7 0 1 0 0 3.4 1.7 1.7 0 0 0 0-3.4zM20 20h-2.94v-5.86c0-1.4-.5-2.35-1.75-2.35-.95 0-1.52.64-1.77 1.26-.09.22-.11.53-.11.84V20H10.5s.04-9.98 0-11.5h2.93v1.63c.39-.6 1.09-1.46 2.65-1.46 1.93 0 3.38 1.26 3.38 3.98V20z" fill="currentColor"/>',
        'facebook' => '<path d="M14 9h2.5V6.2h-2.7C11.4 6.2 10 7.6 10 9.9V12H8v3h2v6h3v-6h2.4l.6-3H13v-1.7c0-.8.3-1.3 1-1.3z" fill="currentColor"/>',
        'instagram' => '<rect x="3.5" y="3.5" width="17" height="17" rx="5" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.6"/><circle cx="17.2" cy="6.8" r="1" fill="currentColor"/>',
        'menu-none' => '',
        'calendar' => '<rect x="3.5" y="5" width="17" height="16" rx="2" stroke="currentColor" stroke-width="1.6"/><path d="M8 3v4M16 3v4M3.5 10h17" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>',
        'book' => '<path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21.5v-16z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M4 19V5.5" stroke="currentColor" stroke-width="1.6"/>',
        'trash' => '<path d="M4 7h16M9 7V4.5A1.5 1.5 0 0 1 10.5 3h3A1.5 1.5 0 0 1 15 4.5V7M6.5 7l.7 12a2 2 0 0 0 2 1.9h5.6a2 2 0 0 0 2-1.9L17.5 7" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>',
        'eye' => '<path d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.6"/>',
        'download' => '<path d="M12 4v11M8 11l4 4 4-4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 17v2.5A1.5 1.5 0 0 0 5.5 21h13a1.5 1.5 0 0 0 1.5-1.5V17" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>',
        'edit' => '<path d="M4 20h4L18.5 9.5a2 2 0 0 0 0-2.8l-1.2-1.2a2 2 0 0 0-2.8 0L4 15.5V20z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M13 6.5l4 4" stroke="currentColor" stroke-width="1.6"/>',
        'plus' => '<path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>',
    ];
    $body = $icons[$name] ?? $icons['check'];
    return '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">' . $body . '</svg>';
}
