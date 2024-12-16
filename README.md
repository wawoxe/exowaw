# Wawoxe API

[![PHP Version](https://img.shields.io/badge/PHP-%3E=8.2-blue)](https://www.php.net)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

The backend for powering your personal website.

## Table of Contents

- [✨ Overview](#-overview)
- [🔧 Features](#-features)
- [📦 Installation](#-installation)
- [✅ Testing](#-testing)
- [🤝 Contributing](#-contributing)
- [📄 License](#-license)
- [🌟 Show Your Support](#-show-your-support)

## 🔧 Overview

- ✅ **Resume** — Serve your bio and key information dynamically.
- 🗂 **Portfolio Management** — Showcase all projects with descriptions and links.
- 📝 **Blog Posts** — Optional endpoint for dynamic blogging.
- 📬 **Contact Form API** — Handle contact submissions with ease.
- 🔐 **Secure & Extensible** — Ready for production with secure request handling.

## 📝 Features

The following features are planned for future updates:
- [X] Add User entity and admin command
- [X] Hash User password on creation and install
- [X] Integrate JWT authentication for secure endpoints
- [ ] API for changing email and password
- [ ] 2FA authentication

## 📦 Installation

### Prerequisites

Make sure you have the following installed:
- **PHP 8.2+**
- **Composer** (Dependency manager for PHP)
- **Database**: PostgreSQL

### Steps to Set Up Locally

1. **Clone the Repository**
```bash
git clone git@github.com:wawoxe/wawoxe-api.git
```

2. **Install Dependencies**
```bash
composer install
```

3. **Environment Configuration**
```bash
cp .env .env.local
nano .env.local # Configure project
```

4. **Database setup**
```bash
# If you don't have Symfony installed, then use "php bin/console" instead of "symfony console"

symfony console doctrine:database:create # Only if database doesn't exist
symfony console doctrine:migrations:migrate # Make all database migrations

# If you want to recreate database, use next commands:
symfony console doctrine:database:drop --force # WARNING: IT WILL DELETE ALL PREVIOUS DATA IN THE PROJECT DATABASE!
symfony console doctrine:database:create # Create database
symfony console doctrine:migrations:migrate # Make all database migrations
```
6. **Create secret and public keys**
```bash
# Available options:
# --skip-if-exists will silently do nothing if keys already exist.
# --overwrite will overwrite your keys if they already exist.


symfony console lexik:jwt:generate-keypair
```

7. **Create admin user**
```bash
symfony console app:installation:make:admin <email|username> <login> <password>
```

8. **Run project**
```bash
symfony serve
```

9. **Access the API**
```bash
https://localhost:8000/auth/jwt
```

## ✅ Testing

To ensure everything works as expected, run the test suite using PHPUnit:

```bash
php bin/phpunit
```

## 🤝 Contributing

Contributions are welcome! 🚀

To contribute:

1. **Fork this repository**

2. **Create a new branch**

```bash
git checkout -b feature/your-feature
```

3. **Make your changes and commit them**

```bash
git commit -m "Add your feature"
```

4. **Push to the branch**

```bash
git push origin feature/your-feature
```

5. **Open a Pull Request**

## 📄 License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## 🌟 Show Your Support

If you like this project, give it a ⭐ on GitHub!
