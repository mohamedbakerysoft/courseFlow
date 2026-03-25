# Configuration

After installation, configuration is managed via the Admin panel:
- Payments: Stripe, PayPal, Manual
- Landing page content
- Appearance and theme colors
- Instructor profile

Environment variables:
- Set by the installer in `.env`
- `APP_URL`, `DB_*`, `APP_KEY`, `APP_DEBUG`
- Do not hardcode credentials in code

Updating Settings:
- Go to Dashboard → Settings
- Save changes; they take effect immediately

## Testing Payments (Demo Mode)
If you would like to test the checkout flow using the PayPal Sandbox, you can use the following test buyer account:
- **Email:** `sb-ftn2t50151885@personal.example.com`
- **Password:** `2F/thMUa`

*This account is pre-funded with sandbox currency specifically for testing PayPal integration.*
