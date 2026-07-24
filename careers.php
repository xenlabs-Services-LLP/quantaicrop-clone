<?php
require_once __DIR__ . '/includes/functions.php';

$pageTitle       = 'Careers & LCA Posting — QuantAI Corp';
$pageDescription = 'Explore open enterprise technology roles at QuantAI Corp and submit your application to join our talent network.';
$pageSlug        = 'careers.php';

$flash = flash_get();
$openings = job_openings();
$roleOptions = array_column($openings, 'title');
$roleOptions[] = 'Other';

require __DIR__ . '/includes/header.php';
?>

<section class="page-hero bg-mesh">
  <div class="container">
    <div class="breadcrumb" data-reveal><a href="/">Home</a> / <span>Careers</span></div>
    <div class="eyebrow" data-reveal>Careers &amp; LCA Posting</div>
    <h1 data-reveal>Join Our Team of <span class="grad-text">Enterprise Technology</span> Experts</h1>
    <p class="lead" style="max-width:640px" data-reveal>Submit your application and join our talent network — including LCA posting for sponsored roles.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-head center" data-reveal>
      <div class="eyebrow" style="margin-inline:auto">Open Roles</div>
      <h2>Current Job Openings</h2>
    </div>
    <div class="cards-grid cols-3" data-stagger>
      <?php foreach ($openings as $job): ?>
        <div class="card" data-reveal="scale">
          <div class="card-icon"><?= svg_icon('chip') ?></div>
          <h3><?= h($job['title']) ?></h3>
          <p><?= h($job['location']) ?> &middot; <?= h($job['experience']) ?> &middot; <?= h($job['type']) ?></p>
          <a href="#apply" class="btn btn-ghost btn-sm" style="margin-top:18px" data-role="<?= h($job['title']) ?>">Apply Now <span class="arrow"><?= svg_icon('arrow') ?></span></a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section bg-mesh" id="apply">
  <div class="container">
    <div class="two-col" style="align-items:flex-start">
      <div data-reveal="left">
        <div class="eyebrow">Apply Now</div>
        <h2>Submit Your Application</h2>
        <p class="lead" style="margin-top:16px">Fill out the form and our talent intelligence team will match you against active enterprise roles — including sponsored (LCA) positions.</p>
        <ul style="margin-top:28px;display:flex;flex-direction:column;gap:16px">
          <li class="flex gap-3 items-center"><span class="card-icon" style="margin:0"><?= svg_icon('check') ?></span> All major visa types supported (H-1B, L-1, O-1, TN, OPT/CPT &amp; more)</li>
          <li class="flex gap-3 items-center"><span class="card-icon" style="margin:0"><?= svg_icon('check') ?></span> Contract, contract-to-hire &amp; direct placement</li>
          <li class="flex gap-3 items-center"><span class="card-icon" style="margin:0"><?= svg_icon('check') ?></span> Resume reviewed by a human, every time</li>
        </ul>
      </div>

      <div class="card" style="padding:40px" data-reveal="right">
        <?php if ($flash): ?>
          <div class="alert alert-<?= $flash['type'] === 'error' ? 'error' : 'success' ?>"><?= h($flash['message']) ?></div>
        <?php endif; ?>

        <form action="/application-submit" method="post" enctype="multipart/form-data" data-validate novalidate>
          <?= csrf_field() ?>
          <div class="field-row">
            <div class="field">
              <input type="text" name="first_name" id="first_name" placeholder=" " required maxlength="80">
              <label for="first_name">First Name *</label>
            </div>
            <div class="field">
              <input type="text" name="last_name" id="last_name" placeholder=" " required maxlength="80">
              <label for="last_name">Last Name *</label>
            </div>
          </div>
          <div class="field-row">
            <div class="field">
              <input type="email" name="email" id="email" placeholder=" " required maxlength="160">
              <label for="email">Email ID *</label>
            </div>
            <div class="field">
              <input type="tel" name="mobile" id="mobile" placeholder=" " required maxlength="40">
              <label for="mobile">Mobile Number *</label>
            </div>
          </div>
          <div class="field-row">
            <div class="field">
              <input type="text" name="location" id="location" placeholder=" " required maxlength="160">
              <label for="location">Location *</label>
            </div>
            <div class="field">
              <input type="text" name="education" id="education" placeholder=" " required maxlength="160">
              <label for="education">Highest Education Qualification *</label>
            </div>
          </div>
          <div class="field-row">
            <div class="field">
              <select name="visa_type" id="visa_type" required>
                <option value="" disabled selected>Select Visa Type</option>
                <?php foreach (VISA_TYPES as $visa): ?>
                  <option value="<?= h($visa) ?>"><?= h($visa) ?></option>
                <?php endforeach; ?>
              </select>
              <label for="visa_type">Visa Type *</label>
            </div>
            <div class="field">
              <select name="role" id="role" required>
                <option value="" disabled selected>Select Role</option>
                <?php foreach ($roleOptions as $role): ?>
                  <option value="<?= h($role) ?>"><?= h($role) ?></option>
                <?php endforeach; ?>
              </select>
              <label for="role">Role *</label>
            </div>
          </div>
          <div class="field-row">
            <div class="field">
              <input type="text" name="technologies" id="technologies" placeholder=" " required maxlength="255">
              <label for="technologies">Technologies *</label>
            </div>
            <div class="field">
              <input type="text" name="experience_years" id="experience_years" placeholder=" " required maxlength="20">
              <label for="experience_years">Years of Experience *</label>
            </div>
          </div>

          <div class="field">
            <label style="position:static;font-size:.85rem;color:var(--text-2);display:block;margin-bottom:8px">Upload Resume (PDF/DOC) *</label>
            <div class="file-drop">
              <input type="file" name="resume" id="resume" accept=".pdf,.doc,.docx" required style="display:none">
              <span class="file-drop-label"><b>Click to upload</b> or drag &amp; drop — PDF or Word, up to 5MB</span>
            </div>
          </div>

          <button type="submit" class="btn btn-primary btn-block magnetic" style="margin-top:8px">Submit Application <span class="arrow"><?= svg_icon('arrow') ?></span></button>
        </form>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
