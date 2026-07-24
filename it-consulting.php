<?php
require_once __DIR__ . '/includes/functions.php';

$pageTitle       = 'IT Consulting — QuantAI Corp';
$pageDescription = 'Strategic technology consulting for enterprise-grade digital transformation: cloud architecture, AI & data solutions, cybersecurity, and AI-driven talent solutions.';
$pageSlug        = 'it-consulting';

require __DIR__ . '/includes/header.php';
?>

<section class="page-hero bg-mesh">
  <div class="container">
    <div class="breadcrumb" data-reveal><a href="/">Home</a> / <span>IT Consulting</span></div>
    <div class="eyebrow" data-reveal>IT Consulting</div>
    <h1 data-reveal>Strategic Technology Consulting for <span class="grad-text">Enterprise Transformation</span></h1>
    <p class="lead" style="max-width:640px" data-reveal>We design and implement scalable technology ecosystems that drive operational excellence.</p>
    <div class="hero-actions" data-reveal>
      <a href="/contact" class="btn btn-primary magnetic">Request a Consultation <span class="arrow"><?= svg_icon('arrow') ?></span></a>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="cards-grid" data-stagger>
      <div class="card" data-reveal="scale">
        <div class="card-icon"><?= svg_icon('cloud') ?></div>
        <h3>Enterprise IT Consulting</h3>
        <p>We design and implement scalable technology ecosystems that drive operational excellence.</p>
        <ul>
          <li>Cloud Architecture &amp; Migration (AWS, Azure, GCP)</li>
          <li>Application Modernization</li>
          <li>System Integration</li>
          <li>Infrastructure Optimization</li>
        </ul>
      </div>
      <div class="card" data-reveal="scale">
        <div class="card-icon"><?= svg_icon('ai') ?></div>
        <h3>AI &amp; Data Solutions</h3>
        <p>We enable enterprises to harness the power of AI and advanced analytics.</p>
        <ul>
          <li>Machine learning deployment</li>
          <li>Predictive analytics systems</li>
          <li>Data engineering pipelines</li>
          <li>AI governance frameworks</li>
        </ul>
      </div>
      <div class="card" data-reveal="scale">
        <div class="card-icon"><?= svg_icon('shield') ?></div>
        <h3>Cybersecurity &amp; Compliance</h3>
        <p>Protecting enterprise systems is mission-critical.</p>
        <ul>
          <li>Risk assessments</li>
          <li>Security architecture design</li>
          <li>Compliance readiness (SOC, HIPAA)</li>
          <li>Cloud security implementation</li>
        </ul>
      </div>
      <div class="card" data-reveal="scale">
        <div class="card-icon"><?= svg_icon('people') ?></div>
        <h3>AI-Driven Talent Solutions</h3>
        <p>Our proprietary sourcing methodology combines data analytics and performance modeling to deliver elite technology professionals.</p>
        <ul>
          <li>Contract staffing</li>
          <li>Contract-to-hire</li>
          <li>Direct placement</li>
          <li>Managed services</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<section class="section bg-mesh">
  <div class="container">
    <div class="card" style="padding:64px;text-align:center;background:var(--grad-brand-soft)" data-reveal="scale">
      <h2>Have a Transformation Roadmap in Mind?</h2>
      <p class="lead" style="max-width:560px;margin:16px auto 32px">Let's talk through your priorities and where our consulting team can accelerate delivery.</p>
      <a href="/contact" class="btn btn-primary magnetic">Talk to an Advisor <span class="arrow"><?= svg_icon('arrow') ?></span></a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
