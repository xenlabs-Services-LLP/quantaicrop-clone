# QuantAI Corp — Website & CMS

A premium, from-scratch enterprise website (PHP 8 + MySQL, no frameworks) with a
custom admin panel for managing contact messages, service enquiries, job
applications (with résumé uploads), and blog posts.

Built for XAMPP. All content, structure, and messaging is original and was
independently recreated — no code, markup, or graphics were copied from any
reference site.

## 1. Local setup (XAMPP)

1. Copy this folder into `C:\xampp\htdocs\quantaicrop` (already done).
2. Start **Apache** and **MySQL** in the XAMPP Control Panel.
3. Open **phpMyAdmin** (`http://localhost/phpmyadmin`) → *Import* →
   select `config/database.sql` → Go. This creates the `quantaicrop`
   database, all tables, a default admin user, and six starter blog posts.
4. If your MySQL root user has a password, update `config/db.php`
   (`DB_USER` / `DB_PASS`).
5. Visit `http://localhost/quantaicrop/index.php`.

## 2. Admin CMS

URL: `http://localhost/quantaicrop/admin/login.php`

| Field | Default |
|---|---|
| Username | `admin` |
| Password | `QuantAI@2026` |

**Change this password immediately** after first login by updating the
`admin_users` table (there is no in-app "change password" screen yet — run
this in phpMyAdmin's SQL tab with a freshly generated PHP `password_hash()`
value, or ask your developer to add a settings page):

```sql
UPDATE admin_users
SET password_hash = '<new bcrypt hash>'
WHERE username = 'admin';
```

From the admin panel you can:
- **Dashboard** — at-a-glance counts of new contacts, enquiries, applications, and posts.
- **Contacts** — view, mark new/read/archived, and delete messages from the Contact page form.
- **Enquiries** — view, update status (new/contacted/closed), and delete service-consultation requests.
- **Applications** — view applicants, download résumés, update status (new/reviewed/shortlisted/rejected/hired), and delete (also removes the stored résumé file).
- **Blog Posts** — create, edit, publish/unpublish, preview, and delete articles shown on `/blog.php`.

## 3. Editing job openings

Job listings on `/careers.php` and `/contact.php` are configured in
`includes/functions.php` → `job_openings()`. Edit that array to add, remove,
or change roles — no database change required.

## 4. Editing company info

All business details (address, phone, email, social links) live as
constants at the top of `includes/functions.php`. Update once and it
propagates across every page, the footer, and the SEO/Organization schema.

## 5. Folder structure

```
config/          Database connection + schema SQL (not web-accessible)
includes/        Shared header/footer/functions (not web-accessible directly)
assets/          CSS, JS, SVG — original design system, no external UI libraries
uploads/resumes/ Uploaded résumés (PHP execution disabled, directory listing denied)
admin/           CMS — auth-gated, noindex
*.php (root)     Public pages: index, about, it-consulting, software-engineering,
                 careers, contact, blog, blog-post
*-submit.php     Form handlers (contact, enquiry, application) — POST + CSRF only
```

## 6. Security notes

- All database queries use PDO prepared statements (no string-concatenated SQL).
- Every form includes a CSRF token, verified server-side before any write.
- Admin sessions use `httponly` + `SameSite=Lax` cookies, ID regeneration on
  login, a 60-minute idle timeout, and basic login-attempt throttling.
- Résumé uploads are validated by extension **and** real MIME type, capped
  at 5MB, renamed to random filenames on disk, and served only through the
  authenticated `admin/download-resume.php` — the `uploads/resumes/`
  directory itself denies direct HTTP access and PHP execution.
- `config/`, `includes/`, and `admin/includes/` are blocked from direct
  browser access via `.htaccess`; `config/database.sql` (and any `.sql`
  file) is blocked site-wide.
- Security headers (CSP, X-Frame-Options, X-Content-Type-Options,
  Referrer-Policy, Permissions-Policy) are set in the root `.htaccess`.
  Enable `Strict-Transport-Security` once you deploy behind HTTPS.
- All dynamic output is escaped with `h()` (htmlspecialchars) except blog
  post `content`, which is intentionally rendered as HTML because it's
  authored by trusted, authenticated admins in the CMS — never from public
  form input.

## 7. Deployment checklist

- [ ] Change the default admin password.
- [ ] Set real `DB_HOST` / `DB_USER` / `DB_PASS` in `config/db.php` for production.
- [ ] Serve over HTTPS and uncomment the HSTS header in `.htaccess`.
- [ ] Update `SITE_URL` in `includes/functions.php` if the domain changes.
- [ ] Confirm PHP `upload_max_filesize` / `post_max_size` in `php.ini` are ≥ 5MB (résumé cap).
- [ ] Set up a real mail sender (e.g. PHPMailer + SMTP) if you want email
      notifications on new submissions — currently all submissions are
      stored in the database and reviewed via the admin panel only.
- [ ] Replace the placeholder favicon/OG SVGs in `assets/images/` with final brand artwork if desired.

## 8. Design system

Custom-built (no Bootstrap/Tailwind/UI kit): dark navy/near-black base,
electric blue → cyan → violet gradient accents, glassmorphic cards, mesh
gradients, CSS + IntersectionObserver scroll reveals, animated counters,
a testimonial carousel, magnetic buttons, and a mobile drawer nav — all in
`assets/css/style.css` and `assets/js/main.js`, dependency-free for fast
Core Web Vitals.
