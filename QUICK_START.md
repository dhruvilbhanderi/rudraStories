# Quick Start Guide - Rudra Stories

## ઝડપી શરૂઆત (Quick Start in Gujarati)

### પ્રથમ વખત Setup માટે:

1. **જરૂરી સોફ્ટવેર ઇન્સ્ટોલ કરો:**
   - XAMPP (PHP + MySQL + Apache): https://www.apachefriends.org/
   - Composer: https://getcomposer.org/
   - Node.js: https://nodejs.org/

2. **XAMPP શરૂ કરો:**
   - Apache અને MySQL બંને Start કરો

3. **ડેટાબેઝ બનાવો:**
   - Browser માં જાઓ: http://localhost/phpmyadmin
   - નવો database બનાવો: `rudra_stories`

4. **PowerShell માં આ commands ચલાવો:**

```powershell
# પ્રોજેક્ટ ફોલ્ડર માં જાઓ
cd C:\Dhruvil2\rudraStories

# Automatic setup script ચલાવો
.\setup.ps1
```

અથવા manually:

```powershell
# 1. .env file બનાવો (જો ન હોય તો)
Copy-Item .env.example .env

# 2. PHP dependencies ઇન્સ્ટોલ કરો
composer install

# 3. Application key જનરેટ કરો
php artisan key:generate

# 4. Node.js dependencies ઇન્સ્ટોલ કરો
npm install

# 5. Frontend assets compile કરો
npm run dev

# 6. Server શરૂ કરો
php artisan serve
```

5. **Browser માં જાઓ:** http://localhost:8000

---

## મહત્વપૂર્ણ નોંધો:

### Database Setup:
- `.env` ફાઇલ ખોલો અને database credentials update કરો:
  ```
  DB_DATABASE=rudra_stories
  DB_USERNAME=root
  DB_PASSWORD=        (XAMPP માં સામાન્ય રીતે ખાલી)
  ```

### જો Database SQL File હોય:
- phpMyAdmin માં જાઓ
- `rudra_stories` database select કરો
- "Import" tab પર ક્લિક કરો
- SQL file select કરો અને import કરો

### જો Error આવે:
- તપાસો કે XAMPP માં Apache અને MySQL બંને running છે
- `.env` ફાઇલ માં database name અને credentials સાચા છે કે નહીં
- `storage/logs/laravel.log` માં error details જુઓ

---

## વધુ માહિતી:
- વિગતવાર setup માટે: `SETUP.md` અથવા `SETUP_GUJARATI.md` જુઓ
