# Custom Suits Business Management System

## Full Module-by-Module Product Specification for a Laravel Blade Application

## 1. Introduction

This document elaborates the full application from the first customer interaction to the final delivery of a customized suit, including invoicing, payments, expenses, reporting, user access, and future-ready operational modules. It is written for a Laravel application using Blade, with the expectation that the system will begin as a practical monolith for one business and later grow as the business matures.

The goal is to avoid building a narrow invoicing app that later becomes a dead end. Instead, the system should begin with a strong invoicing and financial base while covering the real operating flow of a custom suits business.

The actual business flow is usually:

Lead or inquiry → customer registration → measurements and style notes → quotation or invoice → deposit payment → tailoring and production tracking → fitting and alterations → final payment → delivery → after-sales history and repeat order support.

The application should therefore be designed as a complete business system, even if development starts with finance-first modules.

---

# 2. Product Vision

To provide a simple but complete Laravel Blade web application that helps a custom suits business manage customers, orders, measurements, invoices, payments, receipts, expenses, deliveries, and reports in one place.

The system should allow the owner to answer key daily business questions such as:

* Who placed an order today?
* Which customer still owes a balance?
* Which suits are still in progress?
* Which orders are ready for fitting or delivery?
* How much money was received today?
* How much was spent on fabric, labor, transport, and overhead?
* Which customers order most frequently?
* What is the profit trend of the business?

---

# 3. Users and Roles

## 3.1 Admin / Owner

The Admin has full system access and controls settings, users, pricing structure, reports, expenses, financial corrections, and audit review.

Main responsibilities:

* manage users and permissions
* view all customers and orders
* create and edit invoices
* record and review payments
* approve voids or cancellations
* manage expense categories
* view reports and business performance
* configure system settings

## 3.2 Sales / Front Desk

This user handles customer intake, order capture, invoice creation, and payment entry if permitted.

Main responsibilities:

* register customer
* search existing customer
* create quotation or invoice
* capture style notes and order details
* record deposit payments
* print invoice and receipt
* update customer contact details

## 3.3 Tailoring / Production Staff

This user focuses on work progress, fittings, and readiness for delivery.

Main responsibilities:

* view assigned orders
* update production stages
* record fitting notes
* mark alterations complete
* update readiness for delivery

## 3.4 Accountant / Bookkeeper

This user focuses on expense and transaction verification.

Main responsibilities:

* review invoices and payments
* manage expenses
* run financial reports
* review daily cash summaries
* reconcile transactions

## 3.5 Delivery / Dispatch User

Optional role for later stages.

Main responsibilities:

* view ready-for-delivery orders
* confirm handover or dispatch
* capture delivered date and receiving person
* add delivery notes

---

# 4. High-Level Application Modules

The complete application should contain these modules:

1. Authentication and User Access
2. Dashboard
3. Customer Management
4. Customer Measurements
5. Customer Style Preferences
6. Lead / Inquiry Management
7. Quotation Management
8. Order Management
9. Production / Work Progress Tracking
10. Fitting and Alteration Management
11. Invoice Management
12. Payment Management
13. Receipt Management
14. Expense Management
15. Delivery Management
16. Notifications and Reminders
17. Reports and Analytics
18. Audit Trail and Activity Logs
19. Settings and Master Data
20. File / Image Attachment Management
21. Optional Inventory-lite Module
22. Optional Supplier / Vendor Management
23. Optional Staff Performance / Commission Tracking

Each module is elaborated below.

---

# 5. Authentication and User Access Module

## Purpose

To secure the application and ensure that only authorized people can use sensitive features.

## Features

* login with email or username and password
* logout
* forgot password and reset flow if needed
* role-based access control
* permission-based access control
* active/inactive users
* optional session timeout for security

## Blade Pages

* login page
* forgot password page
* reset password page
* users list
* create user page
* edit user page
* roles and permissions pages

## Important Business Rules

* only authenticated users can access the dashboard
* only authorized users can view financial reports
* only certain users can void payments, cancel invoices, or modify expenses
* deleted financial history should be avoided; use cancel and void actions instead

## Suggested Data

### users

* id
* name
* email
* phone
* password
* role or linked roles
* is_active
* last_login_at
* created_at
* updated_at

If using spatie/laravel-permission, keep roles and permissions normalized instead of a simple role column.

---

# 6. Dashboard Module

## Purpose

To provide a quick overview of the business state as soon as a user logs in.

## What the Dashboard Should Show

* number of new customers today
* number of quotations created today
* number of invoices created today
* amount collected today
* amount spent today
* outstanding balances
* overdue invoices
* orders in progress
* orders awaiting fitting
* orders ready for delivery
* recent transactions
* recent expenses
* recent customer activity

## Optional Charts

* revenue trend by week or month
* expense trend by week or month
* order volume trend
* payment method breakdown

## Blade Components

* stat cards
* recent activity table
* recent orders table
* alerts area for overdue balances and urgent deliveries

## Notes

The dashboard should be tailored by role. For example, production staff should see order progress and fitting queues, while the accountant should see money-related summaries.

---

# 7. Customer Management Module

## Purpose

To maintain complete customer records and make it easy to find repeat clients.

## Why It Matters

In a custom suits business, repeat customers are highly valuable. Good customer records make it easier to reuse measurements, preferences, order history, and payment history.

## Features

* create customer
* edit customer
* search customer by name, phone, email, or customer code
* customer profile page
* customer history
* outstanding balance view
* notes about customer behavior or preferences

## Customer Profile Should Show

* personal details
* all quotations
* all orders
* all invoices
* all payments
* all receipts
* all measurements
* style preferences
* alteration history
* outstanding balance
* total lifetime spend

## Suggested Data

### customers

* id
* customer_code
* full_name
* phone
* alternative_phone
* email
* address
* gender optional
* date_of_birth optional
* notes
* created_by
* created_at
* updated_at

## Important Rules

* one customer can have many quotations, orders, invoices, and measurements
* duplicate detection should be considered using phone number or close name matching

## Blade Pages

* customers index
* create customer
* edit customer
* show customer profile

---

# 8. Customer Measurements Module

## Purpose

To store body measurements for each customer and support repeat orders without remeasuring every time unless needed.

## Why It Matters

Measurements are central to tailoring. They are one of the most valuable pieces of business data for a custom suit seller.

## Features

* add measurement record
* update measurement record
* keep multiple measurement versions over time
* mark one measurement profile as current
* attach notes per measurement session
* print or review measurement sheet

## Measurement Fields

The exact list can be adjusted, but should commonly include:

* neck
* chest
* waist
* hips
* shoulder
* sleeve_length
* jacket_length
* trouser_waist
* trouser_length
* inseam
* thigh
* knee
* cuff
* height
* weight optional
* posture notes
* fitting notes
* measured_by
* measurement_date

## Important Rules

* a customer can have many measurement records over time
* old measurements should not be overwritten blindly; keep history
* one measurement profile may be marked as current for reuse

## Blade Pages

* add measurement
* edit measurement
* measurement history list under customer profile
* measurement detail page

---

# 9. Customer Style Preferences Module

## Purpose

To store style preferences so that future orders can be served faster and more accurately.

## Examples of Preferences

* preferred fit: slim, classic, regular
* preferred lapel style
* preferred trouser cut
* preferred button style
* preferred suit colors
* preferred shirt cuff style
* preferred lining choices
* preferred monogram options
* disliked materials or colors

