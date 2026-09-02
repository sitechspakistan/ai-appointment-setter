# Deployment checklist — ai-appointments.webefytoday.com

Run on the **deployed** server (not local).

## 1. Environment (`.env`)

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://ai-appointments.webefytoday.com

DB_CONNECTION=mysql
DB_DATABASE=appointment_db          # import database/webefy_appointment_setter.sql
DB_USERNAME=...
DB_PASSWORD=...

# n8n outbound webhook signing secret — generate a fresh one:
#   php -r "echo bin2hex(random_bytes(24));"
N8N_WEBHOOK_SECRET=<random-hex>
```

## 2. One-time setup

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate            # if APP_KEY not set
# import the schema:
mysql -u <user> -p appointment_db < database/webefy_appointment_setter.sql
php artisan migrate                 # applies Sanctum's personal_access_tokens if missing
php artisan config:cache route:cache view:cache
npm ci && npm run build             # if using Vite assets

php artisan n8n:token               # copy the printed token for n8n
```

Point the web server's document root at `public/`. The static assets live in
`public/assets/` and are referenced with `asset('assets/...')`.

## 3. In the app (Super Admin → Settings)

| Field | Value |
|---|---|
| Booking domain | `ai-appointments.webefytoday.com` (seeded) |
| n8n booking webhook URL | `https://n8n.sitechs.co/webhook/webefy-booking` |

## 4. In n8n (n8n.sitechs.co) — both workflows already point at the deployed API

**Credentials to create:**

| Name | Type | Value |
|---|---|---|
| Webefy API Token | HTTP Header Auth | name `Authorization`, value `Bearer <php artisan n8n:token>` |
| Vapi API Key | HTTP Header Auth | name `Authorization`, value `Bearer <vapi key>` |
| WhatsApp Business Cloud | WhatsApp API | connect Meta account |

**Attach:**
- *Webefy — Confirmation Call (v2)*: `Webefy API Token` → Report Call to Webefy · `Vapi API Key` → Place Vapi Call
- *Webefy — Due Reminders (v2)*: `Webefy API Token` → Get Due Reminders + Mark Reminder Sent + Mark Reminder Failed · `WhatsApp Business Cloud` → Send WhatsApp Reminder

**Vapi assistant** → set Server URL to `https://n8n.sitechs.co/webhook/webefy-vapi-callback`.

**Activate** both workflows.

## 5. Smoke test

```bash
curl -s https://ai-appointments.webefytoday.com/api/v1/whoami \
  -H "Authorization: Bearer <token>" -H "Accept: application/json"
```

Then make a test booking at `/book/sarahshvac` and confirm:
- an `appointment.booked` execution appears in n8n (Confirmation Call workflow)
- a queued reminder shows up in the tenant's Reminders screen
