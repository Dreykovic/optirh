# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

OPTIRH is a comprehensive human resources management platform built with Laravel 10, offering modular solutions for personnel management, absence tracking, HR documents, and administrative appeals.

## Development Commands

### Core Development
```bash
# Install dependencies
composer install
npm install

# Run development server
php artisan serve
npm run dev

# Build assets for production
npm run build

# Run database migrations and seeders
php artisan migrate
php artisan migrate:fresh --seed  # Reset and seed database
php artisan db:seed              # Run seeders only

# Generate application key
php artisan key:generate
```

### Code Quality & Testing
```bash
# Run tests
php artisan test
php artisan test --filter=FeatureName  # Run specific test
php artisan test tests/Feature        # Run feature tests only
php artisan test tests/Unit           # Run unit tests only

# Code formatting (Laravel Pint)
./vendor/bin/pint
./vendor/bin/pint --test  # Check without fixing

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
```

### Module Management
```bash
# Create new module
php artisan module:make ModuleName

# Module commands
php artisan module:list
php artisan module:enable ModuleName
php artisan module:disable ModuleName
```

### Custom Artisan Commands
```bash
# Activity log cleanup
php artisan cleanup:activity-logs

# Appeal management
php artisan appeals:update-day-count
php artisan appeals:send-daily-reminder

# Absence balance update
php artisan duties:update-absence-balance
```

### Docker Development
```bash
# Start Docker containers
docker-compose up -d

# Stop containers
docker-compose down

# Access PHP container
docker exec -it optirh-app bash

# Access MySQL
docker exec -it optirh-mysql mysql -u root -p
```

## Architecture Overview

### Modular Structure
The application uses **Laravel Modules** (nwidart/laravel-modules) for separation of concerns:
- **OptiHr Module**: Core HR functionality (employees, absences, documents, publications)
- **Recours Module**: Administrative appeals management system

### Key Architectural Patterns

1. **MVC with Service Layer**
   - Controllers handle HTTP requests and responses (`app/Http/Controllers/`)
   - Models represent database entities with Eloquent ORM (`app/Models/`)
   - Services contain business logic and complex operations (`app/Services/`)
   - Observers handle model events (`app/Observers/`)

2. **Permission System**
   - Uses Spatie Laravel Permission package
   - Role-based access control with granular permissions
   - Middleware-based route protection
   - Predefined roles: Admin, HR Manager, Finance Director, General Director

3. **Multi-tenancy Approach**
   - Employee-based data isolation
   - Department-level hierarchical structure (DG, DSAF, etc.)
   - Company-wide reporting capabilities

### Database Architecture
- **OptiHr Tables**: employees, departments, jobs, absences, absence_types, document_requests, document_types, publications, holidays, annual_decisions, files, duties
- **Recours Tables**: applicants, appeals, authorities, personnals, dacs, decisions, comments
- **System Tables**: users, roles, permissions, activity_log, model_has_roles, model_has_permissions

### Key Integrations
- **PDF Generation**: DomPDF for documents, FPDI for PDF manipulation, FPDF for custom PDFs
- **File Management**: Local storage with file table tracking
- **Email Notifications**: Laravel Mail with queued jobs (Mailpit for local development)
- **Charts & Analytics**: Larapex Charts and ConsoleTV Charts for dashboards
- **Authentication**: Laravel Sanctum for API authentication

### API Endpoints
Web-based API endpoints (in `routes/web.php`):
- `/opti-hr/api/files/{employeeId}` - Employee file management
- `/opti-hr/api/jobs/{departmentId}` - Department job listings
- `/opti-hr/api/membres/job/{id}` - Employee-job relationships
- `/recours/api/data` - Appeals data loading

### Scheduled Tasks
Configured in `app/Console/Kernel.php`:
- **Yearly**: Update absence balances (Dec 31, 00:00)
- **Hourly**: Update appeal day counts (8am-6pm)
- **Daily**: Send appeal reminders (12:00)
- **Weekly**: Cleanup activity logs (Sundays, 01:00)

### Frontend Architecture
- **Blade Templates** with component-based structure
- **Bootstrap 5** for responsive UI
- **Vite** for asset bundling and hot reload
- **JavaScript ES6+** for interactive features
- **AJAX** for dynamic interactions without page reload

### Security Considerations
- CSRF protection on all forms
- XSS prevention through Blade escaping
- SQL injection prevention via Eloquent ORM
- File upload validation and sanitization
- Permission checks at controller and view levels
- Secure password hashing with bcrypt

## Important Conventions

### Code Style
- **PHP**: Laravel Pint for formatting (PSR-12 standard)
- **Naming**: Controllers use PascalCase, methods use camelCase
- **Database**: Tables use snake_case, foreign keys follow `table_id` pattern
- **Routes**: RESTful resource routes with middleware grouping
- **Indentation**: 4 spaces for PHP, 2 spaces for YAML

### Directory Structure
- **Models**: `app/Models/ModuleName/`
- **Controllers**: `app/Http/Controllers/ModuleName/`
- **Views**: `resources/views/module-name/`
- **Migrations**: `database/migrations/`
- **Seeders**: `database/seeders/`
- **Custom Commands**: `app/Console/Commands/`
- **Mail Classes**: `app/Mail/`
- **Jobs**: `app/Jobs/`

### Environment Configuration
- **Database**: PostgreSQL (production), MySQL (Docker development)
- **Queue Driver**: sync (default), supports database/redis
- **Cache Driver**: file (default)
- **Session Driver**: file with 120-minute lifetime
- **Mail**: SMTP configuration required

### Testing Environment
- **Test Database**: SQLite in-memory (configurable)
- **Test Directories**: `tests/Feature/` and `tests/Unit/`
- **Test Command**: `php artisan test`

### Localization
- **Primary Language**: French (fr)
- **Translation Files**: `lang/fr/`
- **Locale Configuration**: `config/app.php`

## Key Services and Helpers

### Custom Helper Functions
Located in `app/Utils/helpers.php`:
- Utility functions for common operations
- Date/time formatting helpers
- Permission checking helpers

### Service Classes
Located in `app/Services/`:
- Business logic separation from controllers
- Complex operations and calculations
- External API integrations

### Observer Classes
Located in `app/Observers/`:
- Model event handlers
- Automatic logging and auditing
- Cascade operations

## Development Workflow

1. **Branch from develop**: All features branch from develop
2. **Run migrations**: `php artisan migrate`
3. **Seed test data**: `php artisan db:seed`
4. **Start dev server**: `php artisan serve` and `npm run dev`
5. **Format code**: `./vendor/bin/pint` before committing
6. **Run tests**: `php artisan test` before merging
7. **Clear caches**: `php artisan optimize:clear` after config changes