# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

OPTIRH is an HR management platform built with Laravel 10, PHP 8.1+, and MySQL 8.0+. It provides modular solutions for personnel management, absence tracking, HR documents, and administrative appeals (Recours).

## Development Commands

```bash
# Install & setup
composer install && npm install
cp .env.example .env && php artisan key:generate
php artisan migrate --seed

# Run development
php artisan serve        # Backend at http://localhost:8000
npm run dev              # Vite dev server with HMR

# Testing
php artisan test                          # All tests
php artisan test --filter=FeatureName     # Single test
php artisan test tests/Feature            # Feature tests only

# Code formatting
./vendor/bin/pint                         # Fix code style
./vendor/bin/pint --test                  # Check only

# Clear caches (after config changes)
php artisan optimize:clear
```

### Custom Artisan Commands
```bash
php artisan cleanup:activity-logs              # Clean old activity logs
php artisan appeals:update-day-count           # Update appeal day counts
php artisan appeals:send-daily-reminder        # Send appeal reminders
php artisan duties:update-absence-balance      # Update absence balances
php artisan test:mail-system                   # Test email system
php artisan monitor:failed-mails               # Monitor failed emails
```

### Docker Development
```bash
docker-compose up -d                           # Start containers
docker exec -it optirh-app bash                # Access PHP container
docker exec -it optirh-mysql mysql -u root -p  # Access MySQL
```

## Architecture Overview

### Module Structure
Two main modules organized under `app/Models/`, `app/Http/Controllers/`, and `resources/views/`:
- **OptiHr**: Core HR (employees, departments, jobs, absences, documents, publications)
- **Recours**: Administrative appeals (applicants, appeals, authorities, DAC decisions)

### Key Patterns

**Service Layer**: Business logic in `app/Services/`:
- `AbsencePdfService`, `DocumentPdfService` - PDF generation
- `FileService`, `PublicationFileService` - File handling
- `MailService` - Email operations with retry logic
- `ActivityLogService` - Audit logging

**Jobs & Queues**: Background processing in `app/Jobs/`:
- `SendEmailJob` - Async email sending with retries
- `CleanupActivityLogsJob` - Scheduled log cleanup

**Observer**: `app/Observers/EmployeeObserver.php` handles employee model events.

### Permission System (Spatie)
Roles defined in `database/seeders/RoleSeeder.php`:
- **ADMIN**: Full access
- **GRH**: HR Manager - personnel, absences, documents, publications
- **DSAF**: Finance Director - limited HR access, absence approvals
- **DG**: General Director - approvals, all module access
- **EMPLOYEE**: Self-service only
- **DRAJ**: Appeals management (`appeal-actions` permission)

Permissions follow French naming: `voir-un-{resource}`, `écrire-un-{resource}`, `créer-un-{resource}`, `configurer-un-{resource}`, `access-un-{module}`

### Route Structure (routes/web.php)
All routes under `auth` middleware:
- `/opti-hr/*` - HR module (dashboard, membres, attendances, documents, publications)
- `/recours/*` - Appeals module
- `/opti-hr/api/*` - Internal AJAX endpoints for dynamic data

### Hierarchical Approval Flow
Jobs have `n_plus_one_job_id` for manager chain. Absences and documents follow approval workflow: pending → approved/rejected by hierarchy level.

### Scheduled Tasks (app/Console/Kernel.php)
- **Yearly** (Dec 31): `duties:update-absence-balance`
- **Hourly** (8am-6pm): `appeals:update-day-count`
- **Daily** (12:00): Appeal reminders
- **Weekly** (Sun 01:00): Activity log cleanup (90 days retention)

## Key Files

### Helpers (app/Utils/helpers.php)
- `group_publications_by_date()` - Timeline grouping
- `human_filesize()` - File size formatting
- `formatDate()`, `formatDateRange()` - French date formatting
- `numberToWords()` - French number conversion
- `calculateWorkingDays()` - Working days calculation
- `getFileIcon()`, `getFileIconClass()` - File type icons

### Database Seeders
Run `php artisan migrate:fresh --seed` to create:
- Default roles and permissions
- Admin user (admin@admin.com / admin_password in dev)
- Sample departments (DG, DSAF) and jobs
- Absence types, document types, holidays

## Conventions

- **Code Style**: Laravel Pint (PSR-12)
- **Language**: French for UI, permissions, and comments
- **Models**: `app/Models/OptiHr/` and `app/Models/Recours/`
- **Controllers**: `app/Http/Controllers/OptiHr/` and `app/Http/Controllers/Recours/`
- **Views**: `resources/views/modules/opti-hr/` and `resources/views/modules/recours/`
- **Foreign keys**: `{table}_id` pattern (e.g., `employee_id`, `job_id`)

## Git Workflow

1. Branch from `develop`
2. Format code: `./vendor/bin/pint`
3. Run tests: `php artisan test`
4. Merge to `develop`, then `main` via release branches