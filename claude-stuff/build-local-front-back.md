# Running T20Plus locally

WAMP/MySQL must already be running.

## Backend (Laravel API)

```
cd F:\t20plus\t20plus-api
php artisan serve
```

Runs at http://127.0.0.1:8000

## Frontend (Angular)

```
cd F:\t20plus\t20plus-frontend
npx ng serve
```

Runs at http://localhost:4200

## Verifying the frontend compiles (no dev server needed)

```
cd F:\t20plus\t20plus-frontend
npx ng build
```

## Re-running migrations + seeders

Safe to run anytime — this is just the local dev DB with seed data, nothing real to lose.

```
cd F:\t20plus\t20plus-api
php artisan migrate:fresh --seed
```
