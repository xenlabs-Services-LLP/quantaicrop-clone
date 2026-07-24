<?php
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'Contact Messages';
$activeNav = 'contacts';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $id = (int)($_POST['id'] ?? 0);

    if (isset($_POST['delete'])) {
        $stmt = db()->prepare('DELETE FROM contacts WHERE id = :id');
        $stmt->execute(['id' => $id]);
        flash_set('success', 'Message deleted.');
    } elseif (isset($_POST['status'])) {
        $status = $_POST['status'];
        if (in_array($status, ['new', 'read', 'archived'], true)) {
            $stmt = db()->prepare('UPDATE contacts SET status = :status WHERE id = :id');
            $stmt->execute(['status' => $status, 'id' => $id]);
        }
    }
    header('Location: /admin/contacts');
    exit;
}

$flash = flash_get();
$rows = [];
try {
    $rows = db()->query("SELECT * FROM contacts ORDER BY created_at DESC")->fetchAll();
} catch (Throwable $e) {
    $rows = [];
}

require __DIR__ . '/includes/header.php';
?>

<?php if ($flash): ?><div class="alert alert-<?= $flash['type'] === 'error' ? 'error' : 'success' ?>"><?= h($flash['message']) ?></div><?php endif; ?>

<div class="panel">
  <div class="panel-head">
    <h2>Contact Messages <span class="badge-pill"><?= count($rows) ?> total</span></h2>
    <input type="text" class="search-input" placeholder="Search messages…" data-table-search="#contactsTable">
  </div>
  <?php if (!$rows): ?>
    <div class="empty-state"><?= svg_icon('mail') ?><p>No contact messages yet.</p></div>
  <?php else: ?>
  <table class="admin-table" id="contactsTable">
    <thead><tr><th>From</th><th>Subject</th><th>Message</th><th>Status</th><th>Received</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
      <tr>
        <td>
          <b style="color:var(--text-0)"><?= h($r['full_name']) ?></b><br>
          <a href="mailto:<?= h($r['email']) ?>" style="font-size:.8rem"><?= h($r['email']) ?></a>
          <?php if ($r['phone']): ?><br><span style="font-size:.78rem;color:var(--text-3)"><?= h($r['phone']) ?></span><?php endif; ?>
          <?php if ($r['company']): ?><br><span style="font-size:.78rem;color:var(--text-3)"><?= h($r['company']) ?></span><?php endif; ?>
        </td>
        <td><?= h($r['subject'] ?: '—') ?></td>
        <td style="max-width:320px"><?= nl2br(h(excerpt_text($r['message'], 160))) ?></td>
        <td>
          <form method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
            <select name="status" class="pill-select status-<?= h($r['status']) ?>" data-autosubmit>
              <option value="new" <?= $r['status'] === 'new' ? 'selected' : '' ?>>New</option>
              <option value="read" <?= $r['status'] === 'read' ? 'selected' : '' ?>>Read</option>
              <option value="archived" <?= $r['status'] === 'archived' ? 'selected' : '' ?>>Archived</option>
            </select>
          </form>
        </td>
        <td class="nowrap"><?= h(fmt_date($r['created_at'], 'M j, Y g:ia')) ?></td>
        <td>
          <div class="row-actions">
            <form method="post" data-confirm="Delete this message permanently?">
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
