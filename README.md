# MC Shop USA

A modern PHP-based eCommerce platform designed for small and medium-sized businesses, featuring product management, Stripe payment integration, inventory control, order processing, and administrative tools.

## Features

- 🛒 Complete online store
- 💳 Stripe Checkout integration
- 📦 Inventory management
- 📊 Stock reports
- 🔍 SEO-friendly sitemap
- 📱 Responsive frontend
- 🖼 Product image management
- 📁 JSON-based product storage
- 📈 Product backup system
- 🔐 Administrative dashboard
- ⚡ Lightweight PHP architecture

---

## Tech Stack

| Technology | Version |
|------------|----------|
| PHP | 8.x |
| JavaScript | ES6 |
| HTML5 | Latest |
| CSS3 | Latest |
| Stripe PHP SDK | ^20 |
| PhpSpreadsheet | ^5.7 |

---

## Project Structure

```text
.
├── api/
│   ├── accesses/
│   └── ...
├── site/
│   ├── index.php
│   ├── checkout.php
│   ├── products.json
│   └── html/
├── vendor/
├── config.php
├── app.js
├── sitemap.php
├── relatorio-estoque.php
├── composer.json
└── ...
```

---

## Installation

### Clone the repository

```bash
git clone https://github.com/yourusername/mc-shop-usa.git
cd mc-shop-usa
```

### Install dependencies

```bash
composer install
```

### Configure Stripe

Create your configuration using environment variables.

Example:

```php
STRIPE_SECRET_KEY=your_secret_key
STRIPE_PUBLISHABLE_KEY=your_publishable_key
```

> **Never commit API keys or secrets to the repository.**

---

## Running Locally

If using PHP's built-in server:

```bash
php -S localhost:8000
```

Then open:

```
http://localhost:8000
```

---

## Main Modules

### Storefront

- Product catalog
- Product pages
- Shopping cart
- Checkout
- Responsive interface

### Payment

- Stripe Checkout
- Secure payment processing
- Order confirmation

### Inventory

- Product management
- Stock control
- Inventory reports
- Product backups

### SEO

- Sitemap generation
- Clean URLs
- Search engine optimization

---

## Dependencies

Installed through Composer.

```json
{
    "require": {
        "stripe/stripe-php": "^20.0",
        "phpoffice/phpspreadsheet": "^5.7"
    }
}
```

---

## Security

For production:

- Store API keys in environment variables.
- Never commit secrets.
- Exclude `vendor/` from version control.
- Keep Composer dependencies updated.
- Validate all user input.
- Use HTTPS in production.

---

## Recommended .gitignore

```gitignore
/vendor
.env
*.log
node_modules
products-backup-*.json
error_log
```

---

## Deployment

1. Upload the project to your server.
2. Run:

```bash
composer install --no-dev
```

3. Configure:

- Stripe keys
- PHP version (8+)
- HTTPS
- File permissions

---

## Future Improvements

- User authentication
- Customer accounts
- Order history
- Admin dashboard enhancements
- Coupon system
- Wishlist
- Product reviews
- Email notifications
- Analytics dashboard
- Multi-language support

---

## License

This project is available for educational and commercial use unless otherwise specified.

---

## Author

**Joshua Pedroso**

Website: joshai0castro@gmail.com

GitHub: https://github.com/joshuacpedroso

---

## Disclaimer

This repository does **not** include production API keys or sensitive credentials. All secrets should be managed through secure environment variables or server-side configuration.