## Why It Matters

A repeat customer may want similar suits each time, with small adjustments. This saves time and improves customer experience.

## Suggested Data

### customer_preferences

* id
* customer_id
* preferred_fit
* preferred_colors
* preferred_fabrics
* preferred_lapel_style
* preferred_trouser_style
* notes
* created_at
* updated_at

## Blade Pages

This can initially be part of the customer profile instead of a separate section.

---

# 10. Lead / Inquiry Management Module

## Purpose

To capture people who have shown interest before they become full paying customers.

## Why It Matters

Some businesses get inquiries through WhatsApp, walk-ins, calls, or referrals. This module helps the owner avoid losing potential customers.

## Features

* capture inquiry
* record source of lead
* assign follow-up date
* convert lead to customer
* mark lead as won or lost
* note reason for lost lead

## Suggested Lead Fields

* id
* name
* phone
* email
* inquiry_date
* source
* requested_product
* budget_estimate
* notes
* follow_up_date
* status
* converted_customer_id nullable

## Blade Pages

* lead list
* create lead
* edit lead
* convert to customer

## Suggested Statuses

* new
* contacted
* quoted
* won
* lost

This module is optional in version 1, but useful if the business has many inquiries.

---

# 11. Quotation Management Module

## Purpose

To create a price offer before a customer confirms an order.

## Why It Matters

Many customers ask for price first. A quotation is different from an invoice because it is not yet a payment demand unless approved or converted.

## Features

* create quotation
* quotation line items
* validity date
* notes and assumptions
* print/download quotation
* convert quotation to order and/or invoice

## Quotation Fields

* quotation_number
* customer_id
* quotation_date
* valid_until
* subtotal
* discount
* tax
* total
* status
* notes
* created_by

## Quotation Statuses

* draft
* issued
* accepted
* rejected
* expired
* converted

## Important Rules

* a quotation can be converted to an order, invoice, or both depending on workflow
* accepted quotation should preserve an audit record when converted

## Blade Pages

* quotations index
* create quotation
* show quotation
* edit quotation
* print quotation

---

# 12. Order Management Module

## Purpose

To manage the actual suit request from the time the customer confirms until final completion.

## Why It Matters

This is the heart of the business workflow. The invoice shows the money side, but the order shows the actual tailoring work.

## Core Idea

A customer may place an order for one or more garments. An order may later produce an invoice or be linked to one.

## Features

* create order
* attach customer
* link quotation if it exists
* link invoice if it exists
* store garment details
* promised delivery date
* assign production staff
* add order status
* add notes and attachments

## Order Fields

### orders

* id
* order_number
* customer_id
* quotation_id nullable
* invoice_id nullable
* order_date
* promised_delivery_date
* actual_completion_date nullable
* status
* priority
* notes
* created_by
* assigned_to nullable
* created_at
* updated_at

## Order Statuses

* draft
* confirmed
* awaiting_measurement
* in_cutting
* in_stitching
* in_finishing
* awaiting_fitting
* alteration_required
* ready_for_delivery
* delivered
* cancelled

## Order Line Items / Garments

An order may contain multiple garments:

* suit jacket
* trouser
* waistcoat
* shirt
* coat
* blazer
* alteration-only item

### order_items

* id
* order_id
* garment_type
* description
* quantity
* unit_price optional
* style_notes
* fabric_details
* color
* lining_details
* button_details
* monogram_text nullable
* urgent_flag
* created_at
* updated_at

## Important Rules

* one customer can have many orders
* one order can have many garments
* order lifecycle must be tracked separately from invoice lifecycle
* delivery should only happen when the order is marked ready and payment rules are satisfied according to business policy

## Blade Pages

* orders index
* create order
* edit order
* show order
* update order status
* assign production staff

---

# 13. Production / Work Progress Tracking Module

## Purpose

To monitor where each order is in the tailoring process.

## Why It Matters

Without this, the owner may know a customer has paid but not know whether the suit is being cut, stitched, finished, or delayed.

## Production Stages

These can be customized, but a practical default is:

* order confirmed
* fabric ready
* cutting
* stitching
* pressing / finishing
* fitting ready
* alteration in progress
* final finishing
* ready for delivery

## Features

* update stage per order
* record stage date and notes
* assign staff responsible
* flag delays
* show work queue
* show urgent jobs

## Suggested Data

### order_progress_logs

* id
* order_id
* stage
* notes
* changed_by
* assigned_to nullable
* changed_at

## Blade Pages

* production queue page
* order progress timeline on order detail page
* staff-assigned jobs page

## Benefits

* easier follow-up
* better customer communication
* less confusion among staff

---

# 14. Fitting and Alteration Management Module

## Purpose

To track fittings and any changes required before final delivery.

## Why It Matters

Customized suits often need one or more fitting sessions. Capturing these properly improves quality control and repeat service.

## Features

* schedule fitting date
* record fitting result
* record alteration notes
* track extra alteration charges if needed
* mark fitting complete
* update order status based on fitting outcome

## Fitting Outcomes

* fit approved
* minor alteration required
* major alteration required
* customer did not show
* rescheduled

## Suggested Data

### fittings

* id
* order_id
* fitting_date
* status
* notes
* attended_by
* next_fitting_date nullable
* created_by
* created_at
* updated_at

### alterations

* id
* order_id
* fitting_id nullable
* description
* additional_cost nullable
* status
* assigned_to nullable
* due_date nullable
* completed_at nullable
* created_at
* updated_at

## Blade Pages

* fitting schedule list
* create fitting record
* update fitting outcome
* alteration list
* alteration detail

---

# 15. Invoice Management Module

## Purpose

To formally bill the customer for goods and services.

## Why It Matters

Invoices are the legal and financial basis of the transaction and are the first critical phase requested by the client.

## Features

* create invoice manually
* create invoice from quotation
* create invoice from order
* add line items
* apply discounts
* apply tax if needed
* save draft
* issue invoice
* print/download PDF invoice
* cancel invoice with reason
* duplicate previous invoice if needed

## Suggested Invoice Fields

### invoices

* id
* invoice_number
* customer_id
* order_id nullable
* quotation_id nullable
* invoice_date
* due_date
* status
* subtotal_amount
* discount_amount
* tax_amount
* total_amount
* amount_paid
* balance_due
* notes
* created_by
* issued_at nullable
* cancelled_at nullable
* cancelled_by nullable
* cancellation_reason nullable
* created_at
* updated_at

### invoice_items

* id
* invoice_id
* item_name
* description
* quantity
* unit_price
* line_total
* created_at
* updated_at

## Invoice Statuses

* draft
* issued
* partially_paid
* paid
* overdue
* cancelled

## Important Rules

* an invoice must have at least one item
* invoice totals must be system-calculated
* invoice numbering must be unique
* cancelled invoices remain in history
* invoice should not be physically deleted after issuance

## Blade Pages

* invoices index
* create invoice
* edit draft invoice
* show invoice
* print invoice
* cancel invoice

---

# 16. Payment Management Module

## Purpose

To record money received against invoices.

## Why It Matters

Customers often pay a deposit first and clear the balance later.

## Features

* record payment against invoice
* support multiple payments per invoice
* accept deposit payments
* accept final balance payment
* store payment method
* store reference number
* automatically update invoice totals and status
* allow payment void with approval or permission

## Payment Methods

