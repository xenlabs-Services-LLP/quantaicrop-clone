<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/db.php';

$pageTitle       = 'QuantAI Corp — AI-Driven IT Consulting, Software Engineering & Talent Intelligence';
$pageDescription = 'QuantAI Corp is a next-generation enterprise technology partner delivering AI &amp; data solutions, cloud architecture, cybersecurity, DevOps and AI-powered talent intelligence.';
$pageSlug        = 'index.php';

$partnerLogos = partner_logos();
$logoRowA = array_slice($partnerLogos, 0, 10);
$logoRowB = array_slice($partnerLogos, 10, 10);

$latestPosts = [];
try {
    $stmt = db()->query("SELECT title, slug, excerpt, category, cover_accent, published_at FROM blog_posts WHERE is_published = 1 ORDER BY published_at DESC LIMIT 3");
    $latestPosts = $stmt->fetchAll();
} catch (Throwable $e) {
    $latestPosts = [];
}

require __DIR__ . '/includes/header.php';
?>

<!-- ================= HERO ================= -->
<section class="hero bg-mesh">
  <div class="container">
    <div class="hero-grid">
      <div>
        <div class="hero-badge" data-reveal>
          <span class="dot"></span> Trusted enterprise technology partner
        </div>
        <h1 data-reveal>Engineering the <span class="grad-text">Future</span> of Enterprise Technology</h1>
        <p class="lead" data-reveal>AI-driven IT consulting, software engineering, and talent intelligence — built for enterprises navigating cloud transformation and AI adoption.</p>
        <div class="hero-actions" data-reveal>
          <a href="/contact.php" class="btn btn-primary magnetic">Start a Conversation <span class="arrow"><?= svg_icon('arrow') ?></span></a>
          <a href="/it-consulting.php" class="btn btn-ghost magnetic">Explore Our Services</a>
        </div>
        <div class="hero-stats" data-reveal>
          <div class="hero-stat"><b class="count" data-count="120" data-suffix="+">0</b><span>Enterprise Engagements</span></div>
          <div class="hero-stat"><b class="count" data-count="98" data-suffix="%">0</b><span>Client Retention</span></div>
          <div class="hero-stat"><b class="count" data-count="24" data-suffix="/7">0</b><span>Delivery Coverage</span></div>
        </div>
      </div>
      <div class="hero-visual" data-parallax data-reveal="scale">
        <div class="hero-core" data-depth="8"><?= svg_icon('ai') ?></div>
        <div class="orbit-card float-slow" style="top:4%;left:0" data-depth="18">
          <span class="ic"><?= svg_icon('cloud') ?></span>
          <div><b>Cloud Architecture</b><span>AWS · Azure · GCP</span></div>
        </div>
        <div class="orbit-card float-slower" style="top:36%;right:-4%" data-depth="26">
          <span class="ic"><?= svg_icon('shield') ?></span>
          <div><b>Security &amp; Compliance</b><span>SOC · HIPAA ready</span></div>
        </div>
        <div class="orbit-card float-slow" style="bottom:6%;left:6%" data-depth="14">
          <span class="ic"><?= svg_icon('devops') ?></span>
          <div><b>DevOps &amp; CI/CD</b><span>Automated delivery</span></div>
        </div>
        <div class="orb orb-blue float-slow" style="top:-10%;left:-10%"></div>
        <div class="orb orb-violet float-slower" style="bottom:-15%;right:-10%"></div>
      </div>
    </div>

    <div class="logo-strip" data-reveal="scale">
      <div class="logo-strip-label"><span>Trusted by Leading Enterprises</span></div>
      <div class="logo-panel">
        <div class="logo-marquee" aria-hidden="true">
          <div class="logo-track">
            <?php foreach (array_merge($logoRowA, $logoRowA) as $logo): ?>
              <span class="logo-item">
                <span class="logo-glyph-chip <?= h($logo['color']) ?>"><?= logo_glyph($logo['glyph']) ?></span>
                <span><?= h($logo['name']) ?></span>
              </span>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="logo-marquee" aria-hidden="true">
          <div class="logo-track reverse">
            <?php foreach (array_merge($logoRowB, $logoRowB) as $logo): ?>
              <span class="logo-item">
                <span class="logo-glyph-chip <?= h($logo['color']) ?>"><?= logo_glyph($logo['glyph']) ?></span>
                <span><?= h($logo['name']) ?></span>
              </span>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <p class="logo-panel-note">Representative enterprise partner marks shown for illustration.</p>
    </div>
  </div>
  <div class="scroll-indicator" aria-hidden="true"><span>Scroll</span><span class="line"></span></div>
</section>

