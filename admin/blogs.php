<?php
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'Blog Posts';
$activeNav = 'blogs';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    csrf_verify();
    $id = (int)($_POST['id'] ?? 0);
    $stmt = db()->prepare('DELETE FROM blog_posts WHERE id = :id');
    $stmt->execute(['id' => $id]);
    flash_set('success', 'Blog post deleted.');
    header('Location: /admin/blogs');
    exit;
}

$flash = flash_get();
$rows = [];
try {
    $rows = db()->query("SELECT * FROM blog_posts ORDER BY published_at DESC")->fetchAll();
} catch (Throwable $e) {
    $rows = [];
}

require __DIR__ . '/includes/header.php';
?>

<?php if ($flash): ?><div class="alert alert-<?= $flash['type'] === 'error' ? 'error' : 'success' ?>"><?= h($flash['message']) ?></div><?php endif; ?>

<div class="panel">
  <div class="panel-head">
    <h2>Blog Posts <span class="badge-pill"><?= count($rows) ?> total</span></h2>
    <div class="flex gap-3">
      <input type="text" class="search-input" placeholder="Search posts…" data-table-search="#blogsTable">
      <a href="/admin/blog-form" class="btn btn-primary btn-sm"><?= svg_icon('plus') ?> New Post</a>
    </div>
  </div>
  <?php if (!$rows): ?>
    <div class="empty-state"><?= svg_icon('book') ?><p>No blog posts yet — create your first one.</p></div>
  <?php else: ?>
  <table class="admin-table" id="blogsTable">
    <thead><tr><th>Title</th><th>Category</th><th>Status</th><th>Published</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
      <tr>
        <td><b style="color:var(--text-0)"><?= h($r['title']) ?></b><br><span style="font-size:.78rem;color:var(--text-3)">/blog/<?= h($r['slug']) ?></span></td>
        <td><?= h($r['category']) ?></td>
        <td><span class="status-pill <?= $r['is_published'] ? 'status-hired' : 'status-archived' ?>"><?= $r['is_published'] ? 'Published' : 'Draft' ?></span></td>
        <td class="nowrap"><?= h(fmt_date($r['published_at'])) ?></td>
        <td>
          <div class="row-actions">
            <a href="/blog/<?= h($r['slug']) ?>" target="_blank" class="icon-btn" aria-label="Preview"><?= svg_icon('eye') ?></a>
            <a href="/admin/blog-form?id=<?= (int)$r['id'] ?>" class="icon-btn" aria-label="Edit"><?= svg_icon('edit') ?></a>
            <form method="post" data-confirm="Delete this blog post permanently?">
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
