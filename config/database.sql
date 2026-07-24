-- ============================================================
-- QuantAI Corp — Database Schema
-- Import this file in phpMyAdmin or via:
--   mysql -u root -p < config/database.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS quantaicrop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE quantaicrop;

-- ----------------------------------------------------------------------
-- Admin users (CMS access)
-- ----------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS admin_users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(60) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(120) NOT NULL DEFAULT '',
    email VARCHAR(160) NOT NULL DEFAULT '',
    last_login DATETIME NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Default admin login: username = admin / password = QuantAI@2026
-- CHANGE THIS PASSWORD IMMEDIATELY AFTER FIRST LOGIN (see README).
INSERT INTO admin_users (username, password_hash, full_name, email)
VALUES ('admin', '$2b$10$ETzmX2u0C2g44ACGfGxIs.cyOqSR3zv9ACNHEQaxHYnrrMe9u8sGi', 'Site Administrator', 'hr@quantaicorp.com')
ON DUPLICATE KEY UPDATE username = username;

-- ----------------------------------------------------------------------
-- Contact form submissions (general "Get in touch")
-- ----------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS contacts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL,
    phone VARCHAR(40) NULL,
    company VARCHAR(160) NULL,
    subject VARCHAR(200) NULL,
    message TEXT NOT NULL,
    status ENUM('new','read','archived') NOT NULL DEFAULT 'new',
    ip_address VARCHAR(64) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ----------------------------------------------------------------------
-- Service / solution enquiries (from service pages "Request a Consultation")
-- ----------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS enquiries (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL,
    phone VARCHAR(40) NULL,
    company VARCHAR(160) NULL,
    service_interest VARCHAR(160) NULL,
    budget_range VARCHAR(80) NULL,
    message TEXT NOT NULL,
    status ENUM('new','contacted','closed') NOT NULL DEFAULT 'new',
    ip_address VARCHAR(64) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ----------------------------------------------------------------------
-- Job / LCA applications (Careers & LCA Posting form)
-- ----------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS applications (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(80) NOT NULL,
    last_name VARCHAR(80) NOT NULL,
    email VARCHAR(160) NOT NULL,
    mobile VARCHAR(40) NOT NULL,
    location VARCHAR(160) NOT NULL,
    education VARCHAR(160) NOT NULL,
    visa_type VARCHAR(40) NOT NULL,
    role_applied VARCHAR(160) NOT NULL,
    technologies VARCHAR(255) NOT NULL,
    experience_years VARCHAR(20) NOT NULL,
    resume_stored_name VARCHAR(255) NOT NULL,
    resume_original_name VARCHAR(255) NOT NULL,
    status ENUM('new','reviewed','shortlisted','rejected','hired') NOT NULL DEFAULT 'new',
    ip_address VARCHAR(64) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ----------------------------------------------------------------------
-- Blog posts (Insights)
-- ----------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS blog_posts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(220) NOT NULL,
    slug VARCHAR(220) NOT NULL UNIQUE,
    excerpt VARCHAR(400) NOT NULL,
    content MEDIUMTEXT NOT NULL,
    category VARCHAR(80) NOT NULL DEFAULT 'Insights',
    author VARCHAR(120) NOT NULL DEFAULT 'QuantAI Corp',
    cover_accent VARCHAR(20) NOT NULL DEFAULT 'blue',
    is_published TINYINT(1) NOT NULL DEFAULT 1,
    published_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Seed the six insight articles referenced across the site
INSERT INTO blog_posts (title, slug, excerpt, content, category, cover_accent) VALUES
('The Future of AI in Enterprise Technology', 'future-of-ai-in-enterprise',
 'How predictive analytics and applied machine learning are reshaping enterprise decision-making in 2026 and beyond.',
 '<p>Enterprise AI has moved past the pilot stage. Organizations that once experimented with isolated machine learning models are now embedding AI directly into core operating workflows — from demand forecasting to automated risk scoring.</p><p>The winners in this shift share three traits: a clean, governed data foundation; a clear framework for measuring model impact against business KPIs; and a talent bench that can operate AI systems responsibly at scale.</p><p>QuantAI Corp partners with enterprise teams to build exactly that foundation — pairing strategic AI governance with hands-on engineering delivery.</p>',
 'Artificial Intelligence', 'blue'),
('Cloud Modernization Trends to Watch', 'cloud-modernization-trends',
 'A look at the architecture patterns, cost strategies, and platform choices driving enterprise cloud transformation.',
 '<p>Cloud modernization in 2026 is less about "lift and shift" and more about re-architecting for elasticity, resilience, and cost transparency across AWS, Azure, and GCP.</p><p>Enterprises are consolidating around infrastructure-as-code, adopting FinOps practices to control spend, and re-platforming legacy monoliths into modular, API-first services.</p><p>Our cloud architecture team helps organizations sequence this transformation without disrupting the systems the business depends on today.</p>',
 'Cloud & DevOps', 'cyan'),
('AI-Driven Talent Intelligence', 'ai-driven-talent-intelligence',
 'Why predictive sourcing and performance modeling are replacing traditional staffing methods for enterprise IT roles.',
 '<p>Traditional staffing relies on keyword matching and gut instinct. AI-driven talent intelligence instead models skill adjacency, project performance signals, and role-fit probability — shortening time-to-hire while improving retention.</p><p>QuantAI Corp applies this methodology across contract, contract-to-hire, and direct placement engagements for enterprise technology teams.</p>',
 'Talent Intelligence', 'purple'),
('Cybersecurity in the AI Age', 'cybersecurity-in-the-ai-age',
 'New attack surfaces introduced by AI adoption — and the governance frameworks enterprises need to close the gap.',
 '<p>As enterprises adopt generative and predictive AI, the attack surface expands: model endpoints, training data pipelines, and third-party AI vendors all introduce new risk.</p><p>A modern security posture treats AI systems as first-class assets in risk assessments, with dedicated controls for data lineage, access governance, and model monitoring.</p>',
 'Cybersecurity', 'blue'),
('DevOps Best Practices for 2026', 'devops-best-practices-2026',
 'The CI/CD, infrastructure-as-code, and platform engineering practices enterprise teams are standardizing on this year.',
 '<p>High-performing engineering organizations are converging on platform engineering: internal developer platforms that abstract infrastructure complexity behind self-service, golden-path workflows.</p><p>Paired with automated CI/CD pipelines and policy-as-code, this reduces lead time for changes while improving reliability.</p>',
 'Cloud & DevOps', 'cyan'),
('A Framework for Enterprise AI Governance', 'enterprise-ai-governance-framework',
 'Practical guardrails for deploying AI responsibly — from model risk tiers to human-in-the-loop review gates.',
 '<p>AI governance does not have to slow innovation. A tiered risk framework — classifying use cases by potential impact — lets teams move fast on low-risk applications while applying rigorous review to high-stakes decisions.</p><p>We help enterprise clients design and operationalize these frameworks as part of every AI engagement.</p>',
 'Artificial Intelligence', 'purple')
ON DUPLICATE KEY UPDATE slug = slug;
