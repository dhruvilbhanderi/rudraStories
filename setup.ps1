# Rudra Stories - Quick Setup Script for Windows
# Run this script in PowerShell: .\setup.ps1

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Rudra Stories - Setup Script" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Check if .env exists
if (-not (Test-Path .env)) {
    Write-Host "Creating .env file..." -ForegroundColor Yellow
    if (Test-Path .env.example) {
        Copy-Item .env.example .env
        Write-Host ".env file created from .env.example" -ForegroundColor Green
    } else {
        Write-Host "ERROR: .env.example not found!" -ForegroundColor Red
        exit 1
    }
} else {
    Write-Host ".env file already exists" -ForegroundColor Green
}

Write-Host ""
Write-Host "Step 1: Installing PHP dependencies (Composer)..." -ForegroundColor Yellow
Write-Host "----------------------------------------" -ForegroundColor Gray
try {
    composer install
    Write-Host "Composer dependencies installed successfully!" -ForegroundColor Green
} catch {
    Write-Host "ERROR: Failed to install Composer dependencies" -ForegroundColor Red
    Write-Host "Make sure Composer is installed and in your PATH" -ForegroundColor Yellow
    exit 1
}

Write-Host ""
Write-Host "Step 2: Generating application key..." -ForegroundColor Yellow
Write-Host "----------------------------------------" -ForegroundColor Gray
try {
    php artisan key:generate
    Write-Host "Application key generated successfully!" -ForegroundColor Green
} catch {
    Write-Host "ERROR: Failed to generate application key" -ForegroundColor Red
    Write-Host "Make sure PHP is installed and in your PATH" -ForegroundColor Yellow
    exit 1
}

Write-Host ""
Write-Host "Step 3: Installing Node.js dependencies..." -ForegroundColor Yellow
Write-Host "----------------------------------------" -ForegroundColor Gray
try {
    npm install
    Write-Host "Node.js dependencies installed successfully!" -ForegroundColor Green
} catch {
    Write-Host "ERROR: Failed to install Node.js dependencies" -ForegroundColor Red
    Write-Host "Make sure Node.js and NPM are installed" -ForegroundColor Yellow
    exit 1
}

Write-Host ""
Write-Host "Step 4: Compiling frontend assets..." -ForegroundColor Yellow
Write-Host "----------------------------------------" -ForegroundColor Gray
try {
    npm run dev
    Write-Host "Frontend assets compiled successfully!" -ForegroundColor Green
} catch {
    Write-Host "WARNING: Failed to compile assets. You can run 'npm run dev' manually later." -ForegroundColor Yellow
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Setup Complete!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "IMPORTANT NEXT STEPS:" -ForegroundColor Yellow
Write-Host "1. Create database 'rudra_stories' in phpMyAdmin" -ForegroundColor White
Write-Host "2. Update .env file with your database credentials:" -ForegroundColor White
Write-Host "   - DB_DATABASE=rudra_stories" -ForegroundColor Gray
Write-Host "   - DB_USERNAME=root" -ForegroundColor Gray
Write-Host "   - DB_PASSWORD=(your password)" -ForegroundColor Gray
Write-Host "3. If you have a database SQL file, import it through phpMyAdmin" -ForegroundColor White
Write-Host "4. Run migrations (optional): php artisan migrate" -ForegroundColor White
Write-Host "5. Start the server: php artisan serve" -ForegroundColor White
Write-Host ""
Write-Host "Then visit: http://localhost:8000" -ForegroundColor Cyan
Write-Host ""
