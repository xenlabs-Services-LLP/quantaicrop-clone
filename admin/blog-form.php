<?php
require_once __DIR__ . '/includes/auth.php';

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$post = [
    'id' => 0, 'title' => '', 'slug' => '', 'excerpt' => '', 'content' => '',
    'category' => 'Insights', 'author' => 'QuantAI Corp', 'cover_accent' => 'blue', 'is_published' => 1,
];
$errors = [];

if ($id) {
    $stmt = db()->prepare('SELECT * FROM blog_posts WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $existing = $stmt->fetch();
    if ($existing) {
        $post = $existing;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['delete'])) {
    csrf_verify();

    $post['title']        = post_str('title');
    $post['excerpt']      = post_str('excerpt');
    $post['content']      = trim((string)($_POST['content'] ?? ''));
    $post['category']     = post_str('category') ?: 'Insights';
    $post['author']       = post_str('author') ?: 'QuantAI Corp';
    $post['cover_accent'] = post_str('cover_accent') ?: 'blue';
    $post['is_published'] = isset($_POST['is_published']) ? 1 : 0;

    $slugInput = post_str('slug');
    $post['slug'] = slugify($slugInput !== '' ? $slugInput : $post['title']);

    if ($post['title'] === '') {
        $errors[] = 'Title is required.';
    }
    if ($post['slug'] === '') {
        $errors[] = 'Could not generate a valid slug — please adjust the title.';
    }
    if ($post['excerpt'] === '') {
        $errors[] = 'Excerpt is required (used in listings and meta description).';
    }
    if ($post['content'] === '') {
        $errors[] = 'Content is required.';
    }

    if (!$errors) {
        try {
            if ($id) {
                $stmt = db()->prepare(
                    'UPDATE blog_posts SET title=:title, slug=:slug, excerpt=:excerpt, content=:content,
                     category=:category, author=:author, cover_accent=:cover_accent, is_published=:is_published
                     WHERE id=:id'
                );
                $stmt->execute([
                    'title' => $post['title'], 'slug' => $post['slug'], 'excerpt' => $post['excerpt'],
                    'content' => $post['content'], 'category' => $post['category'], 'author' => $post['author'],
                    'cover_accent' => $post['cover_accent'], 'is_published' => $post['is_published'], 'id' => $id,
                ]);
                flash_set('success', 'Blog post updated.');
            } else {
                $stmt = db()->prepare(
                    'INSERT INTO blog_posts (title, slug, excerpt, content, category, author, cover_accent, is_published)
                     VALUES (:title, :slug, :excerpt, :content, :category, :author, :cover_accent, :is_published)'
                );
                $stmt->execute([
                    'title' => $post['title'], 'slug' => $post['slug'], 'excerpt' => $post['excerpt'],
                    'content' => $post['content'], 'category' => $post['category'], 'author' => $post['author'],
                    'cover_accent' => $post['cover_accent'], 'is_published' => $post['is_published'],
                ]);
                flash_set('success', 'Blog post created.');
            }
            header('Location: /admin/blogs.php');
            exit;
        } catch (PDOException $e) {
            $errors[] = str_contains($e->getMessage(), 'Duplicate')
                ? 'That slug is already in use — please choose another.'
                : 'Could not save the post. Please try again.';
        }
    }
}

$pageTitle = $id ? 'Edit Blog Post' : 'New Blog Post';
$activeNav = 'blogs';
require __DIR__ . '/includes/header.php';
?>

<div class="panel form-card">
  <?php foreach ($errors as $err): ?>
    <div class="alert alert-error"><?= h($err) ?></div>
  <?php endforeach; ?>

  <form method="post" novalidate>
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= (int)$id ?>">

    <div class="field">
      <input type="text" name="title" id="bp_title" placeholder=" " required value="<?= h($post['title']) ?>">
      <label for="bp_title">Title *</label>
    </div>
    <div class="field">
      <input type="text" name="slug" id="bp_slug" placeholder=" " value="<?= h($post['slug']) ?>">
      <label for="bp_slug">URL Slug (leave blank to auto-generate)</label>
    </div>
    <div class="field-row">
      <div class="field">
        <input type="text" name="category" id="bp_category" placeholder=" " value="<?= h($post['category']) ?>">
        <label for="bp_category">Category</label>
      </div>
      <div class="field">
        <input type="text" name="author" id="bp_author" placeholder=" " value="<?= h($post['author']) ?>">
        <label for="bp_author">Author</label>
      </div>
    </div>
    <div class="field">
      <select name="cover_accent" id="bp_cover">
        <?php foreach (['blue' => 'Blue', 'cyan' => 'Cyan', 'purple' => 'Violet'] as $val => $lbl): ?>
          <option value="<?= h($val) ?>" <?= $post['cover_accent'] === $val ? 'selected' : '' ?>><?= h($lbl) ?></option>
        <?php endforeach; ?>
      </select>
      <label for="bp_cover">Cover Accent</label>
    </div>
    <div class="field">
      <textarea name="excerpt" id="bp_excerpt" placeholder=" " required maxlength="400" style="min-height:80px"><?= h($post['excerpt']) ?></textarea>
      <label for="bp_excerpt">Excerpt (used in listings &amp; meta description) *</label>
    </div>
    <div class="field">
      <label style="position:static;display:block;font-size:.85rem;color:var(--text-2);margin-bottom:8px">Content (HTML allowed — e.g. &lt;p&gt;, &lt;h2&gt;, &lt;ul&gt;) *</label>
      <textarea name="content" id="bp_content" required style="min-height:320px;font-family:monospace;font-size:.85rem"><?= h($post['content']) ?></textarea>
    </div>
    <label class="flex items-center gap-2" style="margin-bottom:24px;color:var(--text-1);font-size:.9rem">
      <input type="checkbox" name="is_published" value="1" <?= $post['is_published'] ? 'checked' : '' ?> style="width:16px;height:16px">
      Published (visible on the public site)
    </label>

    <div class="flex gap-3">
      <button type="submit" class="btn btn-primary">Save Post</button>
      <a href="/admin/blogs.php" class="btn btn-ghost">Cancel</a>
    </div>
  </form>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
