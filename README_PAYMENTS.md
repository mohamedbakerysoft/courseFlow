# Payments Setup

## Stripe
- Set environment:
  - `STRIPE_SECRET` (server)
  - `STRIPE_PUBLISHABLE_KEY` (client display if needed)
  - `STRIPE_WEBHOOK_SECRET`
- Webhook endpoint: `POST /webhooks/stripe`
- Behavior:
  - CSRF disabled on webhook only
  - Signature verification required
  - Enrollment occurs after verified `checkout.session.completed`

## PayPal
- Set environment:
  - `PAYPAL_CLIENT_ID`
  - `PAYPAL_CLIENT_SECRET`
  - `PAYPAL_WEBHOOK_SECRET` (used for Dusk/testing HMAC)
  - `PAYPAL_BASE_URL` (`https://api-m.paypal.com` or sandbox)
- Callback:
  - Success URL: `GET /payments/paypal/success`
  - Server-side verification required (`Orders API`)
  - Enrollment occurs only after verified completion

## Manual Payments
- Flow:
  - Student starts manual payment: `POST /courses/{course:slug}/manual/start`
  - Pending page: `GET /payments/manual/pending/{payment}`
  - Approval (instructor/admin): `POST /dashboard/payments/{payment}/approve`
- Security:
  - Requires authenticated user
  - Requires instructor/admin role
  - Requires CSRF (production)
  - Idempotent approval (no duplicate enrollments)

## Security Notes
- Production never bypasses auth/role/CSRF
- Conditional relaxations exist only when `app()->environment('dusk')` is true:
  - Manual approval route may skip auth/role/CSRF for browser tests
  - Controller may select an admin approver only in Dusk
- Duplicate paid records are prevented across providers
- Success URLs redirect only when payment context belongs to the user