<!-- ================= WHO WE ARE ================= -->
<section class="section">
  <div class="container">
    <div class="two-col">
      <div data-reveal="left">
        <div class="eyebrow">Who We Are</div>
        <h2>A Strategic Technology Partner — Not Just Another Staffing Firm</h2>
        <p class="lead" style="margin-top:18px">We are a next-generation technology partner combining strategic advisory, advanced engineering, and AI-powered talent intelligence.</p>
        <p style="margin-top:16px">Unlike traditional staffing firms, we integrate enterprise technology consulting, Cloud &amp; DevOps engineering, AI &amp; data transformation, and scalable IT workforce solutions into a single, accountable partnership.</p>
        <p style="margin-top:12px">Our model is built for long-term partnerships, measurable impact, and sustainable growth.</p>
        <a href="/about.php" class="btn btn-ghost magnetic" style="margin-top:28px">More About Us <span class="arrow"><?= svg_icon('arrow') ?></span></a>
      </div>
      <div class="grid-12" data-reveal="right" data-stagger>
        <div class="card" style="grid-column:span 6" data-reveal="scale">
          <div class="card-icon"><?= svg_icon('target') ?></div>
          <h3>Strategic Execution</h3>
          <p>Advisory insight paired with hands-on implementation.</p>
        </div>
        <div class="card" style="grid-column:span 6" data-reveal="scale">
          <div class="card-icon"><?= svg_icon('people') ?></div>
          <h3>AI-Powered Talent</h3>
          <p>Faster hiring, better alignment, higher retention.</p>
        </div>
        <div class="card" style="grid-column:span 6" data-reveal="scale">
          <div class="card-icon"><?= svg_icon('scale') ?></div>
          <h3>Scalable Models</h3>
          <p>Flexible delivery frameworks built for enterprise needs.</p>
        </div>
        <div class="card" style="grid-column:span 6" data-reveal="scale">
          <div class="card-icon"><?= svg_icon('gauge') ?></div>
          <h3>Enterprise Governance</h3>
          <p>Structured processes with measurable KPIs.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ================= SERVICES ================= -->
<section class="section bg-mesh">
  <div class="container">
    <div class="section-head center" data-reveal>
      <div class="eyebrow" style="margin-inline:auto">Our Services</div>
      <h2>Enterprise-Grade Solutions, End to End</h2>
      <p class="lead" style="margin-top:16px">IT consulting, software engineering, and talent intelligence — engineered around your roadmap, not ours.</p>
    </div>
    <div class="cards-grid" data-stagger>
      <div class="card" data-reveal="scale">
        <div class="card-icon"><?= svg_icon('ai') ?></div>
        <h3>AI &amp; Data Solutions</h3>
        <p>Machine learning deployment, predictive analytics, and AI governance frameworks.</p>
      </div>
      <div class="card" data-reveal="scale">
        <div class="card-icon"><?= svg_icon('cloud') ?></div>
        <h3>Cloud Architecture</h3>
        <p>Scalable cloud migration and architecture across AWS, Azure, and GCP.</p>
      </div>
      <div class="card" data-reveal="scale">
        <div class="card-icon"><?= svg_icon('shield') ?></div>
        <h3>Cybersecurity &amp; Compliance</h3>
        <p>Risk assessments, security architecture, and compliance readiness.</p>
      </div>
      <div class="card" data-reveal="scale">
        <div class="card-icon"><?= svg_icon('devops') ?></div>
        <h3>DevOps &amp; CI/CD</h3>
        <p>Automated pipelines, infrastructure as code, and continuous delivery.</p>
      </div>
    </div>
  </div>
</section>

<!-- ================= PROCESS ================= -->
<section class="section">
  <div class="container">
    <div class="two-col">
      <div data-reveal="left">
        <div class="eyebrow">How We Work</div>
        <h2>A Delivery Model Built for Enterprise Accountability</h2>
        <p class="lead" style="margin-top:16px">Every engagement follows a structured, transparent process — from discovery to measurable outcomes.</p>
      </div>
      <div class="timeline" data-reveal="right">
        <div class="timeline-item">
          <b>Discover &amp; Assess</b>
          <p>We map your current architecture, talent gaps, and business objectives.</p>
        </div>
        <div class="timeline-item">
          <b>Design the Roadmap</b>
          <p>A phased plan aligning technology investment with measurable KPIs.</p>
        </div>
        <div class="timeline-item">
          <b>Engineer &amp; Deploy</b>
          <p>Hands-on delivery across cloud, AI, security, and DevOps workstreams.</p>
        </div>
        <div class="timeline-item">
          <b>Measure &amp; Scale</b>
          <p>Continuous governance and reporting so outcomes compound over time.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ================= WHY CHOOSE US ================= -->