* cash
* mobile money
* bank transfer
* card
* other

## Suggested Data

### payments

* id
* invoice_id
* payment_date
* amount
* payment_method
* reference_number
* notes
* status
* received_by
* voided_at nullable
* voided_by nullable
* void_reason nullable
* created_at
* updated_at

## Payment Statuses

* valid
* voided

## Important Rules

* one invoice can have many payments
* valid payments reduce invoice balance
* overpayment should be blocked or handled intentionally
* voided payments should not count toward paid amount

## Blade Pages

* payment entry form or modal
* payment history under invoice
* payments index page
* void payment page or action modal

---

# 17. Receipt Management Module

## Purpose

To generate proof of payment whenever money is received.

## Features

* generate receipt automatically on payment creation
* print/download receipt
* reprint receipt
* show receipt history under invoice and customer profile

## Suggested Data

### receipts

* id
* receipt_number
* payment_id
* issued_date
* created_at
* updated_at

## Important Rules

* every valid payment should have one receipt
* receipt numbers must be unique
* voided payment receipts should remain traceable but clearly marked if needed

## Blade Pages

* show receipt
* print receipt
* receipts index

---

# 18. Expense Management Module

## Purpose

To track business spending and determine whether the business is actually profitable.

## Why It Matters

Without expenses, invoicing alone does not show the real business picture.

## Features

* create expense
* categorize expense
* edit expense
* void expense with reason
* filter by date, category, method, and user
* attach proof later if desired

## Common Expense Categories

* fabric and materials
* tailor labor
* transport
* rent
* utilities
* marketing
* packaging
* equipment and repairs
* meals and refreshments
* miscellaneous

## Suggested Data

### expense_categories

* id
* name
* description
* is_active
* created_at
* updated_at

### expenses

* id
* expense_category_id
* expense_date
* amount
* payment_method
* vendor_name nullable
* reference_number nullable
* description
* notes
* status
* created_by
* voided_at nullable
* voided_by nullable
* void_reason nullable
* created_at
* updated_at

## Expense Statuses

* valid
* voided

## Blade Pages

* expenses index
* create expense
* edit expense
* show expense
* void expense action
* expense categories page

---

# 19. Delivery Management Module

## Purpose

To track when completed garments are handed over to the customer or delivered.

## Why It Matters

A job can be finished in production but not yet delivered. This distinction is important for both operations and customer service.

## Features

* mark order ready for delivery
* record actual delivery date
* record delivery method
* record recipient name
* add handover notes
* optionally require balance clearance before delivery based on settings

## Suggested Delivery Methods

* customer pickup
* courier
* staff delivery
* third-party transport

## Suggested Data

### deliveries

* id
* order_id
* delivery_method
* promised_delivery_date
* actual_delivery_date nullable
* received_by_name nullable
* receiver_phone nullable
* delivery_notes nullable
* status
* created_by
* created_at
* updated_at

## Delivery Statuses

* pending
* ready
* dispatched
* delivered
* failed

## Important Rules

* delivered orders should record who received them where possible
* system may optionally block delivery where unpaid balance remains, depending on settings

## Blade Pages

* delivery queue
* mark ready for delivery
* mark delivered
* delivery history under order page

---

# 20. Notifications and Reminders Module

## Purpose

To help the business follow up on deadlines and balances.

## Useful Notifications

* invoice overdue reminder
* promised delivery date approaching
* fitting appointment reminder
* ready-for-collection alert
* outstanding balance follow-up

## Initial Recommendation

For version 1 in Laravel Blade, start with in-app reminders only:

* dashboard alerts
* overdue badge indicators
* task lists

Later, integrate WhatsApp, SMS, or email notifications.

## Example Reminder Targets

* overdue invoices
* orders delayed past promised delivery date
* fittings scheduled for today
* garments ready but not collected

---

# 21. Reports and Analytics Module

## Purpose

To give the owner clear business insight.

## Core Reports

### Sales Report

Should show:

* total invoiced amount
* issued invoices
* paid invoices
* partially paid invoices
* overdue invoices
* cancelled invoices optional

### Payments Report

Should show:

* total received in period
* payment method breakdown
* payments by user
* payments by customer

### Expense Report

Should show:

* total expenses in period
* expenses by category
* expenses by payment method
* expenses by user

### Outstanding Balances Report

Should show:

* all unpaid and partially paid invoices
* customer contact details
* age of debt

### Customer Statement

Should show for one customer:

* invoices
* payments
* receipts
* outstanding balances
* date range summary

### Profit Summary

At the initial level, should show:

* total collected revenue
* total expenses
* estimated net position

### Order Operations Report

Should show:

* orders by status
* orders by promised date
* delayed orders
* fittings pending
* deliveries completed

## Blade Pages

* report filters page
* sales report page
* expenses report page
* balances report page
* customer statement page
* print/export view

## Export Options

* printable HTML using Blade print layouts
* PDF export
* CSV or Excel later

---

# 22. Audit Trail and Activity Logs Module

## Purpose

To preserve accountability for sensitive actions.

## Actions That Should Be Logged

* login
* logout optional
* customer creation and updates
* quotation creation and conversion
* order status changes
* invoice creation and cancellation
* payment creation and voiding
* expense creation and voiding
* delivery confirmation
* settings changes
* user and role changes

## Suggested Data

### audit_logs

* id
* user_id
* action_type
* entity_type
* entity_id
* old_values nullable
* new_values nullable
* reason nullable
* ip_address nullable
* user_agent nullable
* created_at

## Important Rules

* cancellations and voids should always capture a reason
* logs should be viewable by admin only
* logs should not be easy to tamper with

## Blade Pages

* audit logs index
* audit log detail modal or page

---

# 23. Settings and Master Data Module

## Purpose

To manage reusable system configuration values.

## Settings to Support

* business name
* logo
* business phone and email
* business address
* invoice prefix
* receipt prefix
* quotation prefix
* currency
* default tax rate if applicable
* whether delivery requires full payment
* whether quotation is mandatory before order
* whether overdue invoices are highlighted automatically

## Master Data Areas

* expense categories
* garment types
* order priorities
* delivery methods
* payment methods if configurable
* production stages

## Blade Pages

* business settings page
* numbering settings page
* master data lists

---

# 24. File / Image Attachment Management Module

## Purpose

To support storing reference materials for orders and expenses.

## Use Cases

* attach customer style inspiration images
* attach fabric images
* attach order sketches
* attach proof of expense
* attach customer-approved design reference

## Suggested Data

### attachments

* id
* attachable_type
* attachable_id
* file_path
* original_name
* mime_type
* file_size
* uploaded_by
* created_at

This can use a polymorphic relationship in Laravel.

## Blade Usage

* upload area on order page
* attachments section on expense page
* image preview on customer or order detail page

---

# 25. Optional Inventory-lite Module

## Purpose

To keep simple visibility into fabrics and materials without building a heavy inventory system too early.

## When to Add It

Add this after finance and order flow are stable.

## Useful Features

* list fabric stock
* record fabric purchases
* track basic quantity in and out
* low stock alerts
* categorize materials

## Simple Items to Track

* suiting fabric
* lining
* buttons
* zips
* threads
* packaging materials

## Note

This should start simple and only grow into a full inventory module if truly needed.

---

# 26. Optional Supplier / Vendor Management Module

## Purpose

To store the businesses or people from whom the client buys materials or services.

## Features

