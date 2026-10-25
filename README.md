# BAUST Nexus Laravel Backend

BAUST Nexus is a smart campus portal built for students, teachers, administrators, and campus service staff. This repository contains the Laravel backend that powers the BAUST Nexus ecosystem, including authentication, authorization, campus modules, notices, resources, library, cafeteria, transport, and admin management.

This backend is designed to work with the Flutter frontend in the sibling project at `../baust_nexus`.

## Features

- Role-based access for students, teachers, admins, cafe staff, and library staff
- Guest/public landing and notice access
- Secure login and registration workflows
- Student and teacher dashboard with academic access
- Academic resources and file download management
- Query board with replies and moderation
- Library management and borrowing flow
- Cafeteria ordering and admin status control
- Transport and campus information pages
- Admin approval and user management
- CGPA tracker and student academic tools

## Tech Stack

- Laravel 12
- PHP 8.2+
- SQLite (default local setup)
- Vite for frontend asset compilation
- MySQL/PostgreSQL-ready configuration for production use

## Project Structure

- `app/` — controllers, models, middleware, and application logic
- `config/` — application configuration
- `database/` — migrations, seeders, factories, and SQLite database
- `public/` — public web entry point
- `resources/` — Blade views and frontend assets
- `routes/` — route definitions
- `tests/` — application tests
- `socket-server/` — real-time notification gateway

## Getting Started

### 1. Install PHP dependencies

```bash
composer install
```

### 2. Configure environment

Copy the example environment file and update the values for your local environment:

```bash
cp .env.example .env
```

Make sure to define the administrator credentials in `.env` before seeding or running the app. The default file includes:

- `BAUST_MAIN_ADMIN_EMAIL`
- `BAUST_MAIN_ADMIN_PASSWORD`
- `BAUST_CAFE_ADMIN_EMAIL`
- `BAUST_CAFE_ADMIN_PASSWORD`
- `BAUST_LIBRARY_ADMIN_EMAIL`
- `BAUST_LIBRARY_ADMIN_PASSWORD`

Use strong passwords with at least 12 characters.

### 3. Generate the app key and initialize the database

```bash
php artisan key:generate
php artisan migrate --seed
```

### 4. Install frontend dependencies

```bash
npm install
npm run build
```

### 5. Run the app

For local development:

```bash
php artisan serve
```

Or run the full Laravel + Vite development stack:

```bash
composer run dev
```

## Portal Access

- Public home page: `/`
- Guest mode: `/guest`
- Login: `/login`
- Admin login: `/admin/login`
- Cafe admin login: `/cafe/admin/login`
- Library admin login: `/library/admin/login`

## Default Roles

- Student
- Teacher
- Admin
- Cafe Admin
- Library Admin

## Notes

- Student and teacher registrations require admin approval.
- Students can download academic resources.
- Only teachers and the main admin can upload academic resources.
- Uploaded files are stored privately and served through protected authenticated access.

## License

This project is open-source software licensed under the MIT license.
