# Kiswa Health Centre III — Drug Inventory & Dispensing System

**Version 2.0** · Laravel 12 · PHP 8.2 · SQLite · Bootstrap 5.3

> A web-based pharmacy and drug inventory management system built for **Kiswa Health Centre III**, Kiswa, Nakawa Division, Kampala, Uganda. The system replaces manual paper-based stock cards and dispensing registers with a fast, role-aware, fully audited digital workflow.

---

## Table of Contents

1. [Quick Start](#quick-start)
2. [Demo Credentials](#demo-credentials)
3. [How the System Works](#how-the-system-works)
4. [Modules](#modules)
5. [Role Permissions](#role-permissions)
6. [Database Schema](#database-schema)
7. [Project Structure](#project-structure)
8. [Design System](#design-system)
9. [Technology Stack](#technology-stack)
10. [Key Design Decisions](#key-design-decisions)
11. [Academic Context](#academic-context)

---

## Quick Start

### Prerequisites

| Requirement | Version |
|---|---|
| PHP | 8.2 or higher |
| Composer | Latest stable |
| Node.js | 18+ (only needed to rebuild frontend assets) |

### Installation

```bash
# 1. Enter the project directory
cd Kiswa_Health

# 2. Install PHP dependencies
composer install

# 3. Copy environment file
cp .env.example .env        # Windows: copy .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Run migrations and seed demo data
php artisan migrate --seed

# 6. Create storage symlink (for profile picture uploads)
php artisan storage:link

# 7. Start the development server
php artisan serve
```

Open **http://localhost:8000** in your browser.

> **SQLite** is used out of the box — no database server setup needed. The database file lives at `database/database.sqlite`.

---

## Demo Credentials

| Role | Email | Password |
|---|---|---|
| Administrator | admin@kiswahealth.ug | admin@123 |
| Stores In-Charge | stores@kiswahealth.ug | stores@123 |
| Pharmacy Technician | pharmacy@kiswahealth.ug | pharmacy@123 |
| Pharmacy Technician 2 | pharmacy2@kiswahealth.ug | pharmacy@123 |

> All patient references in the demo data are anonymised OPD numbers. No real patient data is stored.

---

## How the System Works

### User Journey — Step by Step

```
Homepage (/)
    │
    ├── Public landing page — no login required
    │   Shows: what the system manages, how it works, staff sign-in CTA
    │
    └── [Staff Sign In] ──► Login (/login)
                                │
                                ▼
                          Dashboard (/dashboard)
                          ┌─────────────────────────────────────────┐
                          │  Stat cards (clickable shortcuts)        │
                          │  • Registered Drugs  → Drug Register     │
                          │  • Active Batches    → Stock Batches     │
                          │  • Dispensed Today   → Dispensing Log    │
                          │  • Low Stock Drugs   → Procurement Form  │
                          │  • Near-Expiry (90d) → Expiry Report     │
                          │  • Expired Batches   → Expiry Report     │
                          │                                          │
                          │  Alert banners (auto-hidden when clear)  │
                          │  Monthly dispensing trend chart          │
                          │  Stock by category table                 │
                          │  Recent dispensing activity feed         │
                          └─────────────────────────────────────────┘
                                │
                    ┌───────────┼───────────┬──────────────┐
                    ▼           ▼           ▼              ▼
              Inventory    Pharmacy    Procurement     Reports
```

### Core Workflow — Receiving & Dispensing

```
1. REGISTER DRUG
   Drugs → Add Drug
   Enter: name, category (EMHS), formulation, strength, unit, reorder level

2. RECEIVE STOCK (GRN)
   Stock Batches → Receive Stock
   Enter: drug, supplier, batch number, quantity, expiry date, unit cost
   → System updates drug's total_quantity automatically

3. DISPENSE DRUG
   Dispense Drug → Select drug
   → AJAX loads available batches sorted by expiry (FEFO)
   → System pre-selects nearest-expiring batch
   → Enter quantity + patient OPD reference
   → System: creates dispensing record, deducts from batch, updates drug total

4. MONITOR ALERTS
   Dashboard auto-shows banners when:
   • Any drug total_quantity ≤ reorder_level  → Low Stock Alert
   • Any batch expires within 90 days         → Expiry Warning

5. PROCURE
   Procurement → Create Requisition
   → Pre-filled with all drugs currently below reorder level
   → Assign supplier, submit request
   → Track status: Draft → Submitted → Approved → Fulfilled

6. REPORT
   Reports → Stock Balance / Expiry / Dispensing / Usage Pattern
   → Filter, review, print (browser print hides UI chrome)
```

### Authentication & Access Control Flow

```
Request hits route
        │
        ├── No auth cookie → redirect to /login
        │
        └── Auth cookie present
                │
                ├── Role check (RoleMiddleware)
                │       │
                │       ├── Pass  → controller executes
                │       └── Fail  → 403 Forbidden
                │
                └── is_active = false → treated as unauthenticated
```

---

## Modules

### Module 1 — Public Homepage (`/home`, `/`)

- Marketing landing page — no authentication required
- Sections: hero banner, three principles, feature accordion, staff sign-in CTA
- Footer: facility name, page nav links, sign-in button
- Authenticated users visiting `/` are redirected to the dashboard; `/home` always shows the homepage

### Module 2 — Dashboard (`/dashboard`)

- Six stat cards — each is a **clickable shortcut** to the relevant section
- Automatic alert banners: low stock (amber) and near-expiry (red), dismissible
- Monthly dispensing trend bar chart (Chart.js, last 6 months)
- Stock by category breakdown table
- Recent dispensing activity feed (last 7 days)
- Greeting band with current date and quick-action buttons

### Module 3 — Drug Register (`/drugs`)

- Register drugs with: generic name, brand name, EMHS category, formulation, strength, unit of measure, reorder level
- Drug list with search, category filter, formulation filter, stock-level filter
- Colour-coded stock status pills: **Adequate** (green) / **Low** (amber) / **Out of Stock** (red)
- Drug profile page: batch list (FEFO order), dispensing history, stock progress bar

### Module 4 — Stock Batches (`/stock`)

- Record incoming deliveries (GRN): supplier, batch number, delivery note, quantity, manufacture & expiry dates, unit cost
- Batch list with expiry colour coding and status tracking
- Status values: `active` / `depleted` / `expired` / `quarantined`
- Full batch detail page with usage percentage bar

### Module 5 — Pharmacy Dispensing (`/dispensing`)

- Quick dispensing form: patient OPD reference, drug selection, batch (FEFO auto-selected), quantity
- AJAX batch loader at `/api/drugs/{drug}/batches` — returns available batches sorted by expiry
- Quantity guard — cannot exceed available batch quantity (client + server validated)
- Atomic transaction: dispensing record created + batch deducted + drug total recalculated
- Dispensing log with date-range filter and printable detail pages

### Module 6 — Suppliers & Procurement (`/suppliers`, `/procurement`)

- Supplier directory: name, code, contact person, phone, email, address
- Procurement requisition form — auto-populated with all below-reorder drugs
- Status workflow: Draft → Submitted → Approved → Fulfilled
- Printable requisition document

### Module 7 — Reports (`/reports/*`)

| Report | URL | Description |
|---|---|---|
| Stock Balance | `/reports/stock-balance` | Current quantities, filterable by category and stock level |
| Expiry Report | `/reports/expiry` | Batches expiring within 30/60/90/180 days + expired count |
| Dispensing Summary | `/reports/dispensing` | Date-range transaction log, top 10 drugs by usage |
| Usage Pattern | `/reports/usage-pattern` | Fast/slow-moving classification over 1/3/6/12 months |

All reports are **print-ready** — the sidebar and header are hidden by CSS print media queries.

### Module 8 — User Management (`/users`) *(Administrator only)*

- Create, edit, deactivate user accounts
- Assign roles: Administrator / Stores In-Charge / Pharmacy Technician
- Password reset by administrator

### Module 9 — My Profile (`/profile`)

- **Profile picture** — upload JPG/PNG/WebP (max 2 MB); live preview before save; remove button
- **Display name** — update name shown across the UI
- **Change password** — current password verified; strength meter; show/hide toggle
- **Appearance** — dark / light mode toggle with preview swatches; preference persisted in `localStorage`

---

## Role Permissions

| Feature | Administrator | Stores In-Charge | Pharmacy Technician |
|---|:---:|:---:|:---:|
| Dashboard | ✓ | ✓ | ✓ |
| View Drug Register | ✓ | ✓ | ✓ |
| Register / Edit Drug | ✓ | ✓ | ✓ |
| Deactivate Drug | ✓ | ✓ | — |
| Receive Stock (GRN) | ✓ | ✓ | ✓ |
| Dispense Drug | ✓ | ✓ | ✓ |
| View Dispensing Log | ✓ | ✓ | ✓ |
| Manage Suppliers | ✓ | ✓ | ✓ |
| Create Procurement Request | ✓ | ✓ | ✓ |
| Approve / Fulfil Requisition | ✓ | ✓ | — |
| All Reports | ✓ | ✓ | ✓ |
| User Management | ✓ | — | — |
| My Profile | ✓ | ✓ | ✓ |

---

## Database Schema

```
users
  id, name, email, role, phone, avatar, is_active, password, timestamps

suppliers
  id, name, code, contact_person, phone, email, address, is_active, timestamps

drugs
  id, name, brand_name, category, formulation, strength,
  unit_of_measure, reorder_level, total_quantity, is_active, notes, timestamps

stock_batches
  id, drug_id, supplier_id, received_by,
  batch_number, quantity_received, quantity_remaining,
  manufacture_date, expiry_date, received_date,
  delivery_note_number, unit_cost, status, notes, timestamps

dispensing_records
  id, drug_id, stock_batch_id, dispensed_by,
  patient_reference, patient_name, quantity_dispensed,
  dispensed_date, dispensed_time, diagnosis, notes, timestamps

procurement_requests
  id, request_number, requested_by, supplier_id,
  status, request_date, notes, timestamps

procurement_request_items
  id, procurement_request_id, drug_id,
  quantity_requested, current_stock, reorder_level, justification, timestamps
```

> The `avatar` column was added in migration `2024_01_01_000060_add_avatar_to_users_table.php`. Uploaded files are stored in `storage/app/public/avatars/` and served via the `public/storage` symlink.

---

## Project Structure

```
Kiswa_Health/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php          # Login / logout
│   │   │   ├── DashboardController.php     # Dashboard metrics & charts
│   │   │   ├── DrugController.php          # Drug register CRUD
│   │   │   ├── StockBatchController.php    # GRN / batch management
│   │   │   ├── DispensingController.php    # Dispensing + AJAX batch API
│   │   │   ├── SupplierController.php      # Suppliers + procurement
│   │   │   ├── ReportController.php        # All 4 report views
│   │   │   ├── UserController.php          # User management (admin)
│   │   │   └── ProfileController.php       # Profile picture, password, name
│   │   └── Middleware/
│   │       └── RoleMiddleware.php          # Route role guard
│   ├── Models/
│   │   ├── User.php                        # avatar_url accessor, role helpers
│   │   ├── Drug.php
│   │   ├── StockBatch.php                  # expiry_status, days_to_expiry
│   │   ├── Supplier.php
│   │   ├── DispensingRecord.php
│   │   ├── ProcurementRequest.php
│   │   └── ProcurementRequestItem.php
│   └── Providers/
│       └── AppServiceProvider.php
│
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 2024_01_01_000010_create_suppliers_table.php
│   │   ├── 2024_01_01_000020_create_drugs_table.php
│   │   ├── 2024_01_01_000030_create_stock_batches_table.php
│   │   ├── 2024_01_01_000040_create_dispensing_records_table.php
│   │   ├── 2024_01_01_000050_create_procurement_requests_table.php
│   │   └── 2024_01_01_000060_add_avatar_to_users_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── UserSeeder.php
│       ├── SupplierSeeder.php
│       ├── DrugSeeder.php
│       ├── StockBatchSeeder.php
│       └── DispensingRecordSeeder.php
│
├── resources/
│   ├── css/app.css                         # Source CSS (not compiled — served directly)
│   ├── js/app.js                           # Source JS
│   └── views/
│       ├── layouts/app.blade.php           # Master dashboard layout
│       ├── welcome.blade.php               # Public homepage
│       ├── profile.blade.php               # My Profile page
│       ├── dashboard.blade.php
│       ├── auth/login.blade.php
│       ├── drugs/
│       ├── stock/
│       ├── dispensing/
│       ├── suppliers/
│       ├── procurement/
│       ├── reports/
│       └── users/
│
├── public/
│   ├── css/app.css                         # Compiled/served stylesheet
│   ├── js/app.js                           # Compiled/served JS
│   └── storage/                           # Symlink → storage/app/public
│
├── routes/web.php                          # All application routes
├── .env                                    # Environment configuration
├── .env.example                            # Environment template
└── composer.json
```

---

## Design System

The dashboard UI is aligned with the public homepage to create a consistent visual identity.

### Fonts

| Use | Family | Weights |
|---|---|---|
| Headings, card titles, labels | **Manrope** | 600, 700, 800 |
| Body text, UI copy | **DM Sans** | 400, 500, 600, 700 |

Both fonts are loaded from Google Fonts in `layouts/app.blade.php`.

### Colour Palette

| Token | Hex | Use |
|---|---|---|
| `--k-green` | `#176b4a` | Primary brand green — buttons, sidebar, header nav |
| `--k-green-dark` | `#104c36` | Hover states, headings |
| `--k-lime` | `#d7ed78` | Accent — active sidebar indicator, CTA highlight |
| `--k-coral` | `#e78369` | Alert eyebrow accent |
| `--bg-page` | `#f2faf6` | Page background (warm green tint) |
| `--bg-card` | `#FFFFFF` | Card surfaces |
| `--border-color` | `#d0e8d8` | Card and input borders |
| `--text-primary` | `#172a22` | Main body text |
| `--text-muted` | `#64746b` | Secondary / hint text |

### Dark Mode

Toggle via **My Profile → Appearance** or the user menu. Preference is saved to `localStorage` as `kiswa-theme` (`"light"` / `"dark"`) and restored before first paint via an inline script in `<head>` — no flash of wrong theme.

Dark mode overrides live in the `[data-theme="dark"]` block at the bottom of `public/css/app.css`.

### Buttons

All buttons use **pill shape** (`border-radius: 99px`) with a subtle lift (`translateY(-1px)`) on hover. Variants:

| Class | Use |
|---|---|
| `.btn-kiswa` | Primary solid green action |
| `.btn-outline-kiswa` | Secondary green outline |
| `.btn-lime` | Homepage-style lime CTA |
| `.btn-warning` | Amber alert action |
| `.btn-danger` | Destructive / delete action |
| `.btn-icon` | Square icon-only button (32 × 32 px) |

---

## Technology Stack

| Layer | Technology |
|---|---|
| Backend | PHP 8.2, Laravel 12 |
| Database | SQLite 3 |
| Frontend | HTML5, CSS3, Bootstrap 5.3, Bootstrap Icons 1.11 |
| Fonts | Manrope + DM Sans (Google Fonts) |
| Charts | Chart.js 4.4 |
| JavaScript | Vanilla ES6 — AJAX, form validation, dark mode, counters |
| Authentication | Laravel session-based auth + custom `RoleMiddleware` |
| Templating | Laravel Blade |
| File Storage | Laravel `Storage` disk (`public`) + `storage:link` symlink |
| Print/Export | CSS `@media print` — hides chrome, produces clean PDF via browser |

---

## Key Design Decisions

**FEFO (First-Expiry, First-Out)**
The dispensing AJAX endpoint (`/api/drugs/{drug}/batches`) returns active batches sorted by `expiry_date ASC`. The UI pre-selects the first result, ensuring the nearest-expiring stock is used first — aligned with Uganda NMS dispensing guidelines and WHO good pharmacy practice.

**Atomic dispensing transactions**
Each dispense wraps in a database transaction: `dispensing_records` row inserted + `stock_batches.quantity_remaining` decremented + `drugs.total_quantity` recalculated via `recalculateTotalQuantity()`. If any step fails, the whole operation rolls back — stock figures are always consistent.

**Real-time stock totals**
`drugs.total_quantity` is a denormalised sum of all active batch quantities for that drug. It is recalculated after every GRN receipt and every dispensing transaction. This avoids an aggregate JOIN on every dashboard and report load.

**Role middleware**
`RoleMiddleware` accepts comma-separated role names: `middleware('role:administrator,stores_in_charge')`. This keeps route files readable and avoids duplicating permission logic across controllers.

**Sidebar navigation**
Includes a **Back to Homepage** link (`route('home')` → `/home`) at the bottom. The `/home` route always renders `welcome.blade.php` regardless of auth state, while `/` redirects authenticated users to the dashboard.

**Dark mode — no flash**
An inline `<script>` in `<head>` reads `localStorage.getItem('kiswa-theme')` and sets `data-theme="dark"` on `<html>` before the stylesheet is applied. This prevents a flash of the light theme on page load for users who prefer dark mode.

**Profile pictures**
Uploaded via `ProfileController@updateAvatar` to `storage/app/public/avatars/`. The `User::getAvatarUrlAttribute()` accessor returns a full public URL via `asset('storage/' . $this->avatar)`. Old files are deleted on replacement.

---

## Seeded Demo Data

| Dataset | Details |
|---|---|
| **Users** | 4 accounts covering all 3 roles |
| **Drugs** | 28 Uganda EMHS medicines across 10 categories |
| **Suppliers** | NMS, JMS, MEDS Uganda |
| **Stock Batches** | 17 batches including intentional low-stock and near-expiry scenarios |
| **Dispensing Records** | 24 transactions over the last 14 days |

---

## Facility

**Kiswa Health Centre III**
Kiswa, Nakawa Division, Kampala, Uganda
*Public health facility under Kampala Capital City Authority (KCCA)*
Health Hotline: **0800-100-066**

---

## Academic Context

Developed as a **Bachelor of Science in Computer Science / Information Technology** final-year project addressing manual drug inventory management at lower-level health facilities in Uganda — leading to stock-outs, drug expiry wastage, and inaccurate dispensing records.

**Study scope:** Drug inventory, dispensing transactions, expiry monitoring, procurement support at Kiswa Health Centre III.

---

*© 2026 Kiswa Health Centre III — Republic of Uganda. Developed for academic purposes.*
#   D r u g - i n v e n t o r y - m a n a g e m e n t - s y s t e m  
 