* vendor records
* contact information
* expense linkage
* purchase notes
* frequent vendor list

## Suggested Data

### vendors

* id
* name
* phone
* email
* address
* notes
* created_at
* updated_at

This is optional but helpful for material sourcing history.

---

# 27. Optional Staff Performance / Commission Module

## Purpose

To reward performance if the business later uses staff incentives.

## Possible Features

* orders handled by staff
* payments recorded by staff
* commissions on sales
* tailor productivity counts

This should not be in the first build unless the client already needs it.

---

# 28. Full End-to-End Business Workflow

## 28.1 Inquiry to Customer

1. A potential customer calls, messages, or walks in.
2. Staff records lead or directly creates a customer.
3. Customer needs are captured.

## 28.2 Customer to Quotation

1. Staff captures style requirements.
2. Measurements may be taken immediately or later.
3. Quotation is prepared with line items.
4. Customer receives quotation.

## 28.3 Quotation to Order

1. Customer accepts quotation.
2. Quotation is converted into an order.
3. Order receives promised delivery date.
4. Measurements and style details are linked.

## 28.4 Order to Invoice

1. Staff creates invoice from order or directly if no quotation flow is used.
2. Deposit amount may be paid immediately.
3. Invoice status updates from issued to partially paid if deposit is recorded.

## 28.5 Production and Fitting

1. Order moves into production stages.
2. Staff update progress logs.
3. Fitting is scheduled.
4. Alteration records are created if needed.

## 28.6 Final Payment and Delivery

1. Customer pays remaining balance.
2. Receipt is generated.
3. Order is marked ready for delivery.
4. Delivery or pickup is confirmed.
5. System records who received the garment and when.

## 28.7 After-Sales History

1. Customer profile retains measurements and preferences.
2. Next order becomes faster to handle.
3. Owner can track repeat business.

---

# 29. Suggested Laravel Blade Page Structure

A practical page structure for a Laravel monolith using Blade could be:

## Auth

* GET /login
* POST /login
* POST /logout

## Dashboard

* GET /dashboard

## Customers

* GET /customers
* GET /customers/create
* POST /customers
* GET /customers/{customer}
* GET /customers/{customer}/edit
* PUT /customers/{customer}

## Measurements

* GET /customers/{customer}/measurements
* GET /customers/{customer}/measurements/create
* POST /customers/{customer}/measurements
* GET /measurements/{measurement}
* GET /measurements/{measurement}/edit
* PUT /measurements/{measurement}

## Quotations

* GET /quotations
* GET /quotations/create
* POST /quotations
* GET /quotations/{quotation}
* GET /quotations/{quotation}/edit
* PUT /quotations/{quotation}
* POST /quotations/{quotation}/convert

## Orders

* GET /orders
* GET /orders/create
* POST /orders
* GET /orders/{order}
* GET /orders/{order}/edit
* PUT /orders/{order}
* POST /orders/{order}/status
* POST /orders/{order}/progress

## Fittings and Alterations

* POST /orders/{order}/fittings
* PUT /fittings/{fitting}
* POST /orders/{order}/alterations
* PUT /alterations/{alteration}

## Invoices

* GET /invoices
* GET /invoices/create
* POST /invoices
* GET /invoices/{invoice}
* GET /invoices/{invoice}/edit
* PUT /invoices/{invoice}
* POST /invoices/{invoice}/issue
* POST /invoices/{invoice}/cancel
* GET /invoices/{invoice}/print

## Payments

* POST /invoices/{invoice}/payments
* GET /payments
* GET /payments/{payment}
* POST /payments/{payment}/void

## Receipts

* GET /receipts
* GET /receipts/{receipt}
* GET /receipts/{receipt}/print

## Expenses

* GET /expenses
* GET /expenses/create
* POST /expenses
* GET /expenses/{expense}
* GET /expenses/{expense}/edit
* PUT /expenses/{expense}
* POST /expenses/{expense}/void

## Deliveries

* GET /deliveries
* POST /orders/{order}/delivery
* PUT /deliveries/{delivery}

## Reports

* GET /reports/sales
* GET /reports/payments
* GET /reports/expenses
* GET /reports/balances
* GET /reports/customer-statement
* GET /reports/orders

## Admin / Settings

* GET /settings/business
* PUT /settings/business
* GET /settings/categories
* GET /users
* GET /roles
* GET /audit-logs

---

# 30. Suggested Build Order for Development

Because this is a Laravel Blade app, the best build order is not necessarily the exact business order. It should follow dependency order.

## Phase 1: Core Foundation

1. authentication
2. roles and permissions
3. dashboard shell
4. business settings

## Phase 2: Finance First

5. customers
6. invoices
7. invoice items
8. payments
9. receipts
10. expense categories
11. expenses
12. basic reports

## Phase 3: Core Tailoring Workflow

13. measurements
14. customer preferences
15. quotations
16. orders
17. order items
18. production progress logs
19. fittings
20. alterations
21. deliveries

## Phase 4: Control and Polish

22. audit logs
23. attachments
24. reminder alerts
25. advanced reports
26. print and PDF refinement

## Phase 5: Optional Business Growth

27. inventory-lite
28. vendors
29. commissions
30. messaging integrations

---

# 31. Recommended Status and Enum Sets

## Invoice Status

* draft
* issued
* partially_paid
* paid
* overdue
* cancelled

## Quotation Status

* draft
* issued
* accepted
* rejected
* expired
* converted

## Order Status

* draft
* confirmed
* awaiting_measurement
* in_cutting
* in_stitching
* in_finishing
* awaiting_fitting
* alteration_required
* ready_for_delivery
* delivered
* cancelled

## Payment Status

* valid
* voided

## Expense Status

* valid
* voided

## Delivery Status

* pending
* ready
* dispatched
* delivered
* failed

## Fitting Status

* scheduled
* completed
* rescheduled
* no_show
* alteration_required
* approved

---

# 32. Important Business Rules Across the Whole System

1. Every invoice belongs to one customer.
2. Every invoice must have at least one item.
3. One invoice may have multiple payments.
4. One payment belongs to one invoice.
5. Every valid payment should generate one receipt.
6. One customer may have many orders, quotations, invoices, and measurements.
7. Order status is operational and separate from invoice status.
8. Cancelled invoices and voided payments or expenses should remain visible historically.
9. Measurements should maintain history rather than overwrite all previous versions.
10. Delivery may optionally require full payment, depending on business configuration.
11. Sensitive actions should capture reason and user.
12. Financial totals should exclude voided and cancelled records by default.

---

# 33. Final Recommendation

For this application, the system should not be treated as only an invoicing app. It should be treated as a full custom suits business management system, but built in stages.

The first release should focus on the modules that immediately solve the owner’s money and record problems:

* authentication and access
* dashboard
* customers
* invoices
* payments
* receipts
* expenses
* reports

The second release should cover the tailoring reality of the business:

* measurements
* customer preferences
* quotations
* orders
* production tracking
* fittings and alterations
* deliveries

That gives the client a system that matches how the business truly operates from start to finish.

---

# 34. Suggested Next Technical Step

After this document, the best next step is to convert it into:

* Laravel modules and folder structure
* database tables and migrations
* route list
* controllers, form requests, services, and Blade pages
* phased implementation plan

That technical blueprint will make development much easier and more organized.

---

# 35. Technical Laravel Blueprint for Major Modules

