# URL Shortener

## Project

**URL Shortener**

## Local URL

```
http://localhost:<PORT_NUMBER>/
```

Replace `<PORT_NUMBER>` with the port on which your application is running.

## Super Admin Credentials

The Super Admin credentials are available in:

```
dashboard/json/superAdminCredential.json
```

## Environment Configuration

1. Rename the environment file:

```
.env.example
```

to

```
.env
```

2. Update the `.env` file with your own credentials, including:

- Database configuration
- Mail (SMTP) configuration
- Any other required environment variables

## Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
```

After updating the `.env` file, run the migrations:

```bash
php artisan migrate
```

Finally, start the development server:

```bash
php artisan serve
```

The application will be available at:

```
http://localhost:8000
```

