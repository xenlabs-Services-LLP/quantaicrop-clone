<?php
/**
 * Admin layout header. Requires auth.php to already be included.
 * Expects: $pageTitle (string), $activeNav (string key matching nav items below).
 */
$admin = current_admin();
$activeNav = $activeNav ?? '';

$navItems = [
    ['key' => 'dashboard',    'label' => 'Dashboard',    'href' => '/admin/index.php',        'icon' => 'gauge'],
    ['key' => 'contacts',     'label' => 'Contacts',     'href' => '/admin/contacts.php',     'icon' => 'mail'],
    ['key' => 'enquiries',    'label' => 'Enquiries',    'href' => '/admin/enquiries.php',    'icon' => 'target'],
    ['key' => 'applications', 'label' => 'Applications', 'href' => '/admin/applications.php', 'icon' => 'people'],
    ['key' => 'blogs',        'label' => 'Blog Posts',   'href' => '/admin/blogs.php',        'icon' => 'book'],
];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= h($pageTitle ?? 'Admin') ?> — QuantAI Corp CMS</title>
<meta name="robots" content="noindex, nofollow">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css">
<link rel="stylesheet" href="/admin/assets/css/admin.css">
</head>
<body class="bg-mesh">
<div class="admin-body">
  <div class="drawer-overlay" id="sidebarOverlay"></div>
  <aside class="admin-sidebar" id="adminSidebar">
    <a href="/admin/index.php" class="brand">
      <span class="brand-mark"><?= svg_icon('brand') ?></span> QuantAI CMS
    </a>
    <nav class="admin-nav">
      <?php foreach ($navItems as $item): ?>
        <a href="<?= h($item['href']) ?>" class="<?= $activeNav === $item['key'] ? 'is-active' : '' ?>">
          <?= svg_icon($item['icon']) ?> <?= h($item['label']) ?>
        </a>
      <?php endforeach; ?>
      <div class="admin-nav-group">Site</div>
      <a href="/index.php" target="_blank"><?= svg_icon('arrow') ?> View Website</a>
      <a href="/admin/logout.php"><?= svg_icon('check') ?> Sign Out</a>
    </nav>
  </aside>

  <div class="admin-main">
    <div class="admin-topbar">
      <div class="flex items-center gap-3">
        <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle menu"><?= svg_icon('layers') ?></button>
        <h1><?= h($pageTitle ?? 'Dashboard') ?></h1>
      </div>
      <div class="admin-user">
        <div style="text-align:right">
          <div style="color:var(--text-0);font-size:.85rem;font-weight:600"><?= h($admin['name'] ?: $admin['username']) ?></div>
          <div style="color:var(--text-3);font-size:.75rem">Administrator</div>
        </div>
        <div class="admin-avatar"><?= h(strtoupper(substr($admin['name'] ?: $admin['username'], 0, 1))) ?></div>
      </div>
    </div>
    <div class="admin-content">