This section converts the product specification into a practical Laravel 12 + Blade implementation approach using:

* Laravel Daily Laravel + Blade Starter Kit
* Blade views for main screens
* Alpine.js for light interactivity
* Livewire for dynamic sections where it clearly improves UX
* Spatie Laravel Permission for roles and permissions
* Standard Laravel controllers, form requests, services, actions, policies where appropriate

## 35.1 Recommended Application Architecture

This application should remain a monolith, but a well-structured one.

Recommended folder approach:

* `app/Models`
* `app/Enums`
* `app/Http/Controllers`
* `app/Http/Requests`
* `app/Livewire`
* `app/Services`
* `app/Actions`
* `app/Policies`
* `app/Support`
* `app/Observers`
* `app/Notifications`

Recommended principle:

* Controllers coordinate HTTP requests and responses.
* Form Requests validate input.
* Services handle multi-step business workflows.
* Actions handle focused write operations.
* Policies handle record-level authorization.
* Livewire is added only where dynamic, stateful UI is a clear win.
* Blade remains the primary rendering layer.

## 35.2 When to Use Blade Only vs Livewire

Use normal Blade + controller pages for:

* index pages with basic filters
* create/edit forms that submit normally
* show pages
* print pages
* settings pages
* reports with classic filter form submit

Use Livewire for:

* customer search and selection inside invoice/order creation
* adding/removing invoice items dynamically
* adding/removing quotation items dynamically
* measurement capture forms with dynamic sections
* payment modal with immediate balance preview
* order progress timeline updates
* fitting and alteration quick updates
* dashboard widgets that need partial refresh
* reusable tables with search, filters, sorting, pagination

Avoid using Livewire for everything. This starter kit is Blade-first, so Livewire should enhance it, not replace the whole UI style.

## 35.3 Spatie Roles and Permissions Structure

Use Spatie for:

* roles
* permissions
* middleware-based route protection
* blade directives like `@can`, `@role`, `@hasanyrole`

Suggested base roles:

* owner
* admin
* sales
* accountant
* tailor
* dispatch

Suggested permissions grouped by module:

### Customers

* customers.view
* customers.create
* customers.update
* customers.delete

### Measurements

* measurements.view
* measurements.create
* measurements.update
* measurements.delete

### Quotations

* quotations.view
* quotations.create
* quotations.update
* quotations.issue
* quotations.convert
* quotations.cancel

### Orders

* orders.view
* orders.create
* orders.update
* orders.assign
* orders.update_status
* orders.manage_progress
* orders.manage_fittings
* orders.manage_deliveries

### Invoices

* invoices.view
* invoices.create
* invoices.update
* invoices.issue
* invoices.cancel
* invoices.print

### Payments

* payments.view
* payments.create
* payments.void
* receipts.view
* receipts.print

### Expenses

* expenses.view
* expenses.create
* expenses.update
* expenses.void
* expense_categories.manage

### Reports

* reports.view_sales
* reports.view_payments
* reports.view_expenses
* reports.view_balances
* reports.view_profit
* reports.view_customer_statements

### Admin

* users.view
* users.create
* users.update
* users.assign_roles
* settings.manage
* audit_logs.view

Recommended implementation:

* seed roles and permissions
* assign permission sets per role in a dedicated seeder
* protect routes with middleware and also gate actions in views
* still use policies for record-level decisions

## 35.4 Policies Recommendation

Use policies for records where ownership, status, or business state matters.

Recommended policies:

* `CustomerPolicy`
* `QuotationPolicy`
* `OrderPolicy`
* `InvoicePolicy`
* `PaymentPolicy`
* `ExpensePolicy`
* `DeliveryPolicy`

Examples where policy checks matter:

* can a user update an invoice after it is issued?
* can a user cancel an invoice that already has payments?
* can a user void a payment?
* can a user update an order already delivered?
* can a user edit a measurement marked as archived?

So the pattern should be:

* Spatie controls broad access to a module or action.
* Policies control whether this specific record can be acted on.

---

# 36. Module-by-Module Technical Plan

# 36.1 Foundation Module

## Purpose

Set up the application shell before business features begin.

## Deliverables

* starter kit installation
* auth working
* profile pages working
* app layout finalized
* sidebar navigation structure
* Spatie installed and configured
* base roles and permissions seeded
* admin user seeder
* shared flash message components
* shared table, form, modal, and badge components

## Packages

* `spatie/laravel-permission`
* `livewire/livewire`
* optional PDF package later

## Suggested Files

* `database/seeders/RolePermissionSeeder.php`
* `app/Providers/AuthServiceProvider.php`
* `resources/views/layouts/app.blade.php`
* `resources/views/components/*`

## Starter Kit Integration Notes

Use the starter kit’s layout and profile/auth scaffolding as the base. Do not fight its structure early. Build all modules into the existing navigation/sidebar/header pattern so the app stays visually consistent.

---

# 36.2 Customer Module

## Goal

Create the full customer lifecycle foundation because nearly every major module depends on customers.

## Database Tables

### customers

* id
* customer_code
* full_name
* phone
* alternative_phone nullable
* email nullable
* address nullable
* notes nullable
* created_by nullable foreignId users
* created_at
* updated_at

Recommended indexes:

* unique or semi-unique index on `customer_code`
* index on `phone`
* index on `full_name`

## Model

### `app/Models/Customer.php`

Relationships:

* belongsTo createdBy
* hasMany measurements
* hasMany quotations
* hasMany orders
* hasMany invoices
* hasMany customerPreferences

Useful accessors/scopes:

* `scopeSearch($query, $term)`
* `getOutstandingBalanceAttribute()`
* `getLifetimeValueAttribute()`

## Controller

### `CustomerController`

Methods:

* `index()`
* `create()`
* `store(StoreCustomerRequest $request)`
* `show(Customer $customer)`
* `edit(Customer $customer)`
* `update(UpdateCustomerRequest $request, Customer $customer)`

## Form Requests

* `StoreCustomerRequest`
* `UpdateCustomerRequest`

Validation rules:

* full_name required string max
* phone required string max
* email nullable email
* address nullable string
* notes nullable string

## Service / Action

Use an action for customer creation if you want audit hooks cleanly:

* `CreateCustomerAction`
* `UpdateCustomerAction`

## Policy

### `CustomerPolicy`

Methods:

* viewAny
* view
* create
* update

## Blade Pages

* `resources/views/customers/index.blade.php`
* `resources/views/customers/create.blade.php`
* `resources/views/customers/edit.blade.php`
* `resources/views/customers/show.blade.php`

## Livewire Usage

Use Livewire for:

* searchable customer table
* reusable customer search picker component for invoices and orders

Suggested Livewire components:

* `CustomerTable`
* `CustomerSearchSelect`

## Routes

```php
Route::resource('customers', CustomerController::class);
```

## Build Notes

The customer show page should become a major hub later, displaying tabs for measurements, quotations, orders, invoices, payments, and delivery history.

---

# 36.3 Measurements Module

## Goal

Capture and preserve customer body measurements with history.

## Database Tables

### measurements

* id
* customer_id
* profile_name nullable
* measurement_date
* neck nullable decimal
* chest nullable decimal
* waist nullable decimal
* hips nullable decimal
* shoulder nullable decimal
* sleeve_length nullable decimal
* jacket_length nullable decimal
* trouser_waist nullable decimal
* trouser_length nullable decimal
* inseam nullable decimal
* thigh nullable decimal
* knee nullable decimal
* cuff nullable decimal
* height nullable decimal
* weight nullable decimal
* posture_notes nullable text
* fitting_notes nullable text
* is_current boolean default false
* measured_by nullable foreignId users
* created_at
* updated_at

