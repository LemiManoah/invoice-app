# Alloto Exclusives

Alloto Exclusives is a comprehensive **Tailoring and Fashion Business Management System** built with Laravel 12, Blade, and Tailwind CSS. It is designed to streamline the operations of a modern fashion house or tailoring business.

## Features

### 🧵 Measurement Management
- Specialized tracking for various garment pieces:
    - **Jacket:** Shoulder, chest, stomach, sleeve, biceps, wrist, etc.
    - **Trouser:** Waist, length, inseam, thigh, knee, cuff, fly fit, etc.
    - **Waistcoat:** Chest, waist, length.
    - **Skirt:** Waist, hip line, full length.
    - **Shirt:** Chest, waist, shoulder, full length, bottom cut.
- Historical measurement tracking with versioning (current vs. archive).
- Posture and fitting notes for personalized tailoring.

### 👥 Customer Management
- Centralized customer database.
- Detailed customer profiles with measurement history and order tracking.

### 📦 Order & Product Management
- Manage custom orders and standard products.
- Track order items and fulfillment status.
- Product categorization for easy management.

### 💰 Billing & Finance
- **Invoices & Quotations:** Professional document generation for clients.
- **Payments & Receipts:** Track payments against invoices with receipt generation.
- **Expense Tracking:** Monitor business overheads and material costs.
- **Currency Management:** Support for multiple currencies and business profile settings.

### 🔐 Security & Administration
- **Role-Based Access Control:** Managed via Spatie Permission.
- **Audit Logging:** Track all critical system actions for accountability.
- **Appearance Preferences:** Customizable theme settings for users.

## Technical Stack

- **Framework:** Laravel 12
- **Frontend:** Blade, Alpine.js, Tailwind CSS v4
- **PHP Version:** ^8.2 (Compatible with PHP 8.5.x environment)
- **Database:** Optimized Eloquent relationships and schema.
- **Testing:** Pest PHP for unit and feature testing.

## Getting Started

1. Clone the repository.
2. Install dependencies: `composer install && npm install`.
3. Copy `.env.example` to `.env` and configure your database.
4. Run migrations and seeders: `php artisan migrate --seed`.
5. Build assets: `npm run build`.
6. Start the server: `php artisan serve`.

## License

This project is licensed under the MIT license.
