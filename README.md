# Fitness Centre Backend API

A Laravel-based REST API for managing a fitness centre. Handles members, bookings, and authentication.

## Features

- **Authentication**: Login/Register with Laravel Sanctum tokens
- **Members Management**: CRUD operations for fitness centre members
- **Bookings Management**: CRUD operations for class bookings

## Requirements

- PHP 8.1+
- MySQL 5.7+
- Composer

## Installation

```bash
# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate app key
php artisan key:generate

# Configure database in .env
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=

# Run migrations and seed
php artisan migrate:fresh --seed
```

## Running the Server

```bash
php artisan serve
```

The API will be available at `http://127.0.0.1:8000`

## API Endpoints

### Authentication
- `POST /api/v1/web/auth/login` - Login
- `POST /api/v1/web/auth/register` - Register
- `POST /api/v1/web/auth/logout` - Logout (protected)
- `GET /api/v1/web/auth/user` - Get current user (protected)

### Members (protected)
- `GET /api/v1/web/members` - List members
- `POST /api/v1/web/members` - Create member
- `GET /api/v1/web/members/{id}` - Get member
- `PUT /api/v1/web/members/{id}` - Update member
- `DELETE /api/v1/web/members/{id}` - Delete member

### Bookings (protected)
- `GET /api/v1/web/bookings` - List bookings
- `POST /api/v1/web/bookings` - Create booking
- `GET /api/v1/web/bookings/{id}` - Get booking
- `PUT /api/v1/web/bookings/{id}` - Update booking
- `DELETE /api/v1/web/bookings/{id}` - Delete booking

## Test Credentials

- **Username**: `admin`
- **Password**: `password123`
