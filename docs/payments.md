# Payments

Learnova supports Stripe, PayPal, and manual payment requests.

## Supported Payment Methods

- Stripe
- PayPal
- manual payment review

## Payment Flow Rules

- Access is granted only after a payment is confirmed or a manual request is approved.
- Failed or incomplete checkout attempts do not grant course or book access.
- Manual requests stay pending until an administrator reviews them.
- The platform protects against duplicate grants when the same order is completed more than once through overlapping payment events.

## Stripe Setup

Configure Stripe from Dashboard → Settings → Payments.

Required values:

- publishable key
- secret key
- webhook secret

Webhook endpoint:

- `/webhooks/stripe`

## PayPal Setup

Configure PayPal from Dashboard → Settings → Payments.

Required values:

- mode: `sandbox` or `live`
- client ID
- client secret
- webhook ID

Webhook endpoint:

- `/webhooks/paypal`

## PayPal Sandbox Testing

For sandbox testing, use your own PayPal Developer sandbox accounts.

Recommended setup:

1. Create a `Business` sandbox account for the merchant.
2. Create a `Personal` sandbox account for the buyer.
3. Create a sandbox app connected to the merchant account.
4. Use that sandbox app client ID and secret in Learnova.
5. Save the PayPal webhook ID from your PayPal Developer webhook configuration.
6. Use the personal sandbox account when logging in during checkout.

Important:

- Do not use bundled or shared sandbox credentials.
- Do not use the merchant sandbox account as the buyer account.
- If you test card checkout inside PayPal sandbox, use PayPal test card data from your own sandbox/testing setup.

## Manual Payments

Manual payments are useful for bank transfers, offline payments, or regional payment methods outside Stripe and PayPal.

Flow:

1. The customer submits a manual payment request.
2. The customer can provide a transfer reference and upload proof.
3. The request appears in Dashboard → Finance → Manual Payment Requests.
4. An administrator approves or rejects the request.
5. Access is granted only after approval.

## Production Checklist

1. Set your final `APP_URL`.
2. Enable HTTPS on your real domain.
3. Save your live Stripe or PayPal credentials.
4. Register the correct webhook endpoints in Stripe and PayPal.
5. Run a real end-to-end test before opening sales to customers.
