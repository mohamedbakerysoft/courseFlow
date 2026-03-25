# Learnova

Learnova is a Laravel-based online learning and digital products platform for selling courses, books, and downloadable educational resources.

## What Is Included

- public storefront for courses and books
- student accounts and protected access flow
- administrator dashboard
- Stripe, PayPal, and manual payments
- guided web installer
- responsive light and dark mode UI

## Installation

1. Upload the buyer package to your server.
2. Point the web root to the `public` directory.
3. Create a MySQL or MariaDB database.
4. Visit `/install` in the browser.
5. Complete the installer steps and create your admin account.

## Buyer Documentation

Please read the documentation in the `docs` folder:

- `docs/index.md`
- `docs/installation.md`
- `docs/server-requirements.md`
- `docs/configuration.md`
- `docs/payments.md`
- `docs/features.md`

## Important Notes

- The buyer package includes `vendor` and built frontend assets for faster setup.
- Payment credentials should be configured from the dashboard after installation.
- Use your own Stripe and PayPal test or live credentials.
