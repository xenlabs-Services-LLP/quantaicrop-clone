<?php
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'Dashboard';
$activeNav = 'dashboard';

$counts = ['contacts' => 0, 'enquiries' => 0, 'applications' => 0, 'blogs' => 0];
$newCounts = ['contacts' => 0, 'enquiries' => 0, 'applications' => 0];
try {
    $counts['contacts']     = (int) db()->query("SELECT COUNT(*) FROM contacts")->fetchColumn();
    $counts['enquiries']    = (int) db()->query("SELECT COUNT(*) FROM enquiries")->fetchColumn();
    $counts['applications'] = (int) db()->query("SELECT COUNT(*) FROM applications")->fetchColumn();
    $counts['blogs']        = (int) db()->query("SELECT COUNT(*) FROM blog_posts")->fetchColumn();
    $newCounts['contacts']     = (int) db()->query("SELECT COUNT(*) FROM contacts WHERE status='new'")->fetchColumn();
    $newCounts['enquiries']    = (int) db()->query("SELECT COUNT(*) FROM enquiries WHERE status='new'")->fetchColumn();
    $newCounts['applications'] = (int) db()->query("SELECT COUNT(*) FROM applications WHERE status='new'")->fetchColumn();

    $recentContacts = db()->query("SELECT full_name, email, subject, created_at FROM contacts ORDER BY created_at DESC LIMIT 5")->fetchAll();
    $recentApps      = db()->query("SELECT first_name, last_name, role_applied, created_at FROM applications ORDER BY created_at DESC LIMIT 5")->fetchAll();
} catch (Throwable $e) {
    $recentContacts = [];
    $recentApps = [];
}

require __DIR__ . '/includes/header.php';
?>

<div class="kpi-grid">
  <div class="kpi-card">
    <div class="kpi-icon"><?= svg_icon('mail') ?></div>
    <span>Contact Messages</span>
    <b><?= (int)$counts['contacts'] ?></b>
    <span style="color:var(--cyan)"><?= (int)$newCounts['contacts'] ?> new</span>
  </div>
  <div class="kpi-card">
    <div class="kpi-icon"><?= svg_icon('target') ?></div>
    <span>Enquiries</span>
    <b><?= (int)$counts['enquiries'] ?></b>
    <span style="color:var(--cyan)"><?= (int)$newCounts['enquiries'] ?> new</span>
  </div>
  <div class="kpi-card">
    <div class="kpi-icon"><?= svg_icon('people') ?></div>
    <span>Applications</span>
    <b><?= (int)$counts['applications'] ?></b>
    <span style="color:var(--cyan)"><?= (int)$newCounts['applications'] ?> new</span>
  </div>
  <div class="kpi-card">
    <div class="kpi-icon"><?= svg_icon('book') ?></div>
    <span>Blog Posts</span>
    <b><?= (int)$counts['blogs'] ?></b>
    <span>published</span>
  </div>
</div>

<div class="grid-12">
  <div class="panel" style="grid-column:span 6">
    <div class="panel-head"><h2>Recent Contact Messages</h2><a href="/admin/contacts.php" class="btn btn-ghost btn-sm">View All</a></div>
    <?php if ($recentContacts): ?>
      <table class="admin-table">
        <thead><tr><th>Name</th><th>Subject</th><th>Received</th></tr></thead>
        <tbody>
        <?php foreach ($recentContacts as $c): ?>
          <tr>
            <td><?= h($c['full_name']) ?><br><span style="color:var(--text-3);font-size:.78rem"><?= h($c['email']) ?></span></td>
            <td><?= h($c['subject'] ?: '—') ?></td>
            <td class="nowrap"><?= h(fmt_date($c['created_at'], 'M j, g:ia')) ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <div class="empty-state"><?= svg_icon('mail') ?><p>No contact messages yet.</p></div>
    <?php endif; ?>
  </div>

  <div class="panel" style="grid-column:span 6">
    <div class="panel-head"><h2>Recent Applications</h2><a href="/admin/applications.php" class="btn btn-ghost btn-sm">View All</a></div>
    <?php if ($recentApps): ?>
      <table class="admin-table">
        <thead><tr><th>Applicant</th><th>Role</th><th>Received</th></tr></thead>
        <tbody>
        <?php foreach ($recentApps as $a): ?>
          <tr>
            <td><?= h($a['first_name'] . ' ' . $a['last_name']) ?></td>
            <td><?= h($a['role_applied']) ?></td>
            <td class="nowrap"><?= h(fmt_date($a['created_at'], 'M j, g:ia')) ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <div class="empty-state"><?= svg_icon('people') ?><p>No applications yet.</p></div>
    <?php endif; ?>
  </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
