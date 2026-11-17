# KARIRIS — Know Your Career

KARIRIS is a career exploration app built with Laravel 12 (PHP 8.2), Blade, and Neon Postgres. It implements use cases UC-01 to UC-23 from the KARIRIS CDR. The UI follows the Hi-Fi Figma frames: a 440px mobile frame with the PP Telegraf and Klein fonts.

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

The controllers follow the CDR class diagram. `AkunController` handles accounts and profiles. `ProfessionController` handles professions, bookmarks, Career Match, and Day in the Life. The career-match calculation lives in `app/Services/CareerMatchService.php`. The score is the user's skills, weighted by level (Advanced 1.0, Intermediate 0.85, Basic 0.65), matched against the profession's `kriteria_skill`, which makes up 85%. The other 15% is a bonus when the user's experience fits the profession.

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
```

### Database: Neon Postgres

1. Create a project at https://console.neon.tech.
2. Click **Connect** and copy the connection string.
3. Paste it into `.env`:
   ```
   DB_CONNECTION=pgsql
   DB_URL="postgresql://USER:PASSWORD@ep-xxxx.REGION.aws.neon.tech/neondb?sslmode=require"
   DB_SSLMODE=require
   ```
4. Enable the `pdo_pgsql` and `pgsql` extensions in `php.ini`. With XAMPP, the file is `C:\xampp\php\php.ini`.
5. Run the migrations and seed the data:
   ```bash
   php artisan migrate:fresh --seed
   ```

To work offline, set `DB_CONNECTION=sqlite` instead. The schema is compatible with both databases.

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
| Password | `nilaiDPP-A` |

## Tests

```bash
php artisan test
```

There are 25 feature tests. They cover auth, skill and experience CRUD, ownership checks, the username/password rules, search and filter, bookmarks, Career Match, and Day in the Life.

## Asset notes

- The fonts in `public/fonts` (PP Telegraf, Klein) are the designer's commercial/trial fonts. They are only for local development and the class demo. Do not redistribute them publicly. Buy a license or replace them with a free font before deploying.
- The profession photos come from Unsplash (Unsplash License). Photographer credits are in `public/img/profesi/CREDITS.md`.
