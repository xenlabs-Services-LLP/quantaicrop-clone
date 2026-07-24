<?php
/**
 * Shared <head> + site header. The including page must define, before
 * requiring this file: $pageTitle, $pageDescription, $pageSlug (e.g. 'about.php')
 * Optional: $pageSchema (raw JSON-LD string to inject).
 */
if (!defined('QC_BOOTSTRAPPED')) {
    define('QC_BOOTSTRAPPED', true);
    require_once __DIR__ . '/functions.php';
}
$pageTitle       = $pageTitle ?? SITE_NAME . ' — ' . SITE_TAGLINE;
$pageDescription = $pageDescription ?? 'AI-driven IT Consulting, Software Solutions & Talent Intelligence for enterprise-grade digital transformation.';
$pageSlug        = $pageSlug ?? 'index.php';
$canonicalUrl    = SITE_URL . '/' . ltrim($pageSlug, '/');
?><!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= h($pageTitle) ?></title>
<meta name="description" content="<?= h($pageDescription) ?>">
<meta name="author" content="<?= h(SITE_NAME) ?>">
<link rel="canonical" href="<?= h($canonicalUrl) ?>">
<meta name="robots" content="index, follow">
<meta name="theme-color" content="#05070d">

<!-- Open Graph -->
<meta property="og:type" content="website">
<meta property="og:site_name" content="<?= h(SITE_NAME) ?>">
<meta property="og:title" content="<?= h($pageTitle) ?>">
<meta property="og:description" content="<?= h($pageDescription) ?>">
<meta property="og:url" content="<?= h($canonicalUrl) ?>">
<meta property="og:image" content="<?= h(SITE_URL) ?>/assets/images/og-cover.svg">

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= h($pageTitle) ?>">
<meta name="twitter:description" content="<?= h($pageDescription) ?>">

<link rel="icon" type="image/svg+xml" href="/assets/images/favicon.svg">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css">

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "<?= h(COMPANY_LEGAL) ?>",
  "url": "<?= h(SITE_URL) ?>",
  "logo": "<?= h(SITE_URL) ?>/assets/images/favicon.svg",
  "description": "<?= h($pageDescription) ?>",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "<?= h(COMPANY_ADDRESS_1 . ', ' . COMPANY_ADDRESS_2) ?>",
    "addressLocality": "Charlotte",
    "addressRegion": "NC",
    "postalCode": "28262",
    "addressCountry": "US"
  },
  "contactPoint": {
    "@type": "ContactPoint",
    "telephone": "<?= h(COMPANY_PHONE) ?>",
    "contactType": "customer service",
    "email": "<?= h(COMPANY_EMAIL) ?>"
  },
  "sameAs": ["<?= h(SOCIAL_LINKEDIN) ?>", "<?= h(SOCIAL_FACEBOOK) ?>", "<?= h(SOCIAL_INSTAGRAM) ?>"]
}
</script>
<?php if (!empty($pageSchema)): ?>
<script type="application/ld+json"><?= $pageSchema ?></script>
<?php endif; ?>
</head>
<body>
<a href="#main" class="skip-link">Skip to main content</a>
<div class="page-loader" aria-hidden="true"><div class="loader-mark"></div></div>
<div class="cursor-glow" aria-hidden="true"></div>
<div class="nav-progress" aria-hidden="true"></div>

<header class="site-header" id="siteHeader">
  <div class="container nav-row">
    <a href="/index.php" class="brand">
      <span class="brand-mark"><?= svg_icon('brand') ?></span>
      QuantAI Corp
    </a>
    <nav class="nav-links" aria-label="Primary">
      <?php foreach (main_nav() as $item): ?>
        <a href="<?= h($item['href']) ?>"><?= h($item['label']) ?></a>
      <?php endforeach; ?>
    </nav>
    <div class="nav-cta">
      <a href="/contact.php" class="btn btn-ghost btn-sm">Get in Touch</a>
      <a href="/careers.php" class="btn btn-primary btn-sm magnetic">Apply Now <span class="arrow"><?= svg_icon('arrow') ?></span></a>
      <button class="nav-toggle" id="navToggle" aria-label="Open menu" aria-expanded="false" aria-controls="mobileDrawer">
        <span></span>
      </button>
    </div>
  </div>
</header>

<div class="drawer-overlay" id="drawerOverlay"></div>
<nav class="mobile-drawer" id="mobileDrawer" aria-label="Mobile">
  <?php foreach (main_nav() as $item): ?>
    <a href="<?= h($item['href']) ?>"><?= h($item['label']) ?></a>
  <?php endforeach; ?>
  <a href="/careers.php" class="btn btn-primary btn-block" style="margin-top:24px">Apply Now</a>
</nav>

<main id="main">
