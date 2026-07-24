<?php
require_once __DIR__ . '/includes/functions.php';

$pageTitle       = 'Software Engineering — QuantAI Corp';
$pageDescription = 'Building enterprise-grade software with cutting-edge engineering practices: cloud architecture, DevOps & CI/CD, application modernization, API & microservices.';
$pageSlug        = 'software-engineering';

require __DIR__ . '/includes/header.php';
?>

<section class="page-hero bg-mesh">
  <div class="container">
    <div class="breadcrumb" data-reveal><a href="/">Home</a> / <span>Software Engineering</span></div>
    <div class="eyebrow" data-reveal>Software Engineering</div>
    <h1 data-reveal>Building Enterprise Software With <span class="grad-text">Cutting-Edge</span> Engineering</h1>
    <p class="lead" style="max-width:640px" data-reveal>Modern architecture, automated delivery, and reliability engineering — built to scale with your business.</p>
    <div class="hero-actions" data-reveal>
      <a href="/contact" class="btn btn-primary magnetic">Discuss Your Project <span class="arrow"><?= svg_icon('arrow') ?></span></a>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="cards-grid cols-3" data-stagger>
      <div class="card" data-reveal="scale">
        <div class="card-icon"><?= svg_icon('cloud') ?></div>
        <h3>Cloud Architecture</h3>
        <p>Scalable, secure cloud solutions across AWS, Azure, and GCP for enterprise workloads.</p>
      </div>
      <div class="card" data-reveal="scale">
        <div class="card-icon"><?= svg_icon('devops') ?></div>
        <h3>DevOps &amp; CI/CD</h3>
        <p>Automated deployment pipelines, infrastructure as code, and continuous delivery workflows.</p>
      </div>
      <div class="card" data-reveal="scale">
        <div class="card-icon"><?= svg_icon('layers') ?></div>
        <h3>Application Modernization</h3>
        <p>Legacy system transformation to modern, cloud-native architectures.</p>
      </div>
      <div class="card" data-reveal="scale">
        <div class="card-icon"><?= svg_icon('api') ?></div>
        <h3>API &amp; Microservices</h3>
        <p>Robust API design and microservices architecture for scalable applications.</p>
      </div>
      <div class="card" data-reveal="scale">
        <div class="card-icon"><?= svg_icon('gauge') ?></div>
        <h3>Infrastructure Optimization</h3>
        <p>Performance tuning, cost optimization, and infrastructure reliability engineering.</p>
      </div>
      <div class="card" data-reveal="scale">
        <div class="card-icon"><?= svg_icon('chip') ?></div>
        <h3>Platform Engineering</h3>
        <p>Internal developer platforms with self-service, golden-path workflows.</p>
      </div>
    </div>
  </div>
</section>

<section class="section bg-mesh">
  <div class="container">
    <div class="card" style="padding:64px;text-align:center;background:var(--grad-brand-soft)" data-reveal="scale">
      <h2>Ready to Modernize Your Stack?</h2>
      <p class="lead" style="max-width:560px;margin:16px auto 32px">Tell us what you're building — we'll bring the engineering depth to ship it right.</p>
      <a href="/contact" class="btn btn-primary magnetic">Get Started <span class="arrow"><?= svg_icon('arrow') ?></span></a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
