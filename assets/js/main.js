/* =====================================================================
   QuantAI Corp — Site interactions (vanilla JS, no external deps)
   ===================================================================== */
(function () {
  'use strict';

  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---------------- Page loader ---------------- */
  window.addEventListener('load', () => {
    const loader = document.querySelector('.page-loader');
    if (loader) setTimeout(() => loader.classList.add('is-hidden'), 250);
  });

  /* ---------------- Header scroll state + progress bar ---------------- */
  const header = document.querySelector('.site-header');
  const progress = document.querySelector('.nav-progress');
  function onScroll() {
    const y = window.scrollY || document.documentElement.scrollTop;
    if (header) header.classList.toggle('is-scrolled', y > 24);
    if (progress) {
      const h = document.documentElement;
      const scrollable = h.scrollHeight - h.clientHeight;
      progress.style.width = scrollable > 0 ? (y / scrollable) * 100 + '%' : '0%';
    }
  }
  document.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  /* ---------------- Mobile drawer ---------------- */
  const toggle = document.querySelector('.nav-toggle');
  const drawer = document.querySelector('.mobile-drawer');
  const overlay = document.querySelector('.drawer-overlay');
  function closeDrawer() {
    drawer && drawer.classList.remove('is-open');
    overlay && overlay.classList.remove('is-open');
    document.body.style.overflow = '';
  }
  if (toggle && drawer) {
    toggle.addEventListener('click', () => {
      const willOpen = !drawer.classList.contains('is-open');
      drawer.classList.toggle('is-open', willOpen);
      overlay && overlay.classList.toggle('is-open', willOpen);
      document.body.style.overflow = willOpen ? 'hidden' : '';
    });
    overlay && overlay.addEventListener('click', closeDrawer);
    drawer.querySelectorAll('a').forEach((a) => a.addEventListener('click', closeDrawer));
  }

  /* ---------------- Scroll reveal ---------------- */
  const revealEls = document.querySelectorAll('[data-reveal]');
  if ('IntersectionObserver' in window && !reduceMotion) {
    const io = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            io.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.15, rootMargin: '0px 0px -40px 0px' }
    );
    revealEls.forEach((el, i) => {
      el.style.setProperty('--i', i % 8);
      io.observe(el);
    });
  } else {
    revealEls.forEach((el) => el.classList.add('is-visible'));
  }

  /* ---------------- Animated counters ---------------- */
  const counters = document.querySelectorAll('[data-count]');
  function animateCount(el) {
    const target = parseFloat(el.getAttribute('data-count'));
    const suffix = el.getAttribute('data-suffix') || '';
    const decimals = el.getAttribute('data-decimals') ? parseInt(el.getAttribute('data-decimals'), 10) : 0;
    const duration = 1600;
    const start = performance.now();
    function tick(now) {
      const p = Math.min((now - start) / duration, 1);
      const eased = 1 - Math.pow(1 - p, 3);
      const val = target * eased;
      el.textContent = (decimals ? val.toFixed(decimals) : Math.round(val)) + suffix;
      if (p < 1) requestAnimationFrame(tick);
    }
    requestAnimationFrame(tick);
  }
  if (counters.length && 'IntersectionObserver' in window) {
    const cio = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            animateCount(entry.target);
            cio.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.6 }
    );
    counters.forEach((el) => cio.observe(el));
  }

  /* ---------------- Magnetic buttons ---------------- */
  if (!reduceMotion && window.matchMedia('(hover:hover)').matches) {
    document.querySelectorAll('.magnetic').forEach((el) => {
      el.addEventListener('mousemove', (e) => {
        const r = el.getBoundingClientRect();
        const x = e.clientX - r.left - r.width / 2;
        const y = e.clientY - r.top - r.height / 2;
        el.style.transform = `translate(${x * 0.18}px, ${y * 0.32}px)`;
      });
      el.addEventListener('mouseleave', () => (el.style.transform = ''));
    });
  }

  /* ---------------- Cursor glow ---------------- */
  const glow = document.querySelector('.cursor-glow');
  if (glow && !reduceMotion) {
    window.addEventListener('mousemove', (e) => {
      glow.style.left = e.clientX + 'px';
      glow.style.top = e.clientY + 'px';
    });
  }

  /* ---------------- Hero mouse parallax ---------------- */
  const heroVisual = document.querySelector('[data-parallax]');
  if (heroVisual && !reduceMotion && window.matchMedia('(hover:hover)').matches) {
    const layers = heroVisual.querySelectorAll('[data-depth]');
    heroVisual.addEventListener('mousemove', (e) => {
      const r = heroVisual.getBoundingClientRect();
      const cx = (e.clientX - r.left) / r.width - 0.5;
      const cy = (e.clientY - r.top) / r.height - 0.5;
      layers.forEach((layer) => {
        const depth = parseFloat(layer.getAttribute('data-depth')) || 10;
        layer.style.transform = `translate(${cx * depth}px, ${cy * depth}px)`;
      });
    });
  }

  /* ---------------- Testimonial carousel ---------------- */
  const track = document.querySelector('.testi-track');
  if (track) {
    const slides = track.querySelectorAll('.testi-slide');
    const dotsWrap = document.querySelector('.testi-controls');
    let idx = 0;
    let timer;
    function show(i) {
      idx = (i + slides.length) % slides.length;
      slides.forEach((s, n) => s.classList.toggle('is-active', n === idx));
      if (dotsWrap) {
        [...dotsWrap.children].forEach((d, n) => d.classList.toggle('is-active', n === idx));
      }
    }
    if (dotsWrap) {
      slides.forEach((_, n) => {
        const dot = document.createElement('button');
        dot.type = 'button';
        dot.className = 'testi-dot' + (n === 0 ? ' is-active' : '');
        dot.setAttribute('aria-label', 'Show testimonial ' + (n + 1));
        dot.addEventListener('click', () => {
          show(n);
          restart();
        });
        dotsWrap.appendChild(dot);
      });
    }
    function restart() {
      clearInterval(timer);
      if (!reduceMotion) timer = setInterval(() => show(idx + 1), 6000);
    }
    show(0);
    restart();
  }

  /* ---------------- Floating-label select fix ---------------- */
  document.querySelectorAll('.field select').forEach((sel) => {
    const field = sel.closest('.field');
    function sync() {
      field.classList.toggle('has-value', !!sel.value);
    }
    sel.addEventListener('change', sync);
    sync();
  });

  /* ---------------- File drop UX ---------------- */
  document.querySelectorAll('.file-drop').forEach((drop) => {
    const input = drop.querySelector('input[type="file"]');
    if (!input) return;
    const label = drop.querySelector('.file-drop-label');
    function updateName() {
      if (input.files && input.files[0] && label) {
        label.innerHTML = '<b>' + input.files[0].name + '</b> selected — click to change';
      }
    }
    drop.addEventListener('click', () => input.click());
    input.addEventListener('change', updateName);
    ['dragover', 'dragenter'].forEach((evt) =>
      drop.addEventListener(evt, (e) => {
        e.preventDefault();
        drop.classList.add('is-dragover');
      })
    );
    ['dragleave', 'drop'].forEach((evt) =>
      drop.addEventListener(evt, (e) => {
        e.preventDefault();
        drop.classList.remove('is-dragover');
      })
    );
    drop.addEventListener('drop', (e) => {
      if (e.dataTransfer.files.length) {
        input.files = e.dataTransfer.files;
        updateName();
      }
    });
  });

  /* ---------------- Simple client-side form validation UX ---------------- */
  document.querySelectorAll('form[data-validate]').forEach((form) => {
    form.addEventListener('submit', (e) => {
      let valid = true;
      form.querySelectorAll('[required]').forEach((field) => {
        const wrap = field.closest('.field') || field;
        if (!field.value || (field.type === 'checkbox' && !field.checked)) {
          valid = false;
          wrap.style.setProperty('--err', '1');
          field.style.borderColor = '#ef4444';
        } else {
          field.style.borderColor = '';
        }
      });
      if (!valid) {
        e.preventDefault();
        const firstInvalid = form.querySelector('[required][style*="border-color"]');
        firstInvalid && firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
      } else {
        const btn = form.querySelector('button[type="submit"]');
        if (btn) {
          btn.dataset.originalText = btn.innerHTML;
          btn.innerHTML = 'Submitting…';
          btn.style.opacity = '.7';
          btn.style.pointerEvents = 'none';
        }
      }
    });
  });

  /* ---------------- Active nav link ---------------- */
  const path = location.pathname.split('/').pop() || 'index.php';
  document.querySelectorAll('.nav-links a, .mobile-drawer a').forEach((a) => {
    const href = a.getAttribute('href').split('/').pop();
    if (href === path) a.classList.add('is-active');
  });
})();
