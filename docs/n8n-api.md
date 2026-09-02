# n8n Automation API (v1)

Machine-to-machine API the n8n backend uses to run the booking → AI call →
reminder flow. This app owns all data and auth; n8n is a stateless worker.

## Auth

Laravel Sanctum bearer token, issued to the **`n8n` service account**
(`users.role = 'service'`, no portal access, not tenant-scoped).

```bash
php artisan n8n:token              # issue a token
php artisan n8n:token --fresh      # revoke existing + issue a new one
```

Send on every request:

```
Authorization: Bearer <token>
Accept: application/json
Content-Type: application/json
```

Base URL: `{APP_URL}/api/v1`  ·  Rate limit: 300 req/min per token.
Missing/invalid token → `401 {"message":"Unauthenticated."}`.
Validation error → `422 {"message": "...", "errors": {field: [..]}}`.

`GET /whoami` → identity behind the token (probe).

## Tenants (read-only — provider config)

| Method | Path | Notes |
|---|---|---|
| GET | `/tenants` | `?slug=` `?status=` `?per_page=` |
| GET | `/tenants/{id}` | full config |

`vapi` / `whatsapp` blocks fall back to Webefy defaults; `uses_default`
tells you whether the tenant has its own IDs. Also returns
`confirmation_call_script` and `whatsapp_reminder_message`
(placeholders: `{{name}} {{service}} {{date}} {{time}} {{business}}`).

## Appointments

| Method | Path | Notes |
|---|---|---|
| GET | `/appointments` | `?tenant_id=` `?status=` `?date=` `?from=` `?to=` `?updated_since=` `?per_page=` |
| GET | `/appointments/{id}` | includes `tenant`, `reminders`, `call_logs` |
| POST | `/appointments` | create |
| PATCH | `/appointments/{id}` | update status / reschedule |

`status`: `pending` `confirmed` `declined` `completed` `no_show`.
Setting `status=confirmed` stamps `confirmed_at` automatically (pass your own to override);
`pending`/`declined` clears it.

**POST body**

```json
{
  "tenant_id": 1,
  "service_id": 1,                     // or "service_name": "AC Repair"
  "customer_name": "Marcus Reed",
  "customer_email": "marcus@example.com",
  "customer_phone": "(512) 447-0192",
  "notes": "Not cooling",
  "appointment_date": "2026-09-05",
  "appointment_time": "14:00",         // HH:mm, 24h
  "status": "pending",                 // optional
  "source": "phone",                   // web | embed | phone | manual
  "queue_reminder": true,              // optional — also create a queued reminder
  "reminder_channel": "whatsapp",      // whatsapp | voice
  "reminder_hours_before": 3
}
```

## Reminders

| Method | Path | Notes |
|---|---|---|
| GET | `/reminders/due` | **poll this** — queued + `scheduled_for <= now`. `?tenant_id=` `?channel=` `?limit=` |
| GET | `/reminders` | `?tenant_id=` `?status=` `?appointment_id=` `?per_page=` |
| GET | `/reminders/{id}` | |
| POST | `/reminders` | `{ "appointment_id", "channel", "scheduled_for"? }` |
| PATCH | `/reminders/{id}` | mark result |

`/reminders/due` embeds the full `appointment` (customer phone, service,
date/time) and its `tenant` — everything needed to send in one call.

**PATCH body** (after sending)

```json
{
  "status": "sent",                    // sent | failed  (sent stamps sent_at)
  "outcome": "confirmed",              // confirmed | declined | no_reply
  "provider_message_id": "wamid.XXX"
}
```

## Call logs (Vapi)

| Method | Path | Notes |
|---|---|---|
| POST | `/call-logs` | `{ "appointment_id", "vapi_call_id", "status", "outcome"?, ... }` |
| GET | `/call-logs/{id}` | |
| PATCH | `/call-logs/{id}` | update as the call progresses |

`status`: `queued` `ringing` `in_progress` `completed` `failed` `no_answer`.
`outcome`: `confirmed` `reschedule` `declined` `no_response`.

**Auto-sync:** when `outcome` is `confirmed` or `declined`, the linked
appointment is moved to `confirmed` / `declined` (method = `voice`).
Opt out with `"sync_appointment": false`.

## Typical n8n workflows

1. **Booking → confirmation call**
   Trigger on new booking (webhook from this app, or poll
   `/appointments?status=pending&updated_since=`) → `GET /tenants/{id}` for the
   Vapi assistant → place the call → `POST /call-logs` with the result
   (appointment auto-updates).

2. **Day-before reminders** (cron, e.g. every 15 min)
   `GET /reminders/due` → for each: send WhatsApp using the tenant's template →
   `PATCH /reminders/{id}` with `status=sent` + `outcome`.
   If `outcome=confirmed`, also `PATCH /appointments/{id}` `status=confirmed`.
