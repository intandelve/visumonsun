**Overview**

-   **Purpose:** This repository is a Laravel 12 web app (PHP ^8.2) that collects and serves rainfall, wind and forecast data via both server-rendered views and a small JSON API. The frontend uses Vite + Tailwind + Alpine.

**How To Run (dev)**

-   **Quick bootstrap (PowerShell):**

    ```powershell
    composer install; cp .env.example .env; php artisan key:generate; composer run-script setup
    ```

-   **Local dev (recommended):**

    -   Use `composer run-script dev` (the project `package.json` + `composer` scripts spawn `php artisan serve`, `queue:listen`, `php artisan pail`, and `vite` concurrently).
    -   To run only the frontend: `npm run dev`.

-   **Build production assets:** `npm run build` then `php artisan migrate --force` (CI) and other deploy steps.

**Tests**

-   Run PHP tests: `composer test` (invokes `php artisan test`, project uses Pest). Seeders exist under `database/seeders` for generating demo data.

**Architecture & Data Flow**

-   Web routes are in `routes/web.php` (server-rendered views like `dashboard`, `forecast`, `statistics`).
-   API routes are in `routes/api.php` and return JSON for front-end visualizations: see endpoints like `/api/rainfall-stats`, `/api/wind-speed-stats`, `/api/comparison-data`, `/api/wind-map-data`, `/api/seasonal-forecast`.
-   Controllers are split by intent:
    -   `app/Http/Controllers/Admin/*` — pages and CRUD used by authenticated admins (dashboard, edit/delete flows).
    -   `app/Http/Controllers/Api/*` — lightweight JSON endpoints used by SPA/visualization code.
-   Models live in `app/Models`. Note a few non-default conventions:
    -   `App\Models\HistoricalRainfall` sets `protected $table = 'historical_rainfall_data'` (non-standard plural). Check model `$table` when accessing DB.
    -   `App\Models\WindMapData` casts `json_data` to `array` via `$casts` — API consumers expect structured arrays.

**Project-specific Conventions**

-   Controller patterns: Admin controllers perform simple Eloquent queries and return views (see `Admin\RainfallController@index|edit|update|destroy`). Follow their style: minimal business logic in controllers; models are plain Eloquent models.
-   API controllers return JSON via `response()->json(...)` and usually expose only `index` currently. If you add new endpoints, keep responses stable (arrays/objects) to avoid breaking front-end visualizations.
-   Database: migrations exist under `database/migrations`. Seeders are present for each data type (`RainfallDataSeeder`, `HistoricalRainfallSeeder`, `WindMapDataSeeder`, `WindSpeedDataSeeder`, `ForecastSeeder`). Use seeders to create representative datasets for visual work.

**Integration Points & External Dependencies**

-   Auth: Laravel Sanctum is installed (`composer.json`) — some endpoints use `auth:sanctum` middleware (see `routes/api.php`).
-   Frontend build: Vite + `laravel-vite-plugin` + Tailwind. Asset sources: `resources/js` and `resources/css`.
-   Background/queues: `php artisan queue:listen` is invoked in dev scripts; `php artisan pail` is included — be aware dev script spawns these concurrently.

**Examples & Notable Files**

-   API endpoint example: `routes/api.php` → `App\Http\Controllers\Api\RainfallDataController::index` returns ordered rainfall by `month_index`.
-   Admin edit flow example: `app/Http/Controllers/Admin/RainfallController.php` — uses `RainfallData::findOrFail($id)`, edits `rainfall_mm`, then `save()` and redirects to route `dashboard`.
-   Model casting example: `app/Models/WindMapData.php` has `$casts = ['json_data' => 'array'];` — expect an array in controller/view.

**Small rules for AI edits**

-   Preserve existing route names and controller method signatures; the front-end and views call them by name.
-   When changing model table names or JSON shapes, update both the model `$casts` and any API responses or view usages (search `json_data` and `historical_rainfall_data`).
-   Keep migrations and seeders in sync: new model columns → migration + seeder update.

**Common Commands Cheat-sheet (PowerShell)**

```powershell
composer install
cp .env.example .env; php artisan key:generate
php artisan migrate
php artisan db:seed --class=RainfallDataSeeder
npm install; npm run dev
composer test
```

**If something's unclear**

-   Ask about which environment to target (sqlite in `database/database.sqlite` is used for local by default), or whether you should update views or API responses when changing data shapes. Point to the exact file (path) to change and the intended behavior.

---

If you'd like I can adjust the tone/length, add CI snippets, or include a short JSON example of API responses for `rainfall-stats`. Want me to add that?
