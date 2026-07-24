<?php
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'Applications';
$activeNav = 'applications';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $id = (int)($_POST['id'] ?? 0);

    if (isset($_POST['delete'])) {
        $stmt = db()->prepare('SELECT resume_stored_name FROM applications WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        if ($row && $row['resume_stored_name']) {
            $path = __DIR__ . '/../uploads/resumes/' . $row['resume_stored_name'];
            if (is_file($path)) {
                @unlink($path);
            }
        }
        $del = db()->prepare('DELETE FROM applications WHERE id = :id');
        $del->execute(['id' => $id]);
        flash_set('success', 'Application deleted.');
    } elseif (isset($_POST['status'])) {
        $status = $_POST['status'];
        if (in_array($status, ['new', 'reviewed', 'shortlisted', 'rejected', 'hired'], true)) {
            $stmt = db()->prepare('UPDATE applications SET status = :status WHERE id = :id');
            $stmt->execute(['status' => $status, 'id' => $id]);
        }
    }
    header('Location: /admin/applications.php');
    exit;
}

$flash = flash_get();
$rows = [];
try {
    $rows = db()->query("SELECT * FROM applications ORDER BY created_at DESC")->fetchAll();
} catch (Throwable $e) {
    $rows = [];
}

require __DIR__ . '/includes/header.php';
?>

<?php if ($flash): ?><div class="alert alert-<?= $flash['type'] === 'error' ? 'error' : 'success' ?>"><?= h($flash['message']) ?></div><?php endif; ?>

<div class="panel">
  <div class="panel-head">
    <h2>Job Applications <span class="badge-pill"><?= count($rows) ?> total</span></h2>
    <input type="text" class="search-input" placeholder="Search applicants…" data-table-search="#appsTable">
  </div>
  <?php if (!$rows): ?>
    <div class="empty-state"><?= svg_icon('people') ?><p>No applications yet.</p></div>
  <?php else: ?>
  <table class="admin-table" id="appsTable">
    <thead><tr><th>Applicant</th><th>Role</th><th>Visa</th><th>Experience</th><th>Resume</th><th>Status</th><th>Received</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
      <tr>
        <td>
          <b style="color:var(--text-0)"><?= h($r['first_name'] . ' ' . $r['last_name']) ?></b><br>
          <a href="mailto:<?= h($r['email']) ?>" style="font-size:.8rem"><?= h($r['email']) ?></a><br>
          <span style="font-size:.78rem;color:var(--text-3)"><?= h($r['mobile']) ?> &middot; <?= h($r['location']) ?></span>
        </td>
        <td><?= h($r['role_applied']) ?><br><span style="font-size:.78rem;color:var(--text-3)"><?= h($r['technologies']) ?></span></td>
        <td><?= h($r['visa_type']) ?></td>
        <td><?= h($r['experience_years']) ?></td>
        <td>
          <a href="/admin/download-resume.php?id=<?= (int)$r['id'] ?>" class="icon-btn" aria-label="Download resume"><?= svg_icon('download') ?></a>
        </td>
        <td>
          <form method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
            <select name="status" class="pill-select status-<?= h($r['status']) ?>" data-autosubmit>
              <option value="new" <?= $r['status'] === 'new' ? 'selected' : '' ?>>New</option>
              <option value="reviewed" <?= $r['status'] === 'reviewed' ? 'selected' : '' ?>>Reviewed</option>
              <option value="shortlisted" <?= $r['status'] === 'shortlisted' ? 'selected' : '' ?>>Shortlisted</option>
              <option value="rejected" <?= $r['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
              <option value="hired" <?= $r['status'] === 'hired' ? 'selected' : '' ?>>Hired</option>
            </select>
          </form>
        </td>
        <td class="nowrap"><?= h(fmt_date($r['created_at'], 'M j, Y')) ?></td>
        <td>
          <div class="row-actions">
            <form method="post" data-confirm="Delete this application and its resume file permanently?">
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