Indexes:

* customer_id
* measurement_date
* is_current

## Model

### `Measurement`

Relationships:

* belongsTo customer
* belongsTo measuredBy

Useful methods:

* `markAsCurrent()`

## Controller

### `CustomerMeasurementController`

Methods:

* `index(Customer $customer)`
* `create(Customer $customer)`
* `store(StoreMeasurementRequest $request, Customer $customer)`
* `show(Measurement $measurement)`
* `edit(Measurement $measurement)`
* `update(UpdateMeasurementRequest $request, Measurement $measurement)`

## Form Requests

* `StoreMeasurementRequest`
* `UpdateMeasurementRequest`

## Service / Action

Use service if marking a new measurement as current should automatically unset the prior current one.

* `MeasurementService::createForCustomer()`
* `MeasurementService::updateCurrentState()`

## Policy

### `MeasurementPolicy`

Control who may update or archive measurement records.

## Blade Pages

* customer measurement history page
* measurement form page
* measurement detail page

## Livewire Usage

Recommended here. Measurement forms often feel better with grouped sections and live previews.

Suggested Livewire components:

* `MeasurementForm`
* `MeasurementHistoryTable`

## Routes

```php
Route::prefix('customers/{customer}')->group(function () {
    Route::get('measurements', [CustomerMeasurementController::class, 'index'])->name('customers.measurements.index');
    Route::get('measurements/create', [CustomerMeasurementController::class, 'create'])->name('customers.measurements.create');
    Route::post('measurements', [CustomerMeasurementController::class, 'store'])->name('customers.measurements.store');
});
Route::get('measurements/{measurement}', [CustomerMeasurementController::class, 'show'])->name('measurements.show');
Route::get('measurements/{measurement}/edit', [CustomerMeasurementController::class, 'edit'])->name('measurements.edit');
Route::put('measurements/{measurement}', [CustomerMeasurementController::class, 'update'])->name('measurements.update');
```

---

# 36.4 Quotation Module

## Goal

Allow staff to prepare price offers before a customer fully commits.

## Database Tables

### quotations

* id
* quotation_number
* customer_id
* quotation_date
* valid_until nullable
* status
* subtotal_amount
* discount_amount default 0
* tax_amount default 0
* total_amount
* notes nullable
* created_by
* issued_at nullable
* accepted_at nullable
* rejected_at nullable
* converted_at nullable
* created_at
* updated_at

### quotation_items

* id
* quotation_id
* item_name
* description nullable
* quantity
* unit_price
* line_total
* created_at
* updated_at

## Models

### `Quotation`

Relationships:

* belongsTo customer
* belongsTo createdBy
* hasMany items
* hasOne order optional later
* hasOne invoice optional later

Useful methods:

* `recalculateTotals()`
* `canBeConverted()`

### `QuotationItem`

* belongsTo quotation

## Controller

### `QuotationController`

Methods:

* index
* create
* store
* show
* edit
* update
* issue
* accept
* reject
* convert

## Form Requests

* `StoreQuotationRequest`
* `UpdateQuotationRequest`
* `ConvertQuotationRequest` optional

## Service Layer

Quotation is a good candidate for service-based workflows.

### `QuotationService`

Responsibilities:

* create quotation with items in transaction
* update quotation with items in transaction
* issue quotation
* convert quotation to order
* optionally convert quotation to invoice

## Actions

* `CreateQuotationAction`
* `UpdateQuotationAction`
* `ConvertQuotationToOrderAction`
* `ConvertQuotationToInvoiceAction`

## Policy

### `QuotationPolicy`

Examples:

* only editable in draft/issued states depending business rule
* cannot be converted twice

## Blade Pages

* quotations index
* create quotation
* edit quotation
* show quotation
* print quotation

## Livewire Usage

Strongly recommended for quotation item entry.

Suggested components:

* `QuotationItemsEditor`
* `QuotationTable`
* `QuotationConvertModal`

## Routes

```php
Route::resource('quotations', QuotationController::class);
Route::post('quotations/{quotation}/issue', [QuotationController::class, 'issue'])->name('quotations.issue');
Route::post('quotations/{quotation}/accept', [QuotationController::class, 'accept'])->name('quotations.accept');
Route::post('quotations/{quotation}/reject', [QuotationController::class, 'reject'])->name('quotations.reject');
Route::post('quotations/{quotation}/convert', [QuotationController::class, 'convert'])->name('quotations.convert');
```

---

# 36.5 Order Module

## Goal

Track actual customer jobs from confirmation to delivery.

## Database Tables

### orders

* id
* order_number
* customer_id
* quotation_id nullable
* invoice_id nullable
* order_date
* promised_delivery_date nullable
* actual_completion_date nullable
* status
* priority nullable
* notes nullable
* created_by
* assigned_to nullable
* created_at
* updated_at

### order_items

* id
* order_id
* garment_type
* description nullable
* quantity default 1
* unit_price nullable
* line_total nullable
* style_notes nullable
* fabric_details nullable
* color nullable
* lining_details nullable
* button_details nullable
* monogram_text nullable
* urgent_flag boolean default false
* created_at
* updated_at

### order_progress_logs

* id
* order_id
* stage
* notes nullable
* changed_by
* assigned_to nullable
* changed_at
* created_at
* updated_at

## Models

### `Order`

Relationships:

* belongsTo customer
* belongsTo quotation nullable
* belongsTo invoice nullable
* belongsTo createdBy
* belongsTo assignedTo nullable
* hasMany items
* hasMany progressLogs
* hasMany fittings
* hasMany alterations
* hasOne delivery

Useful methods:

* `recalculateFinancialSnapshot()` if linked to invoice items later
* `markReadyForDelivery()`
* `isEditable()`

## Controller

### `OrderController`

Methods:

* index
* create
* store
* show
* edit
* update
* updateStatus
* assign

### `OrderProgressController`

Methods:

* store

## Form Requests

* `StoreOrderRequest`
* `UpdateOrderRequest`
* `UpdateOrderStatusRequest`
* `StoreOrderProgressRequest`

## Service Layer

### `OrderService`

Responsibilities:

* create order with items
* update order with items
* transition status safely
* add progress log
* validate delivery readiness

## Actions

* `CreateOrderAction`
* `UpdateOrderAction`
* `UpdateOrderStatusAction`
* `AddOrderProgressLogAction`

## Policy

### `OrderPolicy`

Important checks:

* who can update status
* who can edit delivered/cancelled order
* who can assign order

## Blade Pages

* orders index
* create order
* edit order
* show order
* production queue page

## Livewire Usage

Very useful here.

Suggested components:

* `OrderItemsEditor`
* `OrderTable`
* `OrderStatusTimeline`
* `ProductionQueueTable`

## Routes

```php
Route::resource('orders', OrderController::class);
Route::post('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
Route::post('orders/{order}/assign', [OrderController::class, 'assign'])->name('orders.assign');
Route::post('orders/{order}/progress', [OrderProgressController::class, 'store'])->name('orders.progress.store');
```

---

# 36.6 Fittings and Alterations Module

## Goal

