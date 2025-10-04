# Syarah Car Store

A comprehensive car dealership management system built with Yii2 Advanced Project Template. Features both a customer-facing storefront and an admin dashboard for managing car listings, users, and sales.

## 🚗 Features

### Storefront
- Browse cars with advanced filtering (make, model, year range, price range)
- Car details with multiple images
- Simple purchase system
- User order history
- Responsive design

### Dashboard (Admin Only)
- Car listing CRUD with image management (max 3 images)
- User management
- Sales analytics
- CSV export with background processing
- Role-based access control

## 🛠️ Quick Setup

### Prerequisites
- PHP 7.4+, MySQL 5.7+, Composer, Web server

### Installation

1. **Clone and install**
   ```bash
   git clone https://github.com/yourusername/syarah-car-store.git
   cd syarah-car-store
   composer install
   ```

2. **Configure environment**
   ```bash
   cp env.dist .env
   # Edit .env with your database credentials and URLs
   ```

3. **Set up configuration files**
   ```bash
   cp common/config/main-local.php.dist common/config/main-local.php
   cp dashboard/config/main-local.php.dist dashboard/config/main-local.php
   cp storefront/config/main-local.php.dist storefront/config/main-local.php
   cp console/config/main-local.php.dist console/config/main-local.php
   # Edit the copied files with your actual values
   ```

4. **Database setup**
   ```bash
   # Create database: yii2_carstore
   php yii migrate
   ```

5. **Create admin user**
   ```bash
   php yii admin/create admin admin@example.com password
   ```

6. **Start queue worker** (for CSV exports)
   ```bash
   php yii queue/listen
   ```

7. **Configure Apache for image uploads** (if using Apache)
   
   Add to your Apache configuration or `.htaccess`:
   ```apache
   Alias /uploads "C:/xampp/htdocs/yii2CarStore/common/web/uploads"
   <Directory "C:/xampp/htdocs/yii2CarStore/common/web/uploads">
       Options Indexes FollowSymLinks
       AllowOverride All
       Require all granted
   </Directory>
   ```
   
   **For XAMPP users:** Add this to `httpd.conf` or create a virtual host configuration.

8. **Access applications**
   - Storefront: `http://localhost/yii2CarStore/storefront/web/`
   - Dashboard: `http://localhost/yii2CarStore/dashboard/web/`

## 🔧 Background Jobs

The system uses Yii2 Queue for background processing (CSV exports):

```bash
# Start queue worker
php yii queue/listen

# Run once
php yii queue/run

# Check queue status
php yii queue/info
```

## 👤 Admin Management

**Important**: Admin users can ONLY be created through console commands for security.

```bash
php yii admin/create <username> <email> <password>
```

## 📁 Project Structure

```
yii2CarStore/
├── common/           # Shared models and configs
├── dashboard/        # Admin interface
├── storefront/       # Customer interface
├── console/          # Console commands
└── vendor/          # Dependencies
```

## 🏗️ Code Structure & Design Decisions

### Architecture Overview
The project follows Yii2 Advanced Project Template architecture with clear separation of concerns:

- **Common**: Shared code between applications (models, configurations, utilities)
- **Dashboard**: Admin-only interface for system management
- **Storefront**: Customer-facing application for browsing and purchasing
- **Console**: Command-line tools for admin user management

### Key Design Decisions

#### 1. **Yii2 Advanced Template**
- **Why**: Clean separation between frontend and backend applications
- **Benefit**: Independent deployment, different access controls, scalable architecture

#### 2. **Shared Models in Common**
- **Why**: Consistency across applications, single source of truth
- **Implementation**: `User`, `CarListing`, `Order` models in `common/models/`
- **Benefit**: Data integrity, easier maintenance

#### 3. **Role-Based Access Control**
- **Why**: Security-first approach for admin functionality
- **Implementation**: Admin-only dashboard access, console-based admin creation
- **Benefit**: Prevents unauthorized access, secure admin management

