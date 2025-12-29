# PadelGo API - Setup & Installation Guide

## Overview

PadelGo is a **REST API-only** backend for a padel mobile application built with Laravel and PostgreSQL.

## Tech Stack

- **Framework**: Laravel 11 (latest stable)
- **Database**: PostgreSQL
- **Authentication**: Laravel Sanctum (token-based)
- **API**: RESTful, versioned (v1)
- **Target Clients**: Flutter mobile app

## Installation

### Prerequisites

- PHP 8.2+
- Composer
- PostgreSQL
- Git

### Steps

```bash
# Clone the repository
git clone <repository-url> padelgo-backend
cd padelgo-backend

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Configure your database in .env
# DB_CONNECTION=pgsql
# DB_HOST=localhost
# DB_PORT=5432
# DB_DATABASE=padelgo
# DB_USERNAME=postgres
# DB_PASSWORD=<your-password>

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Seed sample data (optional)
php artisan db:seed

# Start development server
php artisan serve
```

## Project Structure

```
app/
├── Enums/              # Enum definitions (UserRole, MatchStatus, SkillLevel, MatchType)
├── Http/
│   ├── Controllers/Api/V1/  # API controllers
│   │   ├── AuthController.php
│   │   ├── CourtController.php
│   │   ├── MatchController.php
│   │   └── ScoringController.php
│   ├── Middleware/     # Custom middleware
│   │   └── CheckRole.php
│   └── Requests/       # Form requests (validation)
├── Models/             # Eloquent models
├── Services/           # Business logic services
├── Traits/             # Reusable traits (ApiResponse)
└── Policies/           # Authorization policies

database/
├── migrations/         # Database migrations
├── factories/          # Model factories for testing
└── seeders/           # Database seeders

routes/
├── api.php            # API routes (v1)
└── web.php            # Web routes (root endpoint)
```

## API Documentation

### Base URL

```
http://localhost:8000/api/v1
```

### Health Check

```
GET /api/health
```

### Authentication

#### Register

```
POST /api/v1/auth/register
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "+1234567890",
    "password": "password123",
    "password_confirmation": "password123"
}

Response:
{
    "success": true,
    "message": "User registered successfully",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "phone": "+1234567890",
            "role": "user"
        },
        "token": "1|abcd..."
    }
}
```

#### Login

```
POST /api/v1/auth/login
Content-Type: application/json

{
    "email": "john@example.com",
    "password": "password123"
}

Response:
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": { ... },
        "token": "1|abcd..."
    }
}
```

#### Get Current User

```
GET /api/v1/auth/me
Authorization: Bearer <token>

Response:
{
    "success": true,
    "message": "Success",
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "phone": "+1234567890",
        "role": "user",
        "latitude": 40.7128,
        "longitude": -74.0060
    }
}
```

#### Logout

```
POST /api/v1/auth/logout
Authorization: Bearer <token>

Response:
{
    "success": true,
    "message": "Logout successful",
    "data": {}
}
```

### Courts

#### List Courts

```
GET /api/v1/courts?per_page=15&city=Madrid&latitude=40.7128&longitude=-74.0060&radius=50
Authorization: Bearer <token>

Query Parameters:
- per_page: Items per page (default: 15)
- city: Filter by city
- latitude: User latitude (for distance filtering)
- longitude: User longitude (for distance filtering)
- radius: Search radius in km (default: 50)
```

#### Get Nearby Courts

```
GET /api/v1/courts/nearby?latitude=40.7128&longitude=-74.0060&radius=10&limit=10
Authorization: Bearer <token>
```

#### Get Single Court

```
GET /api/v1/courts/{id}
Authorization: Bearer <token>
```

#### Create Court (Admin Only)

```
POST /api/v1/courts
Authorization: Bearer <token>
Content-Type: application/json

{
    "partner_id": 1,
    "name": "Padel Club Madrid",
    "address": "Calle Principal 123",
    "city": "Madrid",
    "latitude": 40.7128,
    "longitude": -74.0060,
    "phone": "+34911234567",
    "facilities": ["parking", "cafe", "pro_shop", "changing_rooms"],
    "description": "Great padel court near city center",
    "is_active": true
}
```

#### Update Court

```
PUT /api/v1/courts/{id}
Authorization: Bearer <token>
Content-Type: application/json

{
    "name": "Updated Court Name",
    "is_active": true,
    ...
}
```

#### Delete Court

```
DELETE /api/v1/courts/{id}
Authorization: Bearer <token>
```

### Matches

#### List Matches

```
GET /api/v1/matches?per_page=15&court_id=1&status=open&skill_level=intermediate
Authorization: Bearer <token>

Query Parameters:
- per_page: Items per page (default: 15)
- court_id: Filter by court
- status: Filter by status (open, full, ongoing, finished, cancelled)
- skill_level: Filter by skill (beginner, intermediate, advanced)
```

#### Get Nearby Matches

```
GET /api/v1/matches/nearby?latitude=40.7128&longitude=-74.0060&radius=10&skill_level=intermediate
Authorization: Bearer <token>
```

#### Get Single Match

```
GET /api/v1/matches/{id}
Authorization: Bearer <token>
```

#### Create Match

```
POST /api/v1/matches
Authorization: Bearer <token>
Content-Type: application/json

{
    "court_id": 1,
    "title": "Friendly Match",
    "description": "Looking for players for a friendly game",
    "match_date_time": "2025-12-31 18:00:00",
    "max_players": 4,
    "skill_level": "intermediate",
    "match_type": "friendly",
    "notes": "Bring your own racket"
}
```

#### Update Match

```
PUT /api/v1/matches/{id}
Authorization: Bearer <token>
```

