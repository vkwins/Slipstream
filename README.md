# Slipstream - Customer & Contacts Management System

A modern Laravel 11 + Vue.js web application for managing customers and their associated contacts. This system provides a comprehensive, single-page application interface for creating, viewing, editing, and deleting customer records along with their contact information.

## Features

### Customer Management
- **Summary Table**: View all customers in a responsive table format
- **Real-time Search**: Search customers by name, reference, or description without page reload
- **CRUD Operations**: Create, Read, Update, and Delete customer records
- **Modal Forms**: All customer operations use modal dialogs for a seamless user experience
- **Confirmation Dialogs**: Safe deletion with confirmation prompts
- **Category Management**: Customers are categorized as Gold, Silver, or Bronze

### Contact Management
- **Integrated Contact Management**: Manage contacts directly within customer records
- **Contact Listing**: View all contacts associated with each customer in the customer modal
- **Contact CRUD**: Create, edit, and delete contacts within customer records
- **Contact Details**: Each contact includes first name and last name
- **Customer Association**: All contacts are linked to their parent customer

### User Interface
- **Modern SPA**: Single-page application built with Vue.js 3
- **Responsive Design**: Works on desktop and mobile devices
- **Modal Dialogs**: Clean, focused forms for data entry
- **Real-time Updates**: Changes reflect immediately without page refresh
- **Intuitive Navigation**: Easy-to-use interface with clear actions

## Database Schema

### Customers Table
- `id` - Primary key
- `name` - Customer name
- `reference` - Unique reference number
- `customer_category_id` - Foreign key to customer_categories table
- `start_date` - Relationship start date
- `description` - Additional notes
- `created_at` / `updated_at` - Timestamps

### Customer Categories
Pre-seeded categories:
- Gold
- Silver
- Bronze

### Contacts Table
- `id` - Primary key
- `customer_id` - Foreign key to customers table
- `first_name` - Contact's first name
- `last_name` - Contact's last name
- `created_at` / `updated_at` - Timestamps

## Technology Stack

- **Backend**: Laravel 11 (PHP)
- **Frontend**: Vue.js 3 with Tailwind CSS
- **Database**: MySQL (via Lando)
- **Development Environment**: Lando with Docker
- **Package Manager**: Composer & NPM
- **Build Tool**: Vite (with fallback to CDN)

## Installation & Setup

### Prerequisites
- [Lando](https://docs.lando.dev/getting-started/installation.html) installed on your system
- Git for version control

### Step-by-Step Installation

1. **Clone/Pull the Repository**
   ```bash
   # If you're starting fresh
   git clone <repository-url> slipstream
   cd slipstream
   
   # If you're updating an existing project
   git pull origin main
   ```

2. **Start Lando**
   ```bash
   lando start
   ```

3. **Install PHP Dependencies**
   ```bash
   lando composer install
   ```

4. **Set up Environment**
   ```bash
   # Copy environment file
   lando cp .env.example .env
   
   # Generate application key
   lando artisan key:generate
   ```

5. **Configure Database**
   ```bash
   # Run migrations
   lando artisan migrate
   
   # Seed the database with initial data
   lando artisan db:seed
   ```

6. **Install Node.js Dependencies (Optional)**
   ```bash
   # Install NPM packages
   lando npm install
   
   # Build assets (if you want to use Vite instead of CDN)
   lando npm run build
   ```

7. **Configure Hosts File**
   Add this line to your hosts file (`/etc/hosts` on Mac/Linux, `C:\Windows\System32\drivers\etc\hosts` on Windows):
   ```
   127.0.0.1 slipstream.lndo.site
   ```

## Accessing the Application

- **Main URL**: http://slipstream.lndo.site
- **Customers Page**: http://slipstream.lndo.site/customers

## Development Workflow

### Making Changes
1. Make your code changes
2. Test locally using Lando
3. Commit and push to your branch
4. Create a pull request

### Database Changes
If you need to make database changes:
```bash
# Create a new migration
lando artisan make:migration your_migration_name

# Run migrations
lando artisan migrate

# Rollback if needed
lando artisan migrate:rollback
```

### Frontend Development
The application uses Vue.js 3 with a fallback to CDN. For development:
```bash
# Install dependencies
lando npm install

# Start Vite dev server
lando npm run dev

# Build for production
lando npm run build
```

## Testing the Installation

After installation, verify everything is working:

```bash
# Check if models are accessible
lando artisan tinker --execute="echo 'Customers: ' . App\Models\Customer::count(); echo 'Contacts: ' . App\Models\Contact::count(); echo 'Categories: ' . App\Models\CustomerCategory::count();"
```

Expected output:
```
Customers: 3
Contacts: 6
Categories: 3
```

## Project Structure

```
src/
├── app/
│   ├── Http/Controllers/
│   │   ├── CustomerController.php
│   │   └── ContactController.php
│   └── Models/
│       ├── Customer.php
│       ├── Contact.php
│       └── CustomerCategory.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php
│       └── app.blade.php
└── routes/
    └── web.php
```

## API Endpoints

The application provides RESTful API endpoints for Vue.js integration:

### Customers
- `GET /api/customers` - List all customers (with search)
- `POST /api/customers` - Create new customer
- `GET /api/customers/{id}` - Get specific customer
- `PUT /api/customers/{id}` - Update customer
- `DELETE /api/customers/{id}` - Delete customer

### Contacts
- `POST /api/contacts` - Create new contact
- `PUT /api/contacts/{id}` - Update contact
- `DELETE /api/contacts/{id}` - Delete contact

## Troubleshooting

### Common Issues

1. **Lando not starting**
   - Check if Docker is running
   - Try `lando rebuild`

2. **Database connection issues**
   - Verify `.env` file has correct database settings
   - Run `lando artisan config:clear`

3. **Vue.js not loading**
   - Check browser console for errors
   - Verify CDN links are accessible
   - Try `lando npm run build` if using Vite

4. **Page not loading**
   - Check hosts file configuration
   - Verify Lando is running: `lando status`

### Useful Commands

```bash
# Check Lando status
lando status

# View logs
lando logs

# Rebuild Lando
lando rebuild

# Clear Laravel caches
lando artisan cache:clear
lando artisan config:clear
lando artisan view:clear

# Restart services
lando restart
```

## Contributing

1. Create a feature branch from `main`
2. Make your changes
3. Test thoroughly
4. Commit with descriptive messages
5. Push and create a pull request

## Support

For issues or questions:
1. Check the troubleshooting section above
2. Review Laravel and Vue.js documentation
3. Create an issue in the project repository
