<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/db.php';

$pageTitle       = 'Insights & Blog — QuantAI Corp';
$pageDescription = 'Perspectives on enterprise AI, cloud modernization, cybersecurity, DevOps, and talent intelligence from the QuantAI Corp team.';
$pageSlug        = 'blog.php';

$posts = [];
$dbError = false;
try {
    $posts = db()->query("SELECT title, slug, excerpt, category, cover_accent, published_at FROM blog_posts WHERE is_published = 1 ORDER BY published_at DESC")->fetchAll();
} catch (Throwable $e) {
    $dbError = true;
}

require __DIR__ . '/includes/header.php';
?>

<section class="page-hero bg-mesh">
  <div class="container">
    <div class="breadcrumb" data-reveal><a href="/index.php">Home</a> / <span>Insights</span></div>
    <div class="eyebrow" data-reveal>Insights</div>
    <h1 data-reveal>Perspectives on <span class="grad-text">Enterprise Technology</span></h1>
    <p class="lead" style="max-width:640px" data-reveal>Practical thinking on AI, cloud, security, DevOps, and talent — from the team building it.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <?php if ($dbError): ?>
      <div class="alert alert-error">Insights are temporarily unavailable. Please check back shortly.</div>
    <?php elseif (!$posts): ?>
      <p>No articles published yet — check back soon.</p>
    <?php else: ?>
      <div class="blog-grid" data-stagger>
        <?php foreach ($posts as $post): ?>
          <a href="/blog-post.php?slug=<?= h($post['slug']) ?>" class="blog-card" data-reveal="scale">
            <div class="blog-cover <?= h(cover_accent_class($post['cover_accent'])) ?>">
              <span class="blog-cover-tag"><?= h($post['category']) ?></span>
              <span class="blog-cover-icon"><?= svg_icon(category_icon($post['category'])) ?></span>
            </div>
            <div class="blog-body">
              <h3><?= h($post['title']) ?></h3>
              <p><?= h(excerpt_text($post['excerpt'], 130)) ?></p>
              <div class="blog-meta">
                <span><?= h(fmt_date($post['published_at'])) ?></span>
                <span class="blog-more">Read More <?= svg_icon('arrow') ?></span>
              </div>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
