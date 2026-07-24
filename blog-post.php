<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/db.php';

$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';
$post = null;
try {
    $stmt = db()->prepare("SELECT * FROM blog_posts WHERE slug = :slug AND is_published = 1 LIMIT 1");
    $stmt->execute(['slug' => $slug]);
    $post = $stmt->fetch();
} catch (Throwable $e) {
    $post = null;
}

if (!$post) {
    http_response_code(404);
    $pageTitle = 'Article Not Found — QuantAI Corp';
    $pageSlug  = 'blog-post.php';
    require __DIR__ . '/includes/header.php';
    echo '<section class="section"><div class="container text-center"><h1>404</h1><p class="lead">That article could not be found.</p><a href="/blog" class="btn btn-primary" style="margin-top:24px">Back to Insights</a></div></section>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

$related = [];
try {
    $stmt = db()->prepare("SELECT title, slug, category FROM blog_posts WHERE is_published = 1 AND slug != :slug ORDER BY published_at DESC LIMIT 3");
    $stmt->execute(['slug' => $slug]);
    $related = $stmt->fetchAll();
} catch (Throwable $e) {
    $related = [];
}

$pageTitle       = $post['title'] . ' — QuantAI Corp Insights';
$pageDescription = excerpt_text($post['excerpt'], 160);
$pageSlug        = 'blog-post.php?slug=' . $post['slug'];
$pageSchema = json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'BlogPosting',
    'headline' => $post['title'],
    'author'   => ['@type' => 'Organization', 'name' => $post['author']],
    'datePublished' => date('c', strtotime($post['published_at'])),
    'dateModified'  => date('c', strtotime($post['updated_at'])),
    'publisher' => ['@type' => 'Organization', 'name' => COMPANY_LEGAL],
]);

require __DIR__ . '/includes/header.php';
?>

<section class="page-hero bg-mesh">
  <div class="container">
    <div class="breadcrumb" data-reveal><a href="/">Home</a> / <a href="/blog">Insights</a> / <span><?= h($post['category']) ?></span></div>
    <div class="eyebrow" data-reveal><?= h($post['category']) ?></div>
    <h1 style="max-width:840px" data-reveal><?= h($post['title']) ?></h1>
    <p class="lead" data-reveal><?= h($post['author']) ?> &middot; <?= h(fmt_date($post['published_at'])) ?></p>
  </div>
</section>

<section class="section">
  <div class="container">
    <article class="article-body" data-reveal>
      <?= $post['content'] /* Trusted content authored via the CMS, not raw user input */ ?>
    </article>
  </div>
</section>

<?php if ($related): ?>
<section class="section bg-mesh">
  <div class="container">
    <div class="section-head center" data-reveal>
      <div class="eyebrow" style="margin-inline:auto">Keep Reading</div>
      <h2>More Insights</h2>
    </div>
    <div class="cards-grid cols-3" data-stagger>
      <?php foreach ($related as $r): ?>
        <a href="/blog-post.php?slug=<?= h($r['slug']) ?>" class="card" data-reveal="scale">
          <span class="blog-tag"><?= h($r['category']) ?></span>
          <h3 style="margin-top:8px"><?= h($r['title']) ?></h3>
          <span class="blog-more" style="margin-top:12px;display:inline-flex">Read More <?= svg_icon('arrow') ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
