# Rudra Stories - Setup Guide

## Project Overview
This is a Laravel 8 based web application for sharing and reading stories. It uses PHP, MySQL, and Laravel framework.

## Prerequisites (Required Software)

### 1. PHP (Version 7.3 or 8.0+)
- Download from: https://www.php.net/downloads.php
- Or install XAMPP/WAMP which includes PHP
- **XAMPP Download**: https://www.apachefriends.org/download.html (Recommended for Windows)
- **WAMP Download**: https://www.wampserver.com/en/

### 2. Composer (PHP Dependency Manager)
- Download from: https://getcomposer.org/download/
- After installing, verify with: `composer --version`

### 3. Node.js and NPM
- Download from: https://nodejs.org/
- Install LTS version (includes NPM)
- Verify with: `node --version` and `npm --version`

### 4. MySQL Database
- Included in XAMPP/WAMP
- Or download separately: https://dev.mysql.com/downloads/mysql/
- You'll need phpMyAdmin (included in XAMPP) to create database

### 5. Web Server
- Apache (included in XAMPP/WAMP)
- Or use Laravel's built-in server

---

## Step-by-Step Setup Instructions

### Step 1: Install Prerequisites
1. Install **XAMPP** (includes PHP, MySQL, Apache, phpMyAdmin)
2. Install **Composer** globally
3. Install **Node.js** (LTS version)

### Step 2: Configure Database

1. Start XAMPP and start **Apache** and **MySQL** services
2. Open phpMyAdmin: http://localhost/phpmyadmin
3. Create a new database:
   - Click "New" in left sidebar
   - Database name: `rudra_stories`
   - Collation: `utf8mb4_unicode_ci`
   - Click "Create"

### Step 3: Configure Environment File

1. Open `.env` file in the project root
2. Update these values:
   ```env
   DB_DATABASE=rudra_stories
   DB_USERNAME=root
   DB_PASSWORD=          (leave empty if using XAMPP default)
   APP_URL=http://localhost:8000
   ```

### Step 4: Install PHP Dependencies

Open terminal/command prompt in project folder and run:

```bash
composer install
```

This will download all PHP packages required by Laravel.

### Step 5: Generate Application Key

```bash
php artisan key:generate
```

This generates a unique encryption key for your application.

### Step 6: Install Node.js Dependencies

```bash
npm install
```

This installs frontend dependencies (Laravel Mix, etc.)

### Step 7: Compile Frontend Assets

```bash
npm run dev
```

Or for production:
```bash
npm run production
```

### Step 8: Run Database Migrations

**Important**: This project uses custom database tables. You may need to:
1. Import an existing database SQL file (if available)
2. Or create tables manually based on the models

The standard Laravel migrations can be run with:
```bash
php artisan migrate
```

**Note**: Based on the code, the application uses custom tables like:
- `usersignupinfo` (user registration)
- `thoughts` (thoughts/stories)
- `subs` (subscribers)
- And other custom tables for stories, comments, likes, etc.

If you have a database SQL file, import it through phpMyAdmin.

### Step 9: Set Storage Permissions (if needed)

```bash
php artisan storage:link
```

### Step 10: Start the Development Server

```bash
php artisan serve
```

The application will be available at: **http://localhost:8000**

---

## Quick Setup Commands (Run in Order)

```bash
# 1. Install PHP dependencies
composer install

# 2. Generate app key
php artisan key:generate

# 3. Install Node dependencies
npm install

# 4. Compile assets
npm run dev

# 5. (Optional) Run migrations
php artisan migrate

# 6. Start server
php artisan serve
```

---

## Troubleshooting

### Issue: "composer: command not found"
- **Solution**: Add Composer to your system PATH, or use full path to composer

### Issue: "php: command not found"
- **Solution**: Add PHP to your system PATH
- In XAMPP, PHP is usually at: `C:\xampp\php\php.exe`

### Issue: Database connection error
- **Solution**: 
  - Make sure MySQL is running in XAMPP
  - Check database name, username, password in `.env`
  - Verify database exists in phpMyAdmin

### Issue: "npm: command not found"
- **Solution**: Install Node.js and restart terminal

### Issue: Permission errors (Linux/Mac)
- **Solution**: 
  ```bash
  chmod -R 775 storage bootstrap/cache
  ```

---

## Project Structure

- `app/` - Application code (Models, Controllers)
- `config/` - Configuration files
- `database/` - Migrations and seeders
- `public/` - Public assets (CSS, JS, images)
- `resources/views/` - Blade templates
- `routes/` - Route definitions
- `.env` - Environment configuration (IMPORTANT - contains database credentials)

---

## Additional Notes

1. **Database**: The application uses custom tables. If you have a database backup/SQL file, import it through phpMyAdmin.

2. **File Uploads**: Story images are stored in `public/storyImages/` and user profiles in `public/userProfile/`

3. **Development Mode**: The `.env` file has `APP_DEBUG=true` for development. Change to `false` for production.

---

## Need Help?

If you encounter any issues:
1. Check that all prerequisites are installed
2. Verify database connection settings in `.env`
3. Make sure all services (Apache, MySQL) are running
4. Check Laravel logs in `storage/logs/laravel.log`
