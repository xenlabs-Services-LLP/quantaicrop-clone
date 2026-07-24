</main>

<footer class="site-footer">
  <div class="container">
    <div class="footer-grid">
      <div class="footer-brand">
        <a href="/index.php" class="brand">
          <span class="brand-mark"><?= svg_icon('brand') ?></span>
          QuantAI Corp
        </a>
        <p><?= h(SITE_TAGLINE) ?> for enterprise-grade digital transformation.</p>
        <div class="footer-social">
          <a href="<?= h(SOCIAL_LINKEDIN) ?>" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"><?= svg_icon('linkedin') ?></a>
          <a href="<?= h(SOCIAL_FACEBOOK) ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><?= svg_icon('facebook') ?></a>
          <a href="<?= h(SOCIAL_INSTAGRAM) ?>" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><?= svg_icon('instagram') ?></a>
        </div>
      </div>
      <div class="footer-col">
        <h4>Services</h4>
        <ul>
          <li><a href="/it-consulting.php">Enterprise IT Consulting</a></li>
          <li><a href="/it-consulting.php">AI &amp; Data Solutions</a></li>
          <li><a href="/software-engineering.php">Software Engineering</a></li>
          <li><a href="/it-consulting.php">Cybersecurity &amp; Compliance</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Quick Links</h4>
        <ul>
          <li><a href="/about.php">About Us</a></li>
          <li><a href="/careers.php">Careers</a></li>
          <li><a href="/blog.php">Insights</a></li>
          <li><a href="/contact.php">Contact</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Stay Connected</h4>
        <ul style="margin-bottom:16px">
          <li class="flex gap-2 items-center" style="color:var(--text-2);font-size:.9rem"><?= svg_icon('pin') ?> <?= h(COMPANY_ADDRESS_1) ?>, <?= h(COMPANY_CITY) ?></li>
          <li class="flex gap-2 items-center"><a href="tel:<?= h(preg_replace('/[^0-9+]/', '', COMPANY_PHONE)) ?>"><?= svg_icon('phone') ?> <?= h(COMPANY_PHONE) ?></a></li>
          <li class="flex gap-2 items-center"><a href="mailto:<?= h(COMPANY_EMAIL) ?>"><?= svg_icon('mail') ?> <?= h(COMPANY_EMAIL) ?></a></li>
        </ul>
        <form class="newsletter-form" action="/contact.php" method="get">
          <input type="email" name="newsletter" placeholder="Your work email" aria-label="Email for newsletter">
          <button type="submit" aria-label="Subscribe"><?= svg_icon('arrow') ?></button>
        </form>
      </div>
    </div>
    <div class="footer-divider"></div>
    <div class="footer-bottom">
      <span>&copy; <?= date('Y') ?> <?= h(COMPANY_LEGAL) ?>. All rights reserved.</span>
      <span><?= h(COMPANY_ADDRESS_1) ?>, <?= h(COMPANY_ADDRESS_2) ?>, <?= h(COMPANY_CITY) ?></span>
    </div>
  </div>
</footer>

<script src="/assets/js/main.js" defer></script>
</body>
</html>
