<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/db.php';

$pageTitle       = "Contact Us — QuantAI Corp";
$pageDescription = "Get in touch with QuantAI Corp — enterprise IT consulting, AI & data solutions, and talent intelligence. Charlotte, NC.";
$pageSlug        = 'contact.php';

$flash = flash_get();
$openings = job_openings();

$latestPosts = [];
try {
    $stmt = db()->query("SELECT title, slug, category FROM blog_posts WHERE is_published = 1 ORDER BY published_at DESC LIMIT 6");
    $latestPosts = $stmt->fetchAll();
} catch (Throwable $e) {
    $latestPosts = [];
}

require __DIR__ . '/includes/header.php';
?>

<section class="page-hero bg-mesh">
  <div class="container">
    <div class="breadcrumb" data-reveal><a href="/index.php">Home</a> / <span>Contact</span></div>
    <div class="eyebrow" data-reveal>Get in Touch</div>
    <h1 data-reveal>Let's Build the <span class="grad-text">Future</span> Together</h1>
    <p class="lead" style="max-width:640px" data-reveal>Whether you're modernizing infrastructure, deploying AI, or scaling your workforce — we're ready to partner with you.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="two-col" style="align-items:flex-start">
      <div data-reveal="left">
        <div class="cards-grid" style="grid-template-columns:1fr" data-stagger>
          <div class="card" data-reveal="scale">
            <div class="card-icon"><?= svg_icon('pin') ?></div>
            <h3>Address</h3>
            <p><?= h(COMPANY_LEGAL) ?><br><?= h(COMPANY_ADDRESS_1) ?><br><?= h(COMPANY_ADDRESS_2) ?><br><?= h(COMPANY_CITY) ?></p>
          </div>
          <div class="card" data-reveal="scale">
            <div class="card-icon"><?= svg_icon('phone') ?></div>
            <h3>Phone</h3>
            <p><a href="tel:<?= h(preg_replace('/[^0-9+]/', '', COMPANY_PHONE)) ?>"><?= h(COMPANY_PHONE) ?></a></p>
          </div>
          <div class="card" data-reveal="scale">
            <div class="card-icon"><?= svg_icon('mail') ?></div>
            <h3>Email</h3>
            <p><a href="mailto:<?= h(COMPANY_EMAIL) ?>"><?= h(COMPANY_EMAIL) ?></a></p>
          </div>
          <div class="card" data-reveal="scale">
            <div class="card-icon"><?= svg_icon('calendar') ?></div>
            <h3>Business Hours</h3>
            <p>Mon–Fri: 9 AM – 6 PM EST<br>Sat–Sun: Holiday</p>
          </div>
        </div>
      </div>

      <div class="card" style="padding:40px" id="contact-form" data-reveal="right">
        <h3 style="margin-bottom:20px">Send Us a Message</h3>
        <?php if ($flash): ?>
          <div class="alert alert-<?= $flash['type'] === 'error' ? 'error' : 'success' ?>"><?= h($flash['message']) ?></div>
        <?php endif; ?>
        <form action="/contact-submit.php" method="post" data-validate novalidate>
          <?= csrf_field() ?>
          <div class="field-row">
            <div class="field">
              <input type="text" name="full_name" id="full_name" placeholder=" " required maxlength="120">
              <label for="full_name">Full Name *</label>
            </div>
            <div class="field">
              <input type="email" name="email" id="c_email" placeholder=" " required maxlength="160">
              <label for="c_email">Email *</label>
            </div>
          </div>
          <div class="field-row">
            <div class="field">
              <input type="tel" name="phone" id="c_phone" placeholder=" " maxlength="40">
              <label for="c_phone">Phone</label>
            </div>
            <div class="field">
              <input type="text" name="company" id="c_company" placeholder=" " maxlength="160">
              <label for="c_company">Company</label>
            </div>
          </div>
          <div class="field">
            <input type="text" name="subject" id="c_subject" placeholder=" " maxlength="200">
            <label for="c_subject">Subject</label>
          </div>
          <div class="field">
            <textarea name="message" id="c_message" placeholder=" " required maxlength="4000"></textarea>
            <label for="c_message">Message *</label>
          </div>
          <button type="submit" class="btn btn-primary btn-block magnetic">Send Message <span class="arrow"><?= svg_icon('arrow') ?></span></button>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- ================= PROJECT ENQUIRY ================= -->