Track fitting sessions and corrections before final delivery.

## Database Tables

### fittings

* id
* order_id
* fitting_date
* status
* notes nullable
* attended_by nullable
* next_fitting_date nullable
* created_by
* created_at
* updated_at

### alterations

* id
* order_id
* fitting_id nullable
* description
* additional_cost nullable
* status
* assigned_to nullable
* due_date nullable
* completed_at nullable
* created_at
* updated_at

## Models

* `Fitting`
* `Alteration`

Relationships:

* fitting belongsTo order
* alteration belongsTo order
* alteration belongsTo fitting optional

## Controllers

* `OrderFittingController`
* `OrderAlterationController`

Methods:

* store
* edit
* update
* complete where relevant

## Form Requests

* `StoreFittingRequest`
* `UpdateFittingRequest`
* `StoreAlterationRequest`
* `UpdateAlterationRequest`

## Service Layer

### `FittingService`

Responsibilities:

* create fitting
* update fitting outcome
* trigger order status changes based on fitting result

### `AlterationService`

Responsibilities:

* create alteration task
* complete alteration
* optionally add additional invoiceable cost

## Policy

* fitting management should be limited to roles handling production

## Blade Pages

Often nested in order show page.

## Livewire Usage

Recommended for quick workflow updates inside the order detail page.

Suggested components:

* `OrderFittingsPanel`
* `OrderAlterationsPanel`

## Routes

```php
Route::post('orders/{order}/fittings', [OrderFittingController::class, 'store'])->name('orders.fittings.store');
Route::put('fittings/{fitting}', [OrderFittingController::class, 'update'])->name('fittings.update');
Route::post('orders/{order}/alterations', [OrderAlterationController::class, 'store'])->name('orders.alterations.store');
Route::put('alterations/{alteration}', [OrderAlterationController::class, 'update'])->name('alterations.update');
```

---

# 36.7 Invoice Module

## Goal

Support full invoice lifecycle including draft, issued, paid, overdue, and cancelled.

## Database Tables

### invoices

* id
* invoice_number
* customer_id
* order_id nullable
* quotation_id nullable
* invoice_date
* due_date nullable
* status
* subtotal_amount
* discount_amount default 0
* tax_amount default 0
* total_amount
* amount_paid default 0
* balance_due default 0
* notes nullable
* created_by
* issued_at nullable
* cancelled_at nullable
* cancelled_by nullable
* cancellation_reason nullable
* created_at
* updated_at

### invoice_items

* id
* invoice_id
* item_name
* description nullable
* quantity
* unit_price
* line_total
* created_at
* updated_at

Indexes:

* unique invoice_number
* customer_id
* order_id
* status
* invoice_date
* due_date

## Models

### `Invoice`

Relationships:

* belongsTo customer
* belongsTo order nullable
* belongsTo quotation nullable
* belongsTo createdBy
* hasMany items
* hasMany payments

Useful methods:

* `recalculateTotals()`
* `recalculatePayments()`
* `refreshFinancialState()`
* `canAcceptPayments()`
* `markCancelled()`

### `InvoiceItem`

* belongsTo invoice

## Controller

### `InvoiceController`

Methods:

* index
* create
* store
* show
* edit
* update
* issue
* cancel
* print

## Form Requests

* `StoreInvoiceRequest`
* `UpdateInvoiceRequest`
* `IssueInvoiceRequest` optional
* `CancelInvoiceRequest`

## Service Layer

This is a key module and should definitely have a service.

### `InvoiceService`

Responsibilities:

* create invoice with items in transaction
* update draft invoice with items in transaction
* issue invoice
* cancel invoice
* recalculate totals and balance
* refresh invoice status from payments and due date

## Actions

* `CreateInvoiceAction`
* `UpdateInvoiceAction`
* `IssueInvoiceAction`
* `CancelInvoiceAction`
* `RecalculateInvoiceTotalsAction`
* `RefreshInvoiceStatusAction`

## Policy

### `InvoicePolicy`

Important checks:

* who can update draft invoice
* who can cancel issued invoice
* whether invoice with valid payments can be cancelled directly or not
* whether paid invoice can be edited

## Blade Pages

* invoices index
* create invoice
* edit invoice
* show invoice
* printable invoice page

## Livewire Usage

One of the strongest Livewire candidates.

Suggested components:

* `InvoiceItemsEditor`
* `InvoiceTable`
* `InvoicePaymentsPanel`
* `InvoiceSummaryCard`

## Routes

```php
Route::resource('invoices', InvoiceController::class);
Route::post('invoices/{invoice}/issue', [InvoiceController::class, 'issue'])->name('invoices.issue');
Route::post('invoices/{invoice}/cancel', [InvoiceController::class, 'cancel'])->name('invoices.cancel');
Route::get('invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');
```

---

# 36.8 Payment and Receipt Module

## Goal

Record deposits and full payments cleanly, update invoice balances, and generate receipts.

## Database Tables

### payments

* id
* invoice_id
* payment_date
* amount
* payment_method
* reference_number nullable
* notes nullable
* status
* received_by
* voided_at nullable
* voided_by nullable
* void_reason nullable
* created_at
* updated_at

### receipts

* id
* receipt_number
* payment_id
* issued_date
* created_at
* updated_at

Indexes:

* invoice_id
* payment_date
* payment_method
* status
* unique receipt_number

## Models

### `Payment`

Relationships:

* belongsTo invoice
* belongsTo receivedBy
* hasOne receipt

### `Receipt`

* belongsTo payment

Useful methods:

* `isVoided()`
* `canBeVoided()`

## Controllers

### `InvoicePaymentController`

Methods:

* store

### `PaymentController`

Methods:

* index
* show
* void

### `ReceiptController`

Methods:

* index
* show
* print

## Form Requests

* `StorePaymentRequest`
* `VoidPaymentRequest`

## Service Layer

### `PaymentService`

Responsibilities:

* record payment in transaction
* generate receipt number
* create receipt
* update invoice paid amount and balance
* refresh invoice status
* void payment safely
* refresh invoice after void

## Actions

* `RecordPaymentAction`
* `VoidPaymentAction`
* `GenerateReceiptAction`

## Policy

### `PaymentPolicy`

Important decisions:

* who may record payments
* who may void payments
* whether old payments beyond some time window are voidable

## Blade Pages

* payments index
* payment detail
* receipt detail
* receipt print page

## Livewire Usage

Recommended for invoice detail page payment area.

Suggested components:

* `RecordPaymentForm`
* `InvoicePaymentsPanel`
* `PaymentsTable`

## Routes

```php
Route::post('invoices/{invoice}/payments', [InvoicePaymentController::class, 'store'])->name('invoices.payments.store');
Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
Route::get('payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
Route::post('payments/{payment}/void', [PaymentController::class, 'void'])->name('payments.void');
Route::get('receipts', [ReceiptController::class, 'index'])->name('receipts.index');
Route::get('receipts/{receipt}', [ReceiptController::class, 'show'])->name('receipts.show');
Route::get('receipts/{receipt}/print', [ReceiptController::class, 'print'])->name('receipts.print');
```

---

# 36.9 Expense Module

## Goal

Track all business spending with categorization and auditability.

## Database Tables

### expense_categories

* id
* name
* description nullable
* is_active boolean default true
* created_at
* updated_at

### expenses

