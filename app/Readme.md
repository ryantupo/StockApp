# Stock Alerts

A small stock tracking app built with Laravel 13, Inertia 3, Vue 3, and Tailwind. Browse products, record stock movements, and get notified (email + in-app) when something drops below its reorder threshold.

**Time spent:** ~10 hours, spread across 2-3 days around a busy schedule — approximate, not a hard-locked focused count.

## Running it locally

Needs [Herd](https://herd.laravel.com) (or any PHP 8.4+ setup), Node 20+, and Composer.

1. Clone the repo and park it in Herd
2. `composer install` && `npm install`
3. Copy `.env.example` to `.env`, then `php artisan key:generate`
4. `touch database/database.sqlite` (Windows: `New-Item database\database.sqlite`)
5. `php artisan migrate --seed` — seeds ~200 products and a few thousand movements
6. `npm run dev`
7. Visit the `.test` URL Herd gives the project

**Also run the queue** — notifications are queued (`QUEUE_CONNECTION=database`), so in a separate terminal: `php artisan queue:work`. Or set `QUEUE_CONNECTION=sync` in `.env` to skip needing a worker.

**Try the digest manually:** `php artisan stock:digest` — emails land in `storage/logs/laravel.log`.

**Check everything's green:** `php artisan check` runs Pint, PHPStan, and the test suite together.

## Decisions worth flagging

- UUIDs instead of auto-incrementing IDs, to avoid ID enumeration on product URLs
- Laravel Scout (database driver) for search — no external search engine needed
- Spatie Laravel Query Builder for list search/filter/sort
- Custom `Base*` Vue components instead of the starter kit's shadcn-vue ones, to match my own conventions
- Full CRUD for Product (not just list/view) — an added extra beyond the spec
- Notification "mark as read" uses a direct axios call rather than an Inertia visit, to sidestep a redirect/method-preservation quirk
- Notification bell polls every 15s rather than using WebSockets — a reasonable middle ground for this task's scope

## What I'd do differently with more time

- A proper CRUD dialog UI for products (backend supports it; no frontend form yet)
- Product images via an S3 bucket