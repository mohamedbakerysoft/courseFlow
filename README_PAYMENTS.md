# Payments Setup

This project supports Stripe, PayPal, and manual payments.

## Stripe

- Configure the Stripe keys from the dashboard settings.
- Webhook endpoint: `POST /webhooks/stripe`
- Access is granted only after verified completion.

## PayPal

- Configure PayPal mode, client ID, client secret, and webhook ID from the dashboard settings.
- Webhook endpoint: `POST /webhooks/paypal`
- For sandbox testing, use your own PayPal Developer sandbox merchant and buyer accounts.
- Do not rely on bundled or shared third-party sandbox credentials.

## Manual Payments

- Student flow:
  - `POST /courses/{course:slug}/manual/start`
  - `GET /payments/manual/pending/{payment}`
  - `POST /payments/manual/{payment}/submit`
- Admin flow:
  - `POST /dashboard/payments/{payment}/approve`
  - `POST /dashboard/payments/{payment}/reject`

## Security Notes

- Webhooks are CSRF-exempt only on the webhook endpoints.
- Installation and dashboard forms use normal CSRF protection.
- Access is granted only after confirmed or approved payment states.
- Duplicate paid records are protected by reconciliation logic across checkout flows.