#### 4. **Background Job Processing**
- **Why**: Heavy operations (CSV export) shouldn't block user interface
- **Implementation**: Yii2 Queue extension with file-based queue
- **Benefit**: Better user experience, scalable processing

#### 5. **Image Management System**
- **Why**: Centralized storage for both applications
- **Implementation**: Images stored in `common/web/uploads/cars/`
- **Benefit**: Single storage location, web-accessible URLs

#### 6. **Configuration Templates**
- **Why**: Easy setup for new developers, security best practices
- **Implementation**: `.dist` template files, local configs excluded from Git
- **Benefit**: Quick setup, sensitive data protection

#### 7. **Console Commands for Admin Management**
- **Why**: Security - admin users only created through secure console
- **Implementation**: `AdminController` with `actionCreate` command
- **Benefit**: Prevents web-based privilege escalation

### Database Design

#### Core Tables
- **`user`**: User accounts with `is_admin` flag for role management
- **`car_listing`**: Car inventory with JSON image paths
- **`order`**: Purchase records linking users to cars
- **`export`**: Background job tracking for CSV exports

#### Relationships
- User → Orders (one-to-many)
- CarListing → Orders (one-to-many)
- Orders link users to purchased cars

### Security Considerations

1. **Admin Access**: Dashboard restricted to admin users only
2. **Input Validation**: All user inputs validated and sanitized
3. **File Upload Security**: Restricted file types and sizes
4. **Configuration Security**: Sensitive data in local files, not in Git
5. **CSRF Protection**: Built-in Yii2 CSRF protection

### Performance Optimizations

1. **AJAX Filtering**: Real-time search without page reloads
2. **Image Optimization**: Multiple image support with individual management
3. **Background Processing**: Heavy operations moved to background jobs
4. **Database Indexing**: Proper indexes on frequently queried fields

### Scalability Features

1. **Modular Architecture**: Easy to add new features
2. **Background Jobs**: Can handle increased load
3. **Centralized Storage**: Shared resources between applications
4. **Template System**: Easy configuration for different environments

## 🔧 Configuration

### Key Configuration Files
- `.env` - Environment variables (database, URLs, keys)
- `common/config/main-local.php` - Database and mailer config
- `dashboard/config/main-local.php` - Dashboard security keys
- `storefront/config/main-local.php` - Storefront security keys

### Security Keys
Generate secure keys for production:
```bash
openssl rand -base64 32
```

## 🚀 Features Implemented

- ✅ Car listing CRUD with image management
- ✅ User authentication and admin-only dashboard
- ✅ Car purchase system
- ✅ Sales analytics
- ✅ CSV export with background processing
- ✅ Advanced filtering and AJAX search
- ✅ Console admin user creation
- ✅ Role-based access control

## ⚠️ Known Issues

- Queue worker must run manually for background jobs
- File permissions may need manual setting
- Admin creation only via console commands (by design)

## 🔧 Troubleshooting

### Common Issues

1. **Configuration errors**
   ```bash
   # Copy missing templates
   cp */config/*-local.php.dist */config/*-local.php
   ```

2. **Database connection**
   - Check credentials in `common/config/main-local.php`
   - Ensure MySQL is running
   - Verify database exists

3. **Images not displaying**
   ```bash
   # Check file permissions
   chmod -R 755 common/web/uploads
   
   # For Apache/XAMPP, ensure alias is configured:
   # Add to httpd.conf or virtual host:
   # Alias /uploads "C:/xampp/htdocs/yii2CarStore/common/web/uploads"
   # <Directory "C:/xampp/htdocs/yii2CarStore/common/web/uploads">
   #     Options Indexes FollowSymLinks
   #     AllowOverride All
   #     Require all granted
   # </Directory>
   ```

4. **Queue not processing**
   ```bash
   php yii queue/listen
   ```

5. **Admin login issues**
   ```bash
   php yii admin/create admin admin@example.com password
   ```

## 🚀 Future Enhancements

- Soft delete implementation
- Payment integration
- Advanced reporting
- Mobile app API
- Real-time notifications

---

**Happy coding! 🚗💨**