<section class="section bg-mesh">
  <div class="container">
    <div class="two-col">
      <div data-reveal="left">
        <div class="eyebrow">Request a Consultation</div>
        <h2>Have a Specific Project or Service in Mind?</h2>
        <p class="lead" style="margin-top:16px">Tell us about the service you're interested in and your rough budget range — our advisory team will follow up within one business day.</p>
      </div>
      <div class="card" style="padding:40px" id="enquiry-form" data-reveal="right">
        <?php if ($flash): ?>
          <div class="alert alert-<?= $flash['type'] === 'error' ? 'error' : 'success' ?>"><?= h($flash['message']) ?></div>
        <?php endif; ?>
        <form action="/enquiry-submit.php" method="post" data-validate novalidate>
          <?= csrf_field() ?>
          <div class="field-row">
            <div class="field">
              <input type="text" name="full_name" id="e_name" placeholder=" " required maxlength="120">
              <label for="e_name">Full Name *</label>
            </div>
            <div class="field">
              <input type="email" name="email" id="e_email" placeholder=" " required maxlength="160">
              <label for="e_email">Work Email *</label>
            </div>
          </div>
          <div class="field-row">
            <div class="field">
              <select name="service_interest" id="e_service" required>
                <option value="" disabled selected>Select a Service</option>
                <option>AI &amp; Data Solutions</option>
                <option>Cloud Architecture</option>
                <option>Cybersecurity &amp; Compliance</option>
                <option>DevOps &amp; CI/CD</option>
                <option>Software Engineering</option>
                <option>AI-Driven Talent Solutions</option>
              </select>
              <label for="e_service">Service Interest *</label>
            </div>
            <div class="field">
              <select name="budget_range" id="e_budget">
                <option value="" disabled selected>Select Budget Range</option>
                <option>Under $25K</option>
                <option>$25K – $100K</option>
                <option>$100K – $500K</option>
                <option>$500K+</option>
              </select>
              <label for="e_budget">Budget Range</label>
            </div>
          </div>
          <div class="field">
            <textarea name="message" id="e_message" placeholder=" " required maxlength="4000"></textarea>
            <label for="e_message">Tell us about your project *</label>
          </div>
          <button type="submit" class="btn btn-primary btn-block magnetic">Request Consultation <span class="arrow"><?= svg_icon('arrow') ?></span></button>
        </form>
      </div>
    </div>
  </div>
</section>

<?php if ($latestPosts): ?>
<section class="section">
  <div class="container">
    <div class="section-head center" data-reveal>
      <div class="eyebrow" style="margin-inline:auto">Latest Insights</div>
      <h2>From Our Blog</h2>
    </div>
    <div class="cards-grid cols-3" data-stagger>
      <?php foreach ($latestPosts as $post): ?>
        <a href="/blog-post.php?slug=<?= h($post['slug']) ?>" class="card" data-reveal="scale">
          <span class="blog-tag"><?= h($post['category']) ?></span>
          <h3 style="margin-top:8px"><?= h($post['title']) ?></h3>
          <span class="blog-more" style="margin-top:12px;display:inline-flex">Read More <?= svg_icon('arrow') ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="section bg-mesh">
  <div class="container">
    <div class="section-head center" data-reveal>
      <div class="eyebrow" style="margin-inline:auto">Careers</div>
      <h2>Job Openings</h2>
      <p class="lead" style="margin-top:12px">Join our team of enterprise technology experts.</p>
    </div>
    <div class="cards-grid cols-3" data-stagger>
      <?php foreach (array_slice($openings, 0, 6) as $job): ?>
        <div class="card" data-reveal="scale">
          <h3><?= h($job['title']) ?></h3>
          <p><?= h($job['location']) ?> &middot; <?= h($job['experience']) ?></p>
          <a href="/careers.php#apply" class="btn btn-ghost btn-sm" style="margin-top:16px">Apply Now <span class="arrow"><?= svg_icon('arrow') ?></span></a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
