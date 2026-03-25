Suits Invoicing App – Current Requirements Status

Overview

- This document captures the current achievements and remaining work for the Suits-Invoice Laravel Blade application, as per the product specification in suits.md.

Achieved

- Core data model and migrations implemented for the finance and ordering domain:
    - users, customers, measurements, orders, order_items, invoices, invoice_items, payments, expenses, expense_categories
- Seed data populated for key domains and wired in correct dependency order:
    - ExpenseCategory, Customer, Measurement, Order, OrderItem, Invoice, InvoiceItem, Payment, Expense seeders
- Seed order respects foreign key constraints via DatabaseSeeder sequencing.
- The seed for expenses fixed to align with the expenses schema (ten core columns; optional void columns are nullable).
- The suits.md product blueprint is reflected as the project’s module structure and data models.
- The repository now contains a comprehensive specification and data model alignment consistent with the product spec.

Modules and alignment (as per suits.md)

- Authentication and User Access
- Dashboard
- Customer Management (Profile, History, Contacts)
- Customer Measurements
- Customer Style Preferences
- Lead / Inquiry Management
- Quotation Management
- Order Management
- Production / Work Progress Tracking
- Fitting and Alteration Management
- Invoice Management
- Payment Management
- Receipt Management
- Expense Management
- Delivery Management
- Notifications and Reminders
- Reports and Analytics
- Audit Trail and Activity Logs
- Settings and Master Data
- File / Image Attachment Management
- Optional Inventory-lite Module
- Optional Vendor/ Supplier Management
- Optional Staff Performance / Commission Module
- Full End-to-End Business Workflow (Lead to Delivery)
- Build order guidance and recommended statuses/enums (as per suits.md)

What is yet to beCalled / Remaining Work

- Implement authentication flows and RBAC (Admin/Owner, Sales/Front Desk, Tailor, Accountant, Delivery)
- Build out the dashboard with role-based views and live data
- Implement Customer Management UI (Blade pages) and API
- Implement Measurements module UI, history/versioning, and current flag
- Implement Style Preferences for customers
- Lead / Inquiry Management (lead capture, status, conversion)
- Quotation Management (create/convert to order/invoice)
- Order Management (order lifecycle, items, statuses)
- Production and Fittings workflows (progress logs, fittings, alterations)
- Delivery and Logistics management (delivery status, handover)
- Invoice, Payments, Receipts core flows (issue, cancel, print)
- Expense Management (CRUD, categories, voids, attachments)
- Attachments/Files (polymorphic attachments for orders, expenses, customers)
- Reports and Analytics (Sales, Payments, Expenses, Balances, Customer Statements, Profit)
- Audit Trail and Activity Logs (action logging)
- Settings and Master Data (business info, prefixes, currency, tax)
- Notifications and Reminders (in-app, later channels)
- Tests and CI (unit/feature tests; integration tests)
- Performance and security hardening (indexes, constraints, caching)
- Documentation updates (README and module docs)

Assumptions

- Seed data and migrations are in sync with the latest suits.md section definitions.
- Role-based access control will be implemented with spatie/laravel-permission in future tasks.
- Livewire/Blade decisions will be made per module based on UX needs.
- Receipts, audit logs, and attachments data structures will be added as separate seeders/models later.

Next steps

- Run php artisan migrate:fresh --seed to validate the current state.
- Implement RolePermissionSeeder and initial RBAC policy skeletons.
- Create initial Blade pages for the core modules and wire with controllers/services.
- Add unit/feature tests for key workflows (customer, invoices, payments, expenses).
- Iterate on suits.md to reflect any evolving business requirements.

References

- suits.md sections mapped above.

**_ Owners & Sprint Cadence _**
Owners & Team

- Product Owner: TBD
- Tech Lead: TBD
- Backend Engineers: TBD
- Frontend Engineers: TBD
- QA Engineers: TBD
- DevOps Engineers: TBD

Per-Module Ownership

- Authentication and User Access — Owner: Backend
- Dashboard — Owner: Frontend
- Customer Management (Profile, History, Contacts) — Owner: Backend
- Customer Measurements — Owner: Backend
- Customer Style Preferences — Owner: Backend
- Lead / Inquiry Management — Owner: Backend
- Quotation Management — Owner: Backend
- Order Management — Owner: Backend
- Production / Work Progress Tracking — Owner: Backend
- Fitting and Alteration Management — Owner: Backend
- Invoice Management — Owner: Backend
- Payment Management — Owner: Backend
- Receipt Management — Owner: Backend
- Expense Management — Owner: Backend
- Delivery Management — Owner: Backend
- Notifications and Reminders — Owner: Backend
- Reports and Analytics — Owner: Data/BI
- Audit Trail and Activity Logs — Owner: Security
- Settings and Master Data — Owner: Admin
- File / Image Attachment Management — Owner: Backend
- Optional Inventory-lite Module — Owner: Backend
- Optional Vendor / Supplier Management — Owner: Backend
- Optional Staff Performance / Commission Module — Owner: Backend
- End-to-End Workflow (Lead to Delivery) — Owner: Product Owner
- Build/Testing Plan — Owner: QA

