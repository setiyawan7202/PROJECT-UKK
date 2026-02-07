---
description: Repository Information Overview
alwaysApply: true
---

# PROJECT-UKK Information

## Summary
**PROJECT-UKK** is a comprehensive asset management system (Sarana Prasarana) designed for vocational schools. It facilitates inventory tracking, loan management with pre-borrowing and post-return inspections, maintenance scheduling with automated reminders, and reporting. The system supports multiple user roles including **Admin**, **Staff (Petugas)**, and **Users (Siswa/Guru)**.

## Structure
- **[./app](./app)**: Contains core business logic, including [Models](./app/Models), [Controllers](./app/Http/Controllers), and [Providers](./app/Providers).
- **[./routes](./routes)**: Defines application routing, separated into [admin.php](./routes/admin.php), [staff.php](./routes/staff.php), [auth.php](./routes/auth.php), and [main.php](./routes/main.php).
- **[./resources](./resources)**: Frontend assets including [Blade views](./resources/views), [Tailwind CSS](./resources/css), and [JavaScript](./resources/js).
- **[./database](./database)**: Database schema definitions ([migrations](./database/migrations)), [factories](./database/factories), and [seeders](./database/seeders).
- **[./config](./config)**: Application configuration files.
- **[./public](./public)**: Web server entry point and public assets.

## Language & Runtime
**Language**: PHP  
**Version**: ^8.2  
**Build System**: Vite, Composer  
**Package Manager**: Composer, NPM

## Dependencies
**Main Dependencies**:
- **Laravel Framework**: ^12.0 (Core framework)
- **barryvdh/laravel-dompdf**: ^3.1 (PDF generation)
- **maatwebsite/excel**: ^3.1 (Excel export/import)
- **simplesoftwareio/simple-qrcode**: ^4.2 (QR code generation)
- **tailwindcss**: ^4.1 (Frontend styling)
- **axios**: ^1.11 (HTTP client for frontend)

**Development Dependencies**:
- **phpunit/phpunit**: ^11.5 (Testing)
- **laravel/sail**: ^1.41 (Docker development environment - optional)
- **fakerphp/faker**: ^1.23 (Test data generation)
- **laravel/pint**: ^1.24 (PHP code styling)

## Build & Installation
```bash
# Install PHP dependencies
composer install

# Install JS dependencies
npm install

# Setup environment
copy .env.example .env
php artisan key:generate

# Run migrations and seeders
php artisan migrate --seed

# Build frontend assets
npm run build

# Start local development server
php artisan serve
npm run dev
```

## Main Files & Resources
- **Entry Point**: [./public/index.php](./public/index.php)
- **Artisan Binary**: [./artisan](./artisan)
- **Configuration**: [./config/app.php](./config/app.php)
- **Vite Config**: [./vite.config.js](./vite.config.js)
- **Primary Models**:
  - [./app/Models/Barang.php](./app/Models/Barang.php) (Asset inventory)
  - [./app/Models/Peminjaman.php](./app/Models/Peminjaman.php) (Loan tracking)
  - [./app/Models/Inspection.php](./app/Models/Inspection.php) (Checklist results)
  - [./app/Models/MaintenanceSchedule.php](./app/Models/MaintenanceSchedule.php) (Preventive maintenance)

## Testing
**Framework**: PHPUnit  
**Test Location**: [./tests](./tests)  
**Naming Convention**: `*Test.php`  
**Configuration**: [./phpunit.xml](./phpunit.xml)

**Run Command**:
```bash
php artisan test
```
