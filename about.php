<?php
require_once __DIR__ . '/includes/functions.php';

$pageTitle       = 'About Us — QuantAI Corp';
$pageDescription = 'QuantAI Corp is redefining how enterprises build and scale technology, combining strategic advisory, engineering, and AI-powered talent intelligence.';
$pageSlug        = 'about.php';

require __DIR__ . '/includes/header.php';
?>

<section class="page-hero bg-mesh">
  <div class="container">
    <div class="breadcrumb" data-reveal><a href="/index.php">Home</a> / <span>About</span></div>
    <div class="eyebrow" data-reveal>About QuantAI Corp</div>
    <h1 data-reveal>Redefining How Enterprises <span class="grad-text">Build &amp; Scale</span> Technology</h1>
    <p class="lead" style="max-width:640px" data-reveal>A strategic technology partner for organizations navigating digital disruption, cloud transformation, and AI adoption.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="two-col">
      <div data-reveal="left">
        <div class="eyebrow">Who We Are</div>
        <h2>Built for Alignment Between Strategy and Execution</h2>
        <p class="lead" style="margin-top:18px">QuantAI Corp was founded with a bold vision to redefine how enterprises build and scale technology. We recognized that companies don't just need talent — they need alignment between business strategy and technical execution.</p>
        <p style="margin-top:16px">Today, we operate as a strategic technology partner to organizations navigating digital disruption, cloud transformation, and AI adoption.</p>
      </div>
      <div class="hero-visual" style="height:380px" data-reveal="right">
        <div class="hero-core" style="width:180px;height:180px"><?= svg_icon('layers') ?></div>
        <div class="orb orb-blue float-slow" style="top:-6%;left:-8%"></div>
        <div class="orb orb-cyan float-slower" style="bottom:-10%;right:-6%"></div>
      </div>
    </div>
  </div>
</section>

<section class="section bg-mesh">
  <div class="container">
    <div class="cards-grid cols-3" data-stagger>
      <div class="card" data-reveal="scale">
        <div class="card-icon"><?= svg_icon('target') ?></div>
        <h3>Our Mission</h3>
        <p>To empower enterprises with intelligent technology solutions and high-impact talent that accelerate innovation and drive measurable business outcomes.</p>
      </div>
      <div class="card" data-reveal="scale">
        <div class="card-icon"><?= svg_icon('gauge') ?></div>
        <h3>Our Vision</h3>
        <p>To become a globally recognized enterprise technology powerhouse shaping the future of AI, cloud computing, and digital infrastructure.</p>
      </div>
      <div class="card" data-reveal="scale">
        <div class="card-icon"><?= svg_icon('shield') ?></div>
        <h3>Our Promise</h3>
        <p>Long-term partnerships, measurable impact, and sustainable growth — not transactional staffing.</p>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-head center" data-reveal>
      <div class="eyebrow" style="margin-inline:auto">What Drives Us</div>
      <h2>Our Core Values</h2>
    </div>
    <div class="cards-grid" data-stagger>
      <div class="card" data-reveal="scale">
        <div class="card-icon"><?= svg_icon('shield') ?></div>
        <h3>Integrity &amp; Transparency</h3>
        <p>We build trust through honest communication and ethical practices.</p>
      </div>
      <div class="card" data-reveal="scale">
        <div class="card-icon"><?= svg_icon('chip') ?></div>
        <h3>Engineering Excellence</h3>
        <p>We deliver solutions with the highest technical standards.</p>
      </div>
      <div class="card" data-reveal="scale">
        <div class="card-icon"><?= svg_icon('people') ?></div>
        <h3>Client-Centric Partnership</h3>
        <p>Your success is our mission — we grow with our clients.</p>
      </div>
      <div class="card" data-reveal="scale">
        <div class="card-icon"><?= svg_icon('ai') ?></div>
        <h3>Continuous Innovation</h3>
        <p>We stay ahead of the curve with cutting-edge technology.</p>
      </div>
    </div>
  </div>
</section>

<section class="section bg-mesh">
  <div class="container">
    <div class="card" style="padding:64px;text-align:center;background:var(--grad-brand-soft)" data-reveal="scale">
      <h2>Let's Build Something Enterprise-Grade Together</h2>
      <p class="lead" style="max-width:560px;margin:16px auto 32px">Tell us about your technology roadmap and we'll show you where we can help.</p>
      <a href="/contact.php" class="btn btn-primary magnetic">Start the Conversation <span class="arrow"><?= svg_icon('arrow') ?></span></a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
