# Copilot Instructions for door-contracts

## Overview
This is a Laravel-based contract management system for door sales, supporting multi-branch workflows, manager roles, and contract lifecycle management. The project uses SQLite for local development and stores contract data both in the database and as JSON files.

## Key Architecture & Data Flow
- **Models:** `Contract`, `Branch`, `User` in `app/Models/`.
- **Controllers:** `ContractController` and `AuthController` in `app/Http/Controllers/` handle CRUD, authentication, and business logic.
- **Views:** Blade templates in `resources/views/contracts/` for all contract UI (create, edit, show, index).
- **Routes:** Defined in `routes/web.php` using Laravel resource controllers and middleware for auth.
- **Storage:** Uploaded files (photos, attachments) are stored in `storage/app/public` and accessed via symbolic link from `public/storage`.
- **Migrations/Seeders:** All schema and seed logic in `database/migrations/` and `database/seeders/`.

## Developer Workflows
- **Install dependencies:** `composer install`
- **Setup environment:** Copy `.env.example` to `.env`, set `DB_DATABASE` to an absolute path for SQLite.
- **Database setup:** `php artisan migrate:fresh --seed`
- **Storage link:** `php artisan storage:link`
- **Run server:** `php artisan serve`
- **Run tests:** `php artisan test`

## Project-Specific Conventions
- **Contract numbers** are unique per branch and auto-generated.
- **Manager/branch assignment** is automatic on contract creation.
- **All contract fields** (including custom ones like `outer_cover`, `glass_unit`, etc.) must be present in both create/edit forms and show views for consistency.
- **File uploads** (photo, attachment) are optional but handled via standard Laravel file upload patterns.
- **Validation** is enforced in controllers, not in Blade templates.
- **JSON export**: Each contract is also saved as a JSON file for backup/audit.

## Integration & Patterns
- **Authentication:** Laravel session-based, with CSRF protection and hashed passwords.
- **Authorization:** Managers see only their contracts; admins see all.
- **Search:** Implemented in `ContractController` for contract number, client name, and phone.
- **Printing:** Print-friendly Blade markup is included in `show.blade.php`.
- **Dynamic form fields:** JavaScript in `create.blade.php` auto-fills and updates fields based on category/model selection.

## Examples
- See `resources/views/contracts/create.blade.php` for full contract form field list and dynamic logic.
- See `resources/views/contracts/show.blade.php` for print markup and contract details display.
- See `app/Http/Controllers/ContractController.php` for business logic and validation.

## Tips
- Always keep contract fields in sync across create, edit, and show views.
- Use `php artisan migrate:fresh --seed` to reset and reseed the database during development.
- For file uploads, ensure `storage:link` is created and permissions are correct.

---
If any conventions or workflows are unclear, please ask for clarification or examples from the codebase.
