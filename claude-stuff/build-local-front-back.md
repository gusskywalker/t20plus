# Running T20Plus locally

WAMP/MySQL must already be running.

## Backend (Laravel API)

```
cd F:\t20plus\t20plus-api
php artisan serve
```

Runs at http://127.0.0.1:8000

## Frontend (Angular)

`environment.ts`'s `apiUrl` is `/api` (relative, not an absolute `localhost:8000` URL) — needed so the same build works both locally and through an ngrok tunnel (see below). This means `ng serve` needs the proxy config to actually reach the backend:

```
cd F:\t20plus\t20plus-frontend
npx ng serve --proxy-config proxy.conf.json
```

Runs at http://localhost:4200, with `/api/*` calls transparently forwarded to the backend on port 8000.

## Testing on a phone via ngrok

One tunnel covers both frontend and backend, since Angular's dev-server proxy forwards `/api/*` to Laravel internally.

```
cd F:\t20plus\t20plus-frontend
npx ng serve --proxy-config proxy.conf.json
```

Then, separately (ngrok.exe wherever it's downloaded):

```
ngrok http --url=<your-reserved-ngrok-domain> 4200
```

Open the ngrok HTTPS URL on the phone. Two one-time setup notes if this stops working:
- The ngrok domain must be in `angular.json`'s `serve.options.allowedHosts` (Angular dev-server blocks unrecognized Host headers) — `localhost` is also in there so plain local dev still works.
- Google OAuth needs the ngrok origin (`https://<domain>`) added under "Authorized JavaScript origins" for the OAuth Client ID, alongside `http://localhost:4200`.

## Verifying the frontend compiles (no dev server needed)

```
cd F:\t20plus\t20plus-frontend
npx ng build
```

## Re-running migrations + seeders

run when the user asks to — this is just the local dev DB with seed data, nothing real to lose.

```
cd F:\t20plus\t20plus-api
php artisan migrate:fresh --seed
```