Sprint Cadence

- Sprint Length: 2 weeks
- Typical cadence: Planning on Monday, Review/Demo on Friday; mid-sprint checkpoint on Wednesday
- Definition of Done (DoD) for Sprint: Code merged, migrations/seeders run in CI, tests passing, docs updated, backlog item closed

PR-Ready Acceptance Criteria (per module)

- Authentication and User Access: Users can login; Admin can manage users; RBAC gates core actions; seed admin user exists
- Dashboard: Dashboard shell renders; shows at least a few seeded metrics
- Customer Management: CRUD for customers; profile shows related data
- Customer Measurements: Add/edit; multiple versions; can set current
- Customer Style Preferences: CRUD and linkage to customer profiles
- Lead / Inquiry Management: Capture and convert workflow planned
- Quotation Management: Create and convert to orders/invoices
- Order Management: CRUD; link to customer; statuses tracked
- Production / Work Progress Tracking: Basic progress logging wired to orders
- Fitting and Alteration Management: Basic scheduling and notes linkage
- Invoice Management: Create/issue/cancel; link to orders; items
- Payment Management: Record/multiple payments; voids supported
- Receipt Management: Generated receipts per payments
- Expense Management: Create/void with reason; categories; search/filter
- Delivery Management: Track delivery status and handover
- Notifications and Reminders: In-app reminders scaffold
- Reports and Analytics: Core reports plumbing planned
- Audit Trail and Activity Logs: Logging scaffold and admin access
- Settings and Master Data: Seeded categories; settings scaffolding
- File / Image Attachment Management: Attachments scaffold
- Optional Inventory-lite Module: Planned scaffold
- Optional Vendor / Supplier Management: Planned scaffold
- Optional Staff Performance / Commission Module: Planned scaffold
- End-to-End Workflow: Documented pipeline; ready for implementation
- Build/Testing Plan: CI-ready with tests scaffold

Notes

- This section is intended to stay current as modules are implemented; please update owners and acceptance criteria as soon as assignments are known.

Links to Migrations and Seeders

- Migrations
    - Users: database/migrations/0001_01_01_000000_create_users_table.php
    - Theme preference (users): database/migrations/2026_02_09_000925_add_theme_preference_to_users_table.php
    - Customers: database/migrations/2026_03_25_162106_create_customers_table.php
    - Measurements: database/migrations/2026_03_25_162106_create_measurements_table.php
    - Expenses: database/migrations/2026_03_25_164159_create_expenses_table.php
    - Expense Categories: database/migrations/2026_03_25_164157_create_expense_categories_table.php
    - Orders: database/migrations/2026_03_25_162212_create_orders_table.php
    - Order Items: database/migrations/2026_03_25_162215_create_order_items_table.php
    - Invoices: database/migrations/2026_03_25_162217_create_invoices_table.php
    - Invoice Items: database/migrations/2026_03_25_162221_create_invoice_items_table.php
    - Payments: database/migrations/2026_03_25_162224_create_payments_table.php
    - Job / Queue tables: database/migrations/0001_01_01_000002_create_jobs_table.php
- Seeders
    - ExpenseCategorySeeder.php
    - CustomerSeeder.php
    - MeasurementSeeder.php
    - OrderSeeder.php
    - InvoiceSeeder.php
    - PaymentSeeder.php
    - ExpenseSeeder.php
    - DatabaseSeeder.php
    - (Receipts, audit_logs, etc., can be added later)

Kickoff Governance Plan

- Objective
    - Deliver MVP focusing on core money & record-keeping modules; then expand to tailing workflow and operations over iterative sprints.
- Roles
    - Product Owner, Tech Lead, Backend, Frontend, QA, DevOps
- Decision Process
    - PR review requirements (min 2 approvals), design docs for large features, keep a changelog
- Sprint Cadence
    - 2-week sprints; planning on Monday, demo on Friday
- Definition of Done (DoD)
    - Code merged; tests pass; migrations run in CI; seeds idempotent; docs updated
- Release Plan
    - Branch strategy; environments; feature flags; release notes
- Risk & Mitigations
    - Data migrations risk; seed duplication; have reset strategies for dev
- Communication
    - Weekly status digest; issue tracker; PR templates; decision log in repo

Notes

- This tailored requirements.md will be updated as modules are implemented or changed.
