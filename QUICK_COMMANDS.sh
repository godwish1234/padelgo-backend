#!/bin/bash
# PadelGo Backend - Quick Reference Commands

echo "================================"
echo "PadelGo Backend Quick Commands"
echo "================================"
echo ""

# Colors for output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}📦 SETUP${NC}"
echo "php artisan migrate              # Run database migrations"
echo "php artisan db:seed              # Seed sample data"
echo "php artisan key:generate         # Generate APP_KEY"
echo ""

echo -e "${BLUE}🚀 DEVELOPMENT${NC}"
echo "php artisan serve                # Start development server (port 8000)"
echo "php artisan serve --port=3000    # Start on custom port"
echo "php artisan tinker               # Interactive shell"
echo ""

echo -e "${BLUE}📊 DATABASE${NC}"
echo "php artisan migrate:status       # Check migration status"
echo "php artisan migrate:rollback     # Rollback last migration"
echo "php artisan migrate:reset        # Reset all migrations"
echo "php artisan db:seed --class=MatchSeeder  # Seed specific seeder"
echo ""

echo -e "${BLUE}🛣️  ROUTES${NC}"
echo "php artisan route:list           # Show all routes"
echo "php artisan route:list --name=courses  # Filter routes"
echo ""

echo -e "${BLUE}🧪 TESTING${NC}"
echo "php artisan test                 # Run all tests"
echo "php artisan test tests/Feature/  # Run specific test suite"
echo "php artisan test --coverage      # Generate coverage report"
echo ""

echo -e "${BLUE}🔍 DEBUGGING${NC}"
echo "php artisan config:cache         # Cache configuration"
echo "php artisan route:cache          # Cache routes"
echo "php artisan view:cache           # Cache views"
echo "php artisan cache:clear          # Clear all caches"
echo ""

echo -e "${BLUE}📝 CODE GENERATION${NC}"
echo "php artisan make:controller Auth/AuthController    # Create controller"
echo "php artisan make:model Court -m                     # Create model with migration"
echo "php artisan make:request CreateCourtRequest         # Create form request"
echo "php artisan make:migration create_courts_table      # Create migration"
echo "php artisan make:seeder CourtSeeder                 # Create seeder"
echo ""

echo -e "${BLUE}✅ DEPLOYMENT${NC}"
echo "composer install --optimize-autoloader --no-dev     # Install production deps"
echo "php artisan config:cache        # Cache config for production"
echo "php artisan route:cache         # Cache routes for production"
echo "php artisan view:cache          # Cache views for production"
echo "php artisan migrate --force     # Run migrations in production"
echo ""

echo -e "${BLUE}📚 API TESTING${NC}"
echo ""
echo "# Register"
echo "curl -X POST http://localhost:8000/api/v1/auth/register \\"
echo "  -H 'Content-Type: application/json' \\"
echo "  -d '{\"name\":\"John\",\"email\":\"john@example.com\",\"password\":\"password123\",\"password_confirmation\":\"password123\"}'"
echo ""
echo "# Login"
echo "curl -X POST http://localhost:8000/api/v1/auth/login \\"
echo "  -H 'Content-Type: application/json' \\"
echo "  -d '{\"email\":\"john@example.com\",\"password\":\"password123\"}'"
echo ""
echo "# Get courts (with token)"
echo "curl -X GET http://localhost:8000/api/v1/courts \\"
echo "  -H 'Authorization: Bearer YOUR_TOKEN_HERE'"
echo ""

echo -e "${BLUE}🌍 ENVIRONMENT${NC}"
echo "cp .env.example .env             # Copy example env file"
echo "# Then edit .env with your database credentials"
echo ""

echo -e "${BLUE}🧹 CLEANUP${NC}"
echo "php artisan storage:link         # Create storage symlink"
echo "rm -rf bootstrap/cache/*         # Clear cache files"
echo "composer dump-autoload           # Regenerate autoloader"
echo ""

echo "================================"
echo "For more info, see:"
echo "  • README.md"
echo "  • API_DOCUMENTATION.md"
echo "  • SETUP_CHECKLIST.md"
echo "================================"
