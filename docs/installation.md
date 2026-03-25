# Installation

Learnova includes a guided web installer for first-time setup.

## Before You Start

- Upload the buyer package files to your hosting account.
- Point your domain or subdomain document root to the `public` directory.
- Create an empty MySQL or MariaDB database.
- Make sure `storage` and `bootstrap/cache` are writable.

## Installation Steps

1. Open your website in the browser and visit `/install`.
2. Run the environment check and confirm all required items pass.
3. Enter your application URL and database credentials.
4. Continue through the dependency and asset checks.
5. Run migrations.
6. Create the administrator account.
7. Finish the installer and sign in to the dashboard.

## Important Notes

- The CodeCanyon buyer package already includes `vendor` and `public/build`, so dependency installation and asset build steps may pass immediately on many shared-hosting environments.
- The installer writes the main `.env` values for you, including the app URL, app key, and database connection.
- The installer also runs the database seeders, so the first installation includes the default site content and demo-ready records that you can edit from the dashboard.
- After installation, most day-to-day configuration is managed from the dashboard instead of editing files manually.
- For production websites, keep `APP_DEBUG` disabled.

## Recommended Post-Install Checklist

1. Go to Dashboard → Settings and update the general site details.
2. Configure your payment gateways before accepting orders.
3. Review the default demo content and replace it with your own branding, courses, books, menus, and legal pages.
4. Confirm that email delivery, login, checkout, and webhooks work on your final domain.
