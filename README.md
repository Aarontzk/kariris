# KARIRIS — Know Your Career

KARIRIS is a career exploration app built with Laravel 12 (PHP 8.2), Blade, and Supabase Postgres. It implements use cases UC-01 to UC-23 from the KARIRIS CDR. The UI follows the Hi-Fi Figma frames: a 440px mobile frame with the PP Telegraf and Klein fonts.

## Features

| Area | Use cases | Route |
|---|---|---|
| Sign up ("Sign in"), log in, log out | UC-01, UC-02 | `/register`, `/login` |
| Profile, skills (CRUD), experience (CRUD) | UC-03, UC-06..11 | `/profile` |
| Change username (once per 30 days) and password | UC-04, UC-05 | `/settings` |
| Career Match: top 3 match percentages | UC-12 | popup on `/profile`, JSON `/career-match` |
| Profession search and category filter | UC-13, UC-14 | `/search?q=&kategori=` |
| Career profile and Career Review | UC-15, UC-16 | `/profesi/{slug}` |
| Bookmark: add, view, remove | UC-18..20 | `/bookmarks` |
| Day in the Life: routines and project phases, with detail | UC-21..23 | `/profesi/{slug}/day-in-the-life` |

The controllers follow the CDR class diagram. `AkunController` handles accounts and profiles. `ProfessionController` handles professions, bookmarks, Career Match, and Day in the Life. The career-match calculation lives in `app/Services/CareerMatchService.php`. The score is the user's skills, weighted by level (Advanced 1.0, Intermediate 0.88, Basic 0.65), matched against the profession's `kriteria_skill`, which makes up 85%. The other 15% is a bonus when the user's experience fits the profession. Professions with a 0% match are hidden.

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
```

### Database: Supabase Postgres

The app uses the team's existing tables in Supabase. The migrations are **idempotent**: tables that already exist are skipped. Only Laravel's system tables (`sessions`, `cache`, `jobs`, ...) and nullable UI columns are added, such as `profesi.slug`/`gambar` and `fase_kerja.durasi`/`sprint`/`ikon`.

1. In Supabase, click **Connect** and copy the **Session pooler** URI.
2. Paste it into `.env`:
   ```
   DB_CONNECTION=pgsql
   DB_URL="postgresql://postgres.<project-ref>:<password>@aws-0-<region>.pooler.supabase.com:5432/postgres"
   DB_SSLMODE=require
   SESSION_DRIVER=file
   CACHE_STORE=file
   ```
3. Enable `pdo_pgsql` and `pgsql` in `php.ini`.
4. Run the migrations and seed:
   ```bash
   php artisan migrate
   php artisan db:seed
   ```
   The seeder reads `database/seeders/data/kariris.json` (a snapshot of the team's data) and matches rows by natural key. Existing rows are left alone; it only fills UI columns that are still empty. It is safe to run more than once.

> **Do not** run `migrate:fresh` or `migrate:rollback` against Supabase. They drop the team's tables.

To work offline, use `DB_CONNECTION=sqlite`, then run `php artisan migrate --seed`.

### Run

```bash
php artisan serve
```

Then open http://localhost:8000.

Demo account (created by the seeder):

| Field | Value |
|---|---|
| Username | `christinerotua` |
| Email | `5026241000@its.ac.id` |
| Password | `kariris123` (all seeded accounts) |

## Tests

```bash
php artisan test
```

There are 25 feature tests. They always run on in-memory SQLite (`phpunit.xml` + a guard in `tests/TestCase.php`), so they never touch Supabase. They cover auth, skill and experience CRUD, ownership checks, the username/password rules, search and filter, bookmarks, Career Match, and Day in the Life.

## Asset notes

- The fonts in `public/fonts` (PP Telegraf, Klein) are the designer's commercial/trial fonts. They are only for local development and the class demo. Do not redistribute them publicly. Buy a license or replace them with a free font before deploying.
- The profession photos come from Unsplash (Unsplash License). Photographer credits are in `public/img/profesi/CREDITS.md`.