#### Delete Match

```
DELETE /api/v1/matches/{id}
Authorization: Bearer <token>
```

#### Join Match

```
POST /api/v1/matches/{id}/join
Authorization: Bearer <token>
```

#### Leave Match

```
POST /api/v1/matches/{id}/leave
Authorization: Bearer <token>
```

#### Get Match Players

```
GET /api/v1/matches/{id}/players
Authorization: Bearer <token>
```

### Scoring

#### Get Match Score

```
GET /api/v1/matches/{id}/scoring
Authorization: Bearer <token>
```

#### Create Set

```
POST /api/v1/matches/{id}/scoring/sets
Authorization: Bearer <token>
```

#### Create Game

```
POST /api/v1/matches/{id}/scoring/sets/{setId}/games
Authorization: Bearer <token>
```

#### Update Game Score

```
PUT /api/v1/matches/{id}/scoring/sets/{setId}/games/{gameId}
Authorization: Bearer <token>
Content-Type: application/json

{
    "team_a_points": 6,
    "team_b_points": 4,
    "is_completed": true
}
```

#### Complete Set

```
POST /api/v1/matches/{id}/scoring/sets/{setId}/complete
Authorization: Bearer <token>
```

#### Finish Match

```
POST /api/v1/matches/{id}/scoring/finish
Authorization: Bearer <token>
```

## Authentication & Authorization

### Token-Based (Sanctum)

All authenticated requests must include:

```
Authorization: Bearer <your-token>
```

### User Roles

- **user**: Regular player
- **court_admin**: Can manage own courts
- **super_admin**: Full access to all resources

### Protected Routes

Routes requiring authentication use the `auth:sanctum` middleware.
Routes requiring specific roles use the `role:role1,role2` middleware.

## Response Format

### Success Response

```json
{
    "success": true,
    "message": "Success message",
    "data": { ... }
}
```

### Error Response

```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    "field": ["Error details"]
  }
}
```

### Paginated Response

```json
{
    "success": true,
    "message": "Success message",
    "data": [ ... ],
    "pagination": {
        "total": 100,
        "per_page": 15,
        "current_page": 1,
        "last_page": 7,
        "from": 1,
        "to": 15
    }
}
```

## Database Schema

### Users

- id, name, email, phone, password, role, latitude, longitude, timestamps, soft_deletes

### Partners

- id, name, description, contact_email, contact_phone, website, timestamps, soft_deletes

### Courts

- id, partner_id, admin_user_id, name, address, city, latitude, longitude, phone, facilities, description, is_active, timestamps, soft_deletes

### Matches

- id, court_id, creator_id, title, description, match_date_time, max_players, current_players, skill_level, match_type, status, notes, timestamps, soft_deletes

### MatchPlayers

- id, match_id, user_id, team, status, timestamps

### Sets

- id, match_id, set_number, team_a_games, team_b_games, is_completed, timestamps

### Games

- id, set_id, game_number, team_a_points, team_b_points, is_completed, timestamps

## Configuration

### .env Key Settings

```env
APP_NAME=PadelGo
APP_ENV=production
APP_DEBUG=false
APP_URL=https://api.padelgo.com

DB_CONNECTION=pgsql
DB_HOST=your-db-host
DB_PORT=5432
DB_DATABASE=padelgo
DB_USERNAME=postgres
DB_PASSWORD=your-password

SANCTUM_STATEFUL_DOMAINS=padelgo.com,*.padelgo.com
SESSION_DOMAIN=.padelgo.com
```

## Deployment

### VPS (Hostinger/Similar)

1. **Connect via SSH**

   ```bash
   ssh user@your-vps-ip
   ```

2. **Setup PHP & Database**

   ```bash
   # Install PHP 8.2, PostgreSQL, Composer
   # Configure PostgreSQL connection
   ```

3. **Clone & Setup Project**

   ```bash
   cd /var/www
   git clone <repo> padelgo-backend
   cd padelgo-backend
   composer install --optimize-autoloader --no-dev
   ```

4. **Configure Environment**

   ```bash
   cp .env.production .env
   php artisan key:generate
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

5. **Run Migrations**

   ```bash
   php artisan migrate --force
   php artisan db:seed --class=DatabaseSeeder
   ```

6. **Setup Web Server (Nginx)**

   ```nginx
   server {
       listen 80;
       server_name api.padelgo.com;
       root /var/www/padelgo-backend/public;

       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }

       location ~ \.php$ {
           fastcgi_pass unix:/var/run/php-fpm.sock;
           fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
           include fastcgi_params;
       }
   }
   ```

7. **Setup HTTPS with Let's Encrypt**

   ```bash
   certbot certonly --nginx -d api.padelgo.com
   ```

8. **Setup Cron Job** (for queue processing if needed)
   ```bash
   * * * * * cd /var/www/padelgo-backend && php artisan schedule:run >> /dev/null 2>&1
   ```

## Testing

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/AuthTest.php

# Run with coverage
php artisan test --coverage
```

## Troubleshooting

### Common Issues

**CORS Errors**

- Ensure `config/cors.php` is properly configured for your frontend domain

**Sanctum Token Issues**

- Check `SANCTUM_STATEFUL_DOMAINS` in `.env`
- Verify API guard is set in `config/auth.php`

**Database Errors**

- Verify PostgreSQL is running
- Check database credentials in `.env`
- Ensure migrations have been run

## Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Sanctum Documentation](https://laravel.com/docs/11.x/sanctum)
- [PostgreSQL Documentation](https://www.postgresql.org/docs)
- [RESTful API Best Practices](https://restfulapi.net)

## License

Proprietary - PadelGo