* id
* expense_category_id
* expense_date
* amount
* payment_method
* vendor_name nullable
* reference_number nullable
* description
* notes nullable
* status
* created_by
* voided_at nullable
* voided_by nullable
* void_reason nullable
* created_at
* updated_at

Indexes:

* expense_category_id
* expense_date
* payment_method
* status

## Models

### `ExpenseCategory`

* hasMany expenses

### `Expense`

* belongsTo category
* belongsTo createdBy

Useful methods:

* `canBeVoided()`

## Controllers

### `ExpenseController`

Methods:

* index
* create
* store
* show
* edit
* update
* void

### `ExpenseCategoryController`

Methods:

* index
* store
* update

## Form Requests

* `StoreExpenseRequest`
* `UpdateExpenseRequest`
* `VoidExpenseRequest`
* `StoreExpenseCategoryRequest`

## Service Layer

### `ExpenseService`

Responsibilities:

* create expense
* update expense
* void expense

## Actions

* `CreateExpenseAction`
* `UpdateExpenseAction`
* `VoidExpenseAction`

## Policy

### `ExpensePolicy`

Important checks:

* only some users can void expenses
* only valid expenses can be edited

## Blade Pages

* expenses index
* create expense
* edit expense
* show expense
* expense category management page

## Livewire Usage

Good for searchable expense list and category modal manager.

Suggested components:

* `ExpensesTable`
* `ExpenseCategoryManager`

## Routes

```php
Route::resource('expenses', ExpenseController::class)->except(['destroy']);
Route::post('expenses/{expense}/void', [ExpenseController::class, 'void'])->name('expenses.void');
Route::resource('expense-categories', ExpenseCategoryController::class)->only(['index', 'store', 'update']);
```

---

# 36.10 Delivery Module

## Goal

Track handover of completed garments.

## Database Tables

### deliveries

* id
* order_id
* delivery_method
* promised_delivery_date nullable
* actual_delivery_date nullable
* received_by_name nullable
* receiver_phone nullable
* delivery_notes nullable
* status
* created_by
* created_at
* updated_at

## Model

### `Delivery`

Relationships:

* belongsTo order
* belongsTo createdBy

## Controller

### `OrderDeliveryController`

Methods:

* store
* update
* markReady
* markDelivered

## Form Requests

* `StoreDeliveryRequest`
* `UpdateDeliveryRequest`
* `MarkDeliveredRequest`

## Service Layer

### `DeliveryService`

Responsibilities:

* create/update delivery record
* validate order readiness
* enforce full-payment-before-delivery setting if enabled
* mark order delivered

## Policy

### `DeliveryPolicy`

Check who can mark delivered.

## Blade Pages

Often nested into order detail page plus a delivery queue page.

## Livewire Usage

Useful for queue views and fast delivery updates.

Suggested components:

* `DeliveryQueueTable`
* `OrderDeliveryPanel`

## Routes

```php
Route::get('deliveries', [OrderDeliveryController::class, 'index'])->name('deliveries.index');
Route::post('orders/{order}/delivery', [OrderDeliveryController::class, 'store'])->name('orders.delivery.store');
Route::put('deliveries/{delivery}', [OrderDeliveryController::class, 'update'])->name('deliveries.update');
Route::post('deliveries/{delivery}/mark-delivered', [OrderDeliveryController::class, 'markDelivered'])->name('deliveries.mark-delivered');
```

---

# 36.11 Report Module

## Goal

Provide filtered views of financial and operational performance.

## Recommended Approach

For reports, use normal controllers + Blade pages first. Use Livewire only if you later want richer interactive filtering.

## Report Controllers

* `SalesReportController`
* `PaymentsReportController`
* `ExpensesReportController`
* `BalancesReportController`
* `CustomerStatementController`
* `OrdersReportController`
* `ProfitReportController`

## Service Layer

This module benefits from query services.

Suggested services:

* `SalesReportService`
* `PaymentReportService`
* `ExpenseReportService`
* `BalanceReportService`
* `CustomerStatementService`
* `OrderOperationsReportService`
* `ProfitReportService`

Each service should:

* accept date filters and optional other filters
* return totals plus paginated rows
* centralize report logic so it is not duplicated in controllers

## Blade Pages

* reports/sales/index.blade.php
* reports/payments/index.blade.php
* reports/expenses/index.blade.php
* reports/balances/index.blade.php
* reports/customer-statement/index.blade.php
* reports/orders/index.blade.php
* reports/profit/index.blade.php

## Routes

```php
Route::prefix('reports')->group(function () {
    Route::get('sales', SalesReportController::class)->name('reports.sales');
    Route::get('payments', PaymentsReportController::class)->name('reports.payments');
    Route::get('expenses', ExpensesReportController::class)->name('reports.expenses');
    Route::get('balances', BalancesReportController::class)->name('reports.balances');
    Route::get('customer-statement', CustomerStatementController::class)->name('reports.customer-statement');
    Route::get('orders', OrdersReportController::class)->name('reports.orders');
    Route::get('profit', ProfitReportController::class)->name('reports.profit');
});
```

---

# 36.12 Audit Log Module

## Goal

Capture sensitive business events for accountability.

## Database Table

### audit_logs

* id
* user_id nullable
* action_type
* entity_type
* entity_id nullable
* old_values nullable json
* new_values nullable json
* reason nullable
* ip_address nullable
* user_agent nullable
* created_at

## Model

### `AuditLog`

* belongsTo user

## Implementation Recommendation

You can begin with a simple `AuditLogService` and call it from actions/services when important operations happen.

Examples:

* invoice issued
* invoice cancelled
* payment recorded
* payment voided
* expense created
* expense voided
* order delivered

Later, you can improve with model observers or event listeners.

## Controller

### `AuditLogController`

Methods:

* index
* show

## Blade Pages

* audit logs index
* audit log detail modal/page

## Route

```php
Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
```

---

# 37. Suggested Module Build Order for This Stack

Because you are using a Blade starter kit and adding Livewire selectively, the clean build order should be:

## Phase 0

* install starter kit
* install Livewire
* install Spatie Permission
* seed owner/admin role structure
* build shared layout/navigation/components

## Phase 1

* customers
* invoices
* invoice items editor Livewire component
* payments
* receipts
* expenses
* expense categories
* basic sales/expenses/balance reports

## Phase 2

* measurements
* quotations
* quotation items editor Livewire component
* orders
* order items editor Livewire component
* order progress logs

## Phase 3

* fittings
* alterations
* deliveries
* order operations reports
* dashboard refinement

## Phase 4

* audit logs
* settings/master data
* attachments
* reminder widgets
* PDF refinement and exports

---

# 38. Final Stack Recommendation

For this project, the cleanest approach is:

* use the Laravel Daily Blade starter kit as the visual/admin shell
* keep normal CRUD pages in Blade controllers
* introduce Livewire only in the places where dynamic interaction clearly improves usability
* use Spatie for broad role/permission control
* use policies for record-level authorization and business state checks
* keep financial and workflow logic in actions, not scattered across controllers or Livewire classes

That will keep the application understandable, scalable, and still friendly to a Blade-first workflow.

---

# 39. Suggested Immediate Next Technical Deliverables

The best next documents to generate from here are:

* migration list in implementation order
* exact route file structure
* controller list with method signatures
* request classes list with validation rules
* service and action class skeleton plan
* sidebar navigation and menu permissions map
* Blade page inventory per module
* Livewire component inventory per module
