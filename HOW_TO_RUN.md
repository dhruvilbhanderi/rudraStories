# 🚀 Server કેવી રીતે Start કરવું - Step by Step Guide

## Quick Start (ઝડપી શરૂઆત)

### Step 1: Database Import (પહેલા આ કરો!)

**⚠️ મહત્વપૂર્ણ:** Server start કરતા પહેલા database import કરવું જરૂરી છે!

1. **XAMPP Control Panel ખોલો**
   - Apache અને **MySQL બંને Start** કરો
   - બંને Green બનવા જોઈએ

2. **phpMyAdmin ખોલો**
   - Browser માં જાઓ: **http://localhost/phpmyadmin**

3. **Database બનાવો**
   - ડાબી બાજુ "New" button પર ક્લિક કરો
   - Database name: `rudra_stories`
   - Collation: `utf8mb4_unicode_ci` select કરો
   - "Create" button પર ક્લિક કરો

4. **SQL File Import કરો**
   - `rudra_stories` database select કરો (ડાબી બાજુ)
   - Top menu માં "Import" tab પર ક્લિક કરો
   - "Choose File" button પર ક્લિક કરો
   - `database_schema.sql` file select કરો
   - "Go" button પર ક્લિક કરો
   - Success message આવશે ✅

---

### Step 2: Server Start કરો

#### Method 1: PowerShell/Terminal માં (Recommended)

1. **PowerShell ખોલો**
   - Windows Key + X
   - "Windows PowerShell" select કરો
   - અથવા Start Menu માં "PowerShell" search કરો

2. **Project Folder માં જાઓ**
   ```powershell
   cd C:\Dhruvil2\rudraStories
   ```

3. **Server Start કરો**
   ```powershell
   php artisan serve
   ```

4. **Output આવશે:**
   ```
   INFO  Server running on [http://127.0.0.1:8000]
   
   Press Ctrl+C to stop the server
   ```

5. **Browser માં જાઓ:**
   - **http://localhost:8000**
   - અથવા **http://127.0.0.1:8000**

---

#### Method 2: Custom Port પર Start કરવું

જો 8000 port busy હોય, તો બીજો port use કરો:

```powershell
php artisan serve --port=8080
```

પછી browser માં જાઓ: **http://localhost:8080**

---

#### Method 3: Specific Host પર Start કરવું

```powershell
php artisan serve --host=0.0.0.0 --port=8000
```

---

## Server Stop કેવી રીતે કરવું

Server stop કરવા માટે:
- Terminal/PowerShell માં **Ctrl + C** press કરો

---

## Complete Setup Checklist

તમારા માટે checklist:

- [ ] **XAMPP installed** અને running
- [ ] **Database `rudra_stories` created** (phpMyAdmin માં)
- [ ] **`database_schema.sql` imported** (phpMyAdmin માં)
- [ ] **`.env` file configured** (DB credentials સાચા છે)
- [ ] **PHP dependencies installed** (`composer install` ✅ done)
- [ ] **Application key generated** (`php artisan key:generate` ✅ done)
- [ ] **Node.js dependencies installed** (`npm install` ✅ done)
- [ ] **Frontend assets compiled** (`npm run dev` ✅ done)
- [ ] **Server started** (`php artisan serve`)

---

## Common Issues & Solutions

### Issue 1: "Port 8000 is already in use"

**Solution:**
```powershell
# બીજો port use કરો
php artisan serve --port=8080
```

### Issue 2: "Database connection refused"

**Solution:**
1. XAMPP માં MySQL Start કરેલ છે?
2. `.env` file માં credentials તપાસો:
   ```
   DB_HOST=127.0.0.1
   DB_DATABASE=rudra_stories
   DB_USERNAME=root
   DB_PASSWORD=
   ```

### Issue 3: "php: command not found"

**Solution:**
- XAMPP ના PHP ને PATH માં add કરો
- XAMPP PHP path: `C:\xampp\php\`
- અથવા full path use કરો:
  ```powershell
  C:\xampp\php\php.exe artisan serve
  ```

### Issue 4: "No application encryption key"

**Solution:**
```powershell
php artisan key:generate
```

### Issue 5: Page shows errors

**Solution:**
- `storage/logs/laravel.log` file check કરો
- Database import થયેલ છે?
- `.env` file માં settings સાચા છે?

---

## Step-by-Step Visual Guide

### 1. XAMPP Start કરો
```
XAMPP Control Panel
├── Apache [Start] ✅
└── MySQL [Start] ✅
```

### 2. phpMyAdmin માં Database Import
```
Browser: http://localhost/phpmyadmin
├── New (ડાબી બાજુ)
├── Database name: rudra_stories
├── Create
├── Import tab
├── Choose File: database_schema.sql
└── Go ✅
```

### 3. PowerShell માં Server Start
```
PowerShell:
├── cd C:\Dhruvil2\rudraStories
└── php artisan serve
```

### 4. Browser માં Open કરો
```
Browser:
└── http://localhost:8000
```

---

## Quick Reference Commands

```powershell
# Project folder માં જાઓ
cd C:\Dhruvil2\rudraStories

# Server start (default port 8000)
php artisan serve

# Server start (custom port)
php artisan serve --port=8080

# Server stop
Ctrl + C

# Check Laravel version
php artisan --version

# Check routes
php artisan route:list
```

---

## What Happens When Server Starts?

1. **Laravel development server** start થાય છે
2. **Port 8000** પર listen કરે છે
3. **Application** ready થાય છે
4. **Browser** માં access કરી શકો છો

---

## Next Steps After Server Starts

1. ✅ Server running છે
2. ✅ Browser માં જાઓ: http://localhost:8000
3. ✅ Homepage load થવી જોઈએ
4. ✅ Test કરો:
   - Sign Up
   - Log In
   - Stories view
   - Comments

---

## Tips

- **Server running રાખો** જ્યારે તમે development કરો છો
- **Terminal window open રાખો** - server logs ત્યાં દેખાશે
- **Ctrl + C** press કરીને server stop કરો જ્યારે done
- **Database changes** કર્યા પછી server restart કરવાની જરૂર નથી

---

## Need Help?

જો કોઈ issue આવે:
1. `storage/logs/laravel.log` check કરો
2. Terminal માં error messages read કરો
3. Database connection verify કરો

**Happy Coding! 🎉**
