# Configuration

After installation, Learnova is configured mainly from the dashboard.

## How Configuration Works

- Core environment values are written during installation into `.env`.
- Runtime business settings are stored in the database and can be updated from the admin dashboard.
- Payment, branding, authentication, contact, and storefront settings should normally be managed from the UI instead of editing source files.

## Installer-Written Environment Values

The installer sets the main application values such as:

- `APP_NAME`
- `APP_URL`
- `APP_KEY`
- `APP_ENV`
- `APP_DEBUG`
- database connection credentials

## Dashboard Settings Areas

### General

- Site name
- brand messaging
- contact details
- legal page content

### Payments

- Stripe publishable key, secret key, and webhook secret
- PayPal mode, client ID, client secret, and webhook secret
- manual payment instructions
- payment method visibility

### Authentication

- Google login enable/disable
- Google OAuth credentials

### Security

- Google reCAPTCHA enable/disable
- reCAPTCHA site key and secret key

### Notifications and Contact

- WhatsApp contact options
- live chat provider content
- Tawk embed code

### Landing and Brand Presentation

- homepage content blocks
- hero copy
- trust sections
- CTA labels

### Appearance

- color theme tokens
- English font selection
- storefront presentation controls

### Menus

- header and footer menu labels
- links
- ordering

### Content Management

- courses
- books
- FAQs
- instructor profile content
- pages

## Payment Configuration Reminder

- Payment settings are read from the dashboard settings.
- Do not rely on hardcoded sandbox credentials.
- PayPal and Stripe webhook endpoints should be configured on your final HTTPS domain.

## Language and UI Notes

- The current buyer package is prepared as an English-first product experience.
- Make all final branding, legal, and contact updates before publishing your live site.
