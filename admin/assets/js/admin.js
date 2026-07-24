(function () {
  'use strict';

  const toggle = document.getElementById('sidebarToggle');
  const sidebar = document.getElementById('adminSidebar');
  const overlay = document.getElementById('sidebarOverlay');
  if (toggle && sidebar) {
    toggle.addEventListener('click', () => {
      sidebar.classList.toggle('is-open');
      overlay && overlay.classList.toggle('is-open');
    });
    overlay &&
      overlay.addEventListener('click', () => {
        sidebar.classList.remove('is-open');
        overlay.classList.remove('is-open');
      });
  }

  // Confirm before any destructive action (delete buttons/forms)
  document.querySelectorAll('[data-confirm]').forEach((el) => {
    el.addEventListener('submit', (e) => {
      if (!window.confirm(el.getAttribute('data-confirm') || 'Are you sure?')) {
        e.preventDefault();
      }
    });
    el.addEventListener('click', (e) => {
      if (el.tagName === 'A' && !window.confirm(el.getAttribute('data-confirm') || 'Are you sure?')) {
        e.preventDefault();
      }
    });
  });

  // Auto-submit status <select> dropdowns inside admin tables
  document.querySelectorAll('.pill-select[data-autosubmit]').forEach((sel) => {
    sel.addEventListener('change', () => sel.closest('form').submit());
  });

  // Simple client-side table search
  document.querySelectorAll('[data-table-search]').forEach((input) => {
    const table = document.querySelector(input.getAttribute('data-table-search'));
    if (!table) return;
    input.addEventListener('input', () => {
      const q = input.value.trim().toLowerCase();
      table.querySelectorAll('tbody tr').forEach((row) => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
      });
    });
  });

  // Auto-dismiss alerts
  document.querySelectorAll('.alert').forEach((alert) => {
    setTimeout(() => (alert.style.transition = 'opacity .5s'), 4000);
    setTimeout(() => (alert.style.opacity = '0'), 4500);
  });
})();
