# SkinTel Scanner

## 🛠️ Technology Stack

### Backend
- **Framework**: Laravel 11
- **Database**: MySQL
- **API**: RESTful API with JSON responses
- **Email**: Laravel Mail with customizable templates
- **Storage**: Local file storage for images

### Frontend
- **Framework**: Vue.js 3 with Composition API
- **Routing**: Inertia.js for SPA-like experience
- **UI Components**: Shadcn-vue component library
- **Styling**: Tailwind CSS for responsive design
- **Icons**: Lucide Vue icons
- **State Management**: Vue 3 reactive system
- **Face Detection**: face-api.js for face landmarks detection

### Development Tools
- **Build Tool**: Vite for fast development and building
- **Package Manager**: npm/yarn
- **Code Quality**: ESLint and Prettier (recommended)

## 📋 Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- MySQL

## 🚀 Installation

### 1. Clone the Repository
```bash
git clone <repository-url>
cd skintel-scanner
```

### 2. Backend Setup
```bash
# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations and seeders
php artisan migrate --seed

# Create storage link
php artisan storage:link
```

### 3. Frontend Setup
```bash
# Install Node.js dependencies
npm install

# Build assets for development
npm run dev

# Or build for production
npm run build
```

### 4. Environment Configuration

Edit `.env` file with your settings:

```env
# Application
APP_NAME="SkinTel Scanner"
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your-password
MAIL_FROM_ADDRESS=noreply@skintelscanner.com
MAIL_FROM_NAME="SkinTel Scanner"

# Internal team email for notifications
INTERNAL_EMAIL_SUBMISSION=team@skintelscanner.com
```

### 5. Start Development Servers
```bash
# Start Laravel development server
php artisan serve

# In another terminal, start Vite dev server
npm run dev
```

Visit `http://localhost:8000` to access the application.

## 📁 Project Structure

```
skintel-scanner/
├── app/
│   ├── Http/Controllers/Api/     # API controllers
│   ├── Mail/                     # Email classes
│   ├── Models/                   # Eloquent models
│   └── ...
├── database/
│   ├── migrations/               # Database migrations
│   └── seeders/                  # Database seeders
├── resources/
│   ├── js/
│   │   ├── components/           # Vue components
│   │   ├── pages/               # Inertia pages
│   │   └── ...
│   └── views/
│       └── emails/              # Email templates
├── routes/
│   ├── api.php                  # API routes
│   └── web.php                  # Web routes
└── ...
```

## 🔧 Key Models

### Submission
Stores user scan submissions and personal information.

### ScanResult
Contains AI analysis results for each skin concern.

### Product
Product catalog with concern-specific items.

### ProductRecommendation
Links products to submissions based on analysis.

## 🛣️ API Endpoints

### Submissions
- `PUT /api/submissions/{uuid}/personalized-routine` - Update submission with user info
- `GET /api/submissions/{uuid}` - Get submission details
- `GET /api/submissions/{uuid}/recommendations` - Get product recommendations

### Products
- Product management through admin interface (to be implemented)

## 📧 Email System

### Customer Emails
- **Analysis Results**: Detailed skin analysis with concern explanations
- **Product Recommendations**: Direct link to personalized product page

### Internal Emails
- **New Submission Notifications**: Customer info and analysis results
- **Technical Analysis Details**: Concern-specific ingredient recommendations

## 🛒 E-commerce Features

### Shopping Cart
- Add/remove products
- Quantity management
- Local storage persistence
- Cart validation

### Checkout Process
1. Cart review and item management
2. Customer information (pre-filled from submission)
3. Shipping address form with validation
4. Order total calculation
5. Payment processing simulation
6. Invoice generation

## 📦 Deployment

### Production Build
```bash
# Build frontend assets
npm run build

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Environment Setup
- Configure production database
- Set up mail server
- Configure file storage
- Set proper permissions
