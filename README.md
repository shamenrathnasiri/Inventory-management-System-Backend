# Inventory Management System — Backend

A robust RESTful API backend for a full-featured Inventory Management System, built with **Laravel 12**. It handles inventory workflows including GRNs, invoices, stock transfers, sales/purchase orders, returns, stock verification, customer/supplier management, and comprehensive reporting with PDF and Excel export.

---

## Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 12 |
| Authentication | Laravel Sanctum + Jetstream |
| Database | MySQL 8+ |
| Realtime UI | Livewire 3 |
| Excel Export | Maatwebsite Excel 3.1 |
| PHP | >= 8.2 |

---

## Features

- **Authentication** — Token-based login/register/logout via Laravel Sanctum
- **User & Permission Management** — Per-user granular permission control
- **Customer Management** — Customers with types and categories
- **Supplier Management** — Full supplier CRUD
- **Product Management** — Products with types, soft-delete, and inventory details
- **Centers** — Multi-center/warehouse support
- **Discount Levels** — Configurable discount tiers
- **Inventory Transactions**
  - Goods Receipt Notes (GRN)
  - Invoices
  - Stock Transfer
  - Sales Orders & Sales Returns
  - Purchase Orders & Purchase Returns
  - Stock Verification
- **Inventory Stock Tracking** — Real-time stock levels across centers
- **Reports** — Dedicated reports for every transaction type plus customer, supplier, product, and center reports
- **Export** — PDF and Excel export for all report types

---

## Requirements

- PHP >= 8.2
- Composer
- Node.js >= 18
- MySQL >= 8.0

---

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/shamenrathnasiri/Inventory-management-System-Backend
cd inventory-management-system-backend
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Install Node.js dependencies

```bash
npm install
```

### 4. Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` and set your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_db
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Run migrations and seeders

```bash
php artisan migrate
php artisan db:seed
```

### 6. Link storage (if needed)

```bash
php artisan storage:link
```

### 7. Build frontend assets

```bash
npm run build
```

### 8. Start the development server

```bash
php artisan serve
```

The API will be available at `http://127.0.0.1:8000`.

> To run background jobs (e.g. exports):
> ```bash
> php artisan queue:work
> ```

---

## API Overview

All protected routes require a `Bearer` token obtained from `/api/login`.

### Authentication

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/login` | Login and receive access token |
| POST | `/api/register` | Register a new user |
| GET | `/api/logout` | Logout (revoke token) |

### Users & Permissions

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET/POST/PUT/DELETE | `/api/users` | User CRUD |
| GET | `/api/permissions` | List all permissions |
| GET | `/api/users/permissions` | All users with permissions |
| GET | `/api/users/me/permissions` | Current user's permissions |
| GET | `/api/users/{id}/permissions` | Specific user's permissions |
| PUT | `/api/users/{id}/permissions` | Update user permissions |
| POST | `/api/permissions/check` | Check a specific permission |

### Customers

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET/POST/PUT/DELETE | `/api/customers` | Customer CRUD |
| GET | `/api/customer/email/{email}` | Find by email |
| GET | `/api/customer/type/{typeId}` | Find by type |
| GET | `/api/customer/name/{name}` | Find by name |
| GET/POST | `/api/customer-types` | Customer types |
| GET/POST | `/api/customer-categories` | Customer categories |

### Suppliers

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET/POST/PUT/DELETE | `/api/suppliers` | Supplier CRUD |

### Products & Types

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET/POST/PUT/DELETE | `/api/products` | Product CRUD |
| GET | `/api/products/inventory/details` | Inventory details per product |
| GET/POST/PUT/DELETE | `/api/product-types` | Product type CRUD |
| GET | `/api/product-types/trashed/list` | Trashed product types |
| GET | `/api/product-types/stats/overview` | Product type statistics |
| POST | `/api/product-types/{id}/restore` | Restore soft-deleted type |
| POST | `/api/product-types/{id}/status` | Toggle type status |
| DELETE | `/api/product-types/{id}/force` | Permanently delete type |

### Discount Levels

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/discount-levels` | List all levels |
| GET | `/api/discount-levels/{id}` | Get single level |
| POST | `/api/discount-levels` | Create level |
| PUT | `/api/discount-levels/{id}` | Update level |
| DELETE | `/api/discount-levels/{id}` | Delete level |

### Centers

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET/POST/PUT/DELETE | `/api/centers` | Center CRUD |

### Inventory Transactions

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/grn/next` | Next GRN number |
| POST | `/api/grn` | Create GRN |
| GET | `/api/grn` | List GRNs |
| GET | `/api/invoices/next` | Next invoice number |
| POST | `/api/invoices` | Create invoice |
| GET | `/api/invoices` | List invoices |
| GET | `/api/stock-transfer/next` | Next stock transfer number |
| POST | `/api/stock-transfer` | Create stock transfer |
| GET | `/api/salesOrder/next` | Next sales order number |
| POST | `/api/salesOrder` | Create sales order |
| GET | `/api/salesOrder` | List sales orders |
| GET | `/api/salesreturn/next` | Next sales return number |
| POST | `/api/salesreturn` | Create sales return |
| GET | `/api/salesreturn` | List sales returns |
| GET | `/api/purchaseOrder/next` | Next purchase order number |
| POST | `/api/purchaseOrder` | Create purchase order |
| GET | `/api/purchaseOrder` | List purchase orders |
| GET | `/api/purchaseReturn/next` | Next purchase return number |
| POST | `/api/purchaseReturn` | Create purchase return |
| GET | `/api/stockVerification/next` | Next stock verification number |
| POST | `/api/stockVerification` | Create stock verification |

### Inventory Stock

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/inventory-stocks/all` | All stock levels |
| GET/POST/PUT/DELETE | `/api/inventory-products` | Inventory product CRUD |

### Reports

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/reports/grn` | GRN report |
| GET | `/api/reports/invoice` | Invoice report |
| GET | `/api/reports/sales-order` | Sales order report |
| GET | `/api/reports/sales-return` | Sales return report |
| GET | `/api/reports/purchase-order` | Purchase order report |
| GET | `/api/reports/purchase-return` | Purchase return report |
| GET | `/api/reports/stock-transfer` | Stock transfer report |
| GET | `/api/reports/stock-verification` | Stock verification report |
| GET | `/api/reports/customer` | Customer report |
| GET | `/api/reports/supplier` | Supplier report |
| GET | `/api/reports/product` | Product report |
| GET | `/api/reports/center` | Center report |
| GET | `/api/reports/{type}/export/pdf` | Export report as PDF |
| GET | `/api/reports/{type}/export/excel` | Export report as Excel |

---

## Project Structure

```
app/
├── Http/Controllers/     # API controllers
├── Models/               # Eloquent models
├── Services/             # Business logic (e.g. InventoryStockService)
├── Actions/              # Fortify/Jetstream actions
database/
├── migrations/           # Database schema
├── seeders/              # Seed data
routes/
└── api.php               # All API route definitions
```

---

## Running Tests

```bash
php artisan test
```

---

## License

This project is open-sourced under the [MIT license](https://opensource.org/licenses/MIT).
