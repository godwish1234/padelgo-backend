# PadelGo Backend API

A production-ready REST API backend for **PadelGo**, a dedicated padel ecosystem mobile application.

## 🎯 Project Overview

PadelGo is an API-only backend built with Laravel and PostgreSQL, designed to power a Flutter mobile application for discovering padel courts, creating matches, and managing real-time scoring.

**Key Features:**

- ✅ Token-based authentication (Sanctum)
- ✅ Court discovery & location-based search
- ✅ Match creation & player management
- ✅ Built-in scoring system (sets/games/points)
- ✅ Role-based access control (user, court_admin, super_admin)
- ✅ RESTful API with comprehensive validation
- ✅ Database seeding for development
- ✅ Production-ready architecture

## 🛠 Tech Stack

- **Framework**: Laravel 11 (latest stable)
- **Database**: PostgreSQL
- **Authentication**: Laravel Sanctum (Token-based)
- **API**: RESTful, versioned (v1)
- **Language**: PHP 8.2+

## 📦 Quick Start

```bash
# Clone & setup
git clone <repository-url> padelgo-backend && cd padelgo-backend
composer install

# Configure environment
cp .env.example .env
php artisan key:generate

# Setup database
createdb padelgo
php artisan migrate --seed

# Start server
php artisan serve
```

**API available at**: `http://localhost:8000/api/v1`

## 📚 Documentation

- **[API Documentation](./API_DOCUMENTATION.md)** - Complete endpoint reference
- **[Setup Checklist](./SETUP_CHECKLIST.md)** - Full setup & deployment guide

## 🔐 Authentication

Token-based using Laravel Sanctum. Include in all authenticated requests:

```
Authorization: Bearer <token>
```

## 🎮 API Routes

| Category    | Method     | Endpoint                                            | Auth        |
| ----------- | ---------- | --------------------------------------------------- | ----------- |
| **Auth**    | POST       | `/auth/register`                                    | ✗           |
|             | POST       | `/auth/login`                                       | ✗           |
|             | POST       | `/auth/logout`                                      | ✓           |
|             | GET        | `/auth/me`                                          | ✓           |
| **Courts**  | GET        | `/courts`                                           | ✓           |
|             | GET        | `/courts/nearby`                                    | ✓           |
|             | POST       | `/courts`                                           | ✓ (admin)   |
|             | PUT/DELETE | `/courts/{id}`                                      | ✓ (admin)   |
| **Matches** | GET        | `/matches`                                          | ✓           |
|             | GET        | `/matches/nearby`                                   | ✓           |
|             | POST       | `/matches`                                          | ✓           |
|             | PUT/DELETE | `/matches/{id}`                                     | ✓ (creator) |
|             | POST       | `/matches/{id}/join`                                | ✓           |
|             | POST       | `/matches/{id}/leave`                               | ✓           |
| **Scoring** | GET        | `/matches/{id}/scoring`                             | ✓           |
|             | POST       | `/matches/{id}/scoring/sets`                        | ✓ (creator) |
|             | POST/PUT   | `/matches/{id}/scoring/sets/{setId}/games/{gameId}` | ✓ (creator) |

## 📁 Project Structure

```
app/
├── Enums/                    # UserRole, MatchStatus, SkillLevel, MatchType
├── Http/Controllers/Api/V1/  # AuthController, CourtController, MatchController, ScoringController
├── Http/Middleware/          # CheckRole, HandleApiExceptions
├── Http/Requests/            # Form request validation
├── Models/                    # User, Partner, Court, PadelMatch, MatchPlayer, Set, Game
├── Services/                 # AuthService
└── Traits/                    # ApiResponse

database/
├── migrations/               # Database schema
├── factories/               # Model factories
└── seeders/                 # Database seeders
```

## 💾 Database Models

- **Users**: With Sanctum tokens, roles, location
- **Partners**: Court organizations
- **Courts**: Padel courts with location indexes
- **PadelMatches**: Matches with status tracking
- **MatchPlayers**: M2M relationship with teams
- **Sets** & **Games**: Match scoring structure

## 🔒 User Roles

- `user`: Regular player (default)
- `court_admin`: Manage assigned courts
- `super_admin`: Full system access

## 🌍 Key Features

### Court Discovery

- List all courts with pagination
- Search by city
- Location-based nearby search (lat/long + radius)
- Automatic distance calculation

### Match Management

- Create open matches at courts
- Join/leave matches (with validation)
- Track current vs max players
- Prevent double-joining
- Filter by skill level, match type, status

### Scoring System

- Create sets for matches
- Create games within sets
- Update game scores (team_a_points, team_b_points)
- Mark sets/games as completed
- Finish matches with full score history

### Authentication

- Register with email & password
- Login with token generation
- Secure logout (revoke tokens)
- Get current user profile
- Location tracking

## 🚀 Deployment

See [SETUP_CHECKLIST.md](./SETUP_CHECKLIST.md) for complete deployment guide including:

- VPS setup (Hostinger, DigitalOcean, etc.)
- Nginx configuration
- SSL/HTTPS setup
- Database backup strategy
- Monitoring & logging

## 🧪 Development

```bash
# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Start development server
php artisan serve

# Run tests
php artisan test
```

## 📝 API Response Format

All responses are JSON with consistent format:

**Success:**

```json
{
  "success": true,
  "message": "Success message",
  "data": { ... }
}
```

**Error:**

```json
{
  "success": false,
  "message": "Error message",
  "errors": { "field": ["Error detail"] }
}
```

**Paginated:**

```json
{
  "success": true,
  "message": "Success",
  "data": [ ... ],
  "pagination": { "total": 100, "per_page": 15, ... }
}
```

## ⚙️ Environment Setup

Key variables in `.env`:

```env
APP_NAME=PadelGo
APP_ENV=local
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_DATABASE=padelgo
DB_USERNAME=postgres

SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1
```

## 📞 Support & Issues

- Check [API_DOCUMENTATION.md](./API_DOCUMENTATION.md) for detailed endpoint docs
- Review [SETUP_CHECKLIST.md](./SETUP_CHECKLIST.md) for setup issues
- See [Laravel Docs](https://laravel.com/docs) for framework questions

## 📄 License

Proprietary - PadelGo

---

**Built for the Padel Community** ⭐
