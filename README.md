# Student Course Hub

Student Course Hub is a lightweight PHP and Slim application for managing university programmes, modules, and student interest registrations.

## Overview

The application provides role-based access for administrators and staff, supports student interest registration, and includes email notifications for selected workflows.

## Features

- Programme and module management
- Student interest registration and review
- Admin and staff authentication
- Email notifications for registrations and updates
- CSRF protection for form submissions
- Logging and audit tracking

## Technology Stack

- PHP 8.0+ (developed with PHP 8.2)
- Slim 4
- PSR-7, PHP-DI, and PhpRenderer
- MySQL / MariaDB
- Composer for dependency management

## Requirements

- PHP 8.0+ with PDO/MySQL support
- Composer
- MySQL / MariaDB

## Installation

1. Install the PHP dependencies:

```bash
composer install
```

2. Create the database and import the SQL dump from `sql/student_course_hub (2).sql`.

3. Update the database credentials in `config/database.php`.

4. Update the SMTP configuration in `config/mail.php`.

5. Configure your web server to point to the `public/` directory. For local development, you can use the built-in PHP server:

```bash
php -S localhost:8080 -t public
```

6. Open the application in your browser:

```text
http://localhost:8080/
```

## Project Structure

- `public/index.php` - application bootstrap and route definitions
- `config/database.php` - database connection settings
- `config/mail.php` - email configuration
- `sql/student_course_hub (2).sql` - database schema and sample data
- `app/` - controllers, models, helpers, middleware, and views

## Notes

- Uploads are stored in `public/uploads`.
- Logging and audit data are stored in the database.
- CSRF protection and session configuration are initialized in `public/index.php`.
- Remove or rotate any real credentials before sharing the repository.

## Local Demo Accounts

These accounts are intended for local testing only.

- Staff: `Niranjan` / `admin12345`
- Admin: `Chitra` / `admin12345`
- Super Admin: `Super` / `admin12345`

## Contributing

Contributions are welcome. Please open an issue or submit a pull request for changes. Keep secrets and environment-specific credentials out of the repository.
