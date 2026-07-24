<?php
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'Enquiries';
$activeNav = 'enquiries';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $id = (int)($_POST['id'] ?? 0);

    if (isset($_POST['delete'])) {
        $stmt = db()->prepare('DELETE FROM enquiries WHERE id = :id');
        $stmt->execute(['id' => $id]);
        flash_set('success', 'Enquiry deleted.');
    } elseif (isset($_POST['status'])) {
        $status = $_POST['status'];
        if (in_array($status, ['new', 'contacted', 'closed'], true)) {
            $stmt = db()->prepare('UPDATE enquiries SET status = :status WHERE id = :id');
            $stmt->execute(['status' => $status, 'id' => $id]);
        }
    }
    header('Location: /admin/enquiries');
    exit;
}

$flash = flash_get();
$rows = [];
try {
    $rows = db()->query("SELECT * FROM enquiries ORDER BY created_at DESC")->fetchAll();
} catch (Throwable $e) {
    $rows = [];
}

require __DIR__ . '/includes/header.php';
?>

<?php if ($flash): ?><div class="alert alert-<?= $flash['type'] === 'error' ? 'error' : 'success' ?>"><?= h($flash['message']) ?></div><?php endif; ?>

<div class="panel">
  <div class="panel-head">
    <h2>Service Enquiries <span class="badge-pill"><?= count($rows) ?> total</span></h2>
    <input type="text" class="search-input" placeholder="Search enquiries…" data-table-search="#enquiriesTable">
  </div>
  <?php if (!$rows): ?>
    <div class="empty-state"><?= svg_icon('target') ?><p>No enquiries yet.</p></div>
  <?php else: ?>
  <table class="admin-table" id="enquiriesTable">
    <thead><tr><th>From</th><th>Service</th><th>Budget</th><th>Message</th><th>Status</th><th>Received</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
      <tr>
        <td>
          <b style="color:var(--text-0)"><?= h($r['full_name']) ?></b><br>
          <a href="mailto:<?= h($r['email']) ?>" style="font-size:.8rem"><?= h($r['email']) ?></a>
          <?php if ($r['company']): ?><br><span style="font-size:.78rem;color:var(--text-3)"><?= h($r['company']) ?></span><?php endif; ?>
        </td>
        <td><?= h($r['service_interest'] ?: '—') ?></td>
        <td><?= h($r['budget_range'] ?: '—') ?></td>
        <td style="max-width:280px"><?= nl2br(h(excerpt_text($r['message'], 140))) ?></td>
        <td>
          <form method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
            <select name="status" class="pill-select status-<?= h($r['status']) ?>" data-autosubmit>
              <option value="new" <?= $r['status'] === 'new' ? 'selected' : '' ?>>New</option>
              <option value="contacted" <?= $r['status'] === 'contacted' ? 'selected' : '' ?>>Contacted</option>
              <option value="closed" <?= $r['status'] === 'closed' ? 'selected' : '' ?>>Closed</option>
            </select>
          </form>
        </td>
        <td class="nowrap"><?= h(fmt_date($r['created_at'], 'M j, Y g:ia')) ?></td>
        <td>
          <div class="row-actions">
            <form method="post" data-confirm="Delete this enquiry permanently?">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
              <button type="submit" name="delete" value="1" class="icon-btn danger" aria-label="Delete"><?= svg_icon('trash') ?></button>
            </form>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