<section class="section bg-mesh">
  <div class="container">
    <div class="section-head center" data-reveal>
      <div class="eyebrow" style="margin-inline:auto">Why QuantAI Corp</div>
      <h2>Why Leading Enterprises Choose Us</h2>
    </div>
    <div class="cards-grid" data-stagger>
      <div class="card" data-reveal="scale">
        <div class="card-icon"><?= svg_icon('target') ?></div>
        <h3>Strategic Execution</h3>
        <p>Advisory insight with hands-on implementation.</p>
      </div>
      <div class="card" data-reveal="scale">
        <div class="card-icon"><?= svg_icon('people') ?></div>
        <h3>AI-Powered Talent</h3>
        <p>Faster hiring. Better alignment. Higher retention.</p>
      </div>
      <div class="card" data-reveal="scale">
        <div class="card-icon"><?= svg_icon('scale') ?></div>
        <h3>Scalable Models</h3>
        <p>Flexible delivery frameworks for enterprise needs.</p>
      </div>
      <div class="card" data-reveal="scale">
        <div class="card-icon"><?= svg_icon('gauge') ?></div>
        <h3>Enterprise Governance</h3>
        <p>Structured processes with measurable KPIs.</p>
      </div>
    </div>

    <div class="stats-bar" style="margin-top:64px" data-reveal="scale">
      <div class="stat"><b><span class="count" data-count="120" data-suffix="+">0</span></b><span>Engagements Delivered</span></div>
      <div class="stat"><b><span class="count" data-count="35" data-suffix="+">0</span></b><span>Enterprise Clients</span></div>
      <div class="stat"><b><span class="count" data-count="98" data-suffix="%">0</span></b><span>Retention Rate</span></div>
      <div class="stat"><b><span class="count" data-count="9" data-suffix="+">0</span></b><span>Years Combined Leadership</span></div>
    </div>
  </div>
</section>

<!-- ================= TESTIMONIALS ================= -->
<section class="section">
  <div class="container">
    <div class="section-head center" data-reveal>
      <div class="eyebrow" style="margin-inline:auto">Client Voices</div>
      <h2>Partnership That Compounds Over Time</h2>
    </div>
    <div class="testi-track" data-reveal="scale">
      <div class="testi-slide is-active">
        <div class="card testi-card">
          <p class="testi-quote">"QuantAI Corp didn't just staff our cloud migration — they architected it. The difference between a vendor and a partner is accountability, and that's exactly what we got."</p>
          <div class="testi-person"><b>VP of Engineering</b><span>Enterprise Financial Services</span></div>
        </div>
      </div>
      <div class="testi-slide">
        <div class="card testi-card">
          <p class="testi-quote">"Their AI-powered talent model cut our time-to-hire in half while improving retention. Every placement understood our stack before day one."</p>
          <div class="testi-person"><b>Director of Talent Acquisition</b><span>Healthcare Technology Group</span></div>
        </div>
      </div>
      <div class="testi-slide">
        <div class="card testi-card">
          <p class="testi-quote">"The governance framework QuantAI built for our AI rollout gave our board the confidence to move faster, not slower."</p>
          <div class="testi-person"><b>Chief Information Officer</b><span>Global Logistics Enterprise</span></div>
        </div>
      </div>
    </div>
    <div class="testi-controls"></div>
  </div>
</section>

<!-- ================= LATEST INSIGHTS ================= -->
<?php if ($latestPosts): ?>
<section class="section bg-mesh">
  <div class="container">
    <div class="section-head center" data-reveal>
      <div class="eyebrow" style="margin-inline:auto">Latest Insights</div>
      <h2>Perspectives on Enterprise Technology</h2>
    </div>
    <div class="blog-grid" data-stagger>
      <?php foreach ($latestPosts as $post): ?>
        <a href="/blog-post.php?slug=<?= h($post['slug']) ?>" class="blog-card" data-reveal="scale">
          <div class="blog-cover" style="--cover:var(--grad-brand)"></div>
          <div class="blog-body">
            <span class="blog-tag"><?= h($post['category']) ?></span>
            <h3><?= h($post['title']) ?></h3>
            <p><?= h(excerpt_text($post['excerpt'], 110)) ?></p>
            <span class="blog-more">Read More <?= svg_icon('arrow') ?></span>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ================= CTA ================= -->
<section class="section">
  <div class="container">
    <div class="card" style="padding:64px;text-align:center;background:var(--grad-brand-soft)" data-reveal="scale">
      <h2>Ready to Build the Future of Your Enterprise?</h2>
      <p class="lead" style="max-width:560px;margin:16px auto 32px">Whether you're modernizing infrastructure, deploying AI, or scaling your workforce — we're ready to partner with you.</p>
      <div class="flex gap-3" style="justify-content:center;flex-wrap:wrap">
        <a href="/contact.php" class="btn btn-primary magnetic">Talk to Our Team <span class="arrow"><?= svg_icon('arrow') ?></span></a>
        <a href="/careers.php" class="btn btn-ghost magnetic">View Open Roles</a>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
