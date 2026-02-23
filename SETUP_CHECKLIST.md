# Setup Checklist - Rudra Stories

## ✅ Installation Status Check

### Step 1: Software Installation (સોફ્ટવેર ઇન્સ્ટોલેશન)

- [ ] **XAMPP** installed અને running
  - Check: http://localhost/phpmyadmin ખુલે છે?
  - Apache અને MySQL બંને Start કરેલા છે?

- [ ] **Composer** installed
  - Check: Open terminal અને type: `composer --version`
  - જો error આવે તો Composer install કરો: https://getcomposer.org/

- [ ] **Node.js** installed
  - Check: Open terminal અને type: `node --version` અને `npm --version`
  - જો error આવે તો Node.js install કરો: https://nodejs.org/

- [ ] **PHP** installed અને PATH માં છે
  - Check: Open terminal અને type: `php --version`
  - જો error આવે તો XAMPP ના PHP ને PATH માં add કરો

---

### Step 2: Project Setup (પ્રોજેક્ટ સેટઅપ)

- [ ] **.env file** exists અને configured
  - File location: `C:\Dhruvil2\rudraStories\.env`
  - Database settings verify કરો:
    ```
    DB_DATABASE=rudra_stories
    DB_USERNAME=root
    DB_PASSWORD=        (XAMPP માં ખાલી)
    ```

- [ ] **PHP Dependencies** installed
  - Run: `composer install`
  - Check: `vendor/` folder exists

- [ ] **Application Key** generated
  - Run: `php artisan key:generate`
  - Check: `.env` file માં `APP_KEY=` ની પાછળ value છે

- [ ] **Node.js Dependencies** installed
  - Run: `npm install`
  - Check: `node_modules/` folder exists

- [ ] **Frontend Assets** compiled
  - Run: `npm run dev`
  - Check: `public/css/` અને `public/js/` માં compiled files છે

---

### Step 3: Database Setup (ડેટાબેઝ સેટઅપ)

- [ ] **Database created**
  - phpMyAdmin માં જાઓ: http://localhost/phpmyadmin
  - Database name: `rudra_stories` બનાવેલ છે?

- [ ] **Database tables imported**
  - `database_schema.sql` file import કરેલ છે?
  - Check: phpMyAdmin માં આ tables છે:
    - ✅ usersignupinfo
    - ✅ all_stories
    - ✅ story_type
    - ✅ comment_section
    - ✅ story_parts
    - ✅ stry_part_comments
    - ✅ thoughts
    - ✅ subs
    - ✅ helpquery

---

### Step 4: File Permissions (જરૂરી હોય તો)

- [ ] **Storage folder** writable
  - Check: `storage/` folder exists
  - Windows માં સામાન્ય રીતે issue નથી

- [ ] **Public folders** exist
  - Check: `public/storyImages/` folder exists
  - Check: `public/userProfile/` folder exists

---

### Step 5: Final Verification (અંતિમ તપાસ)

- [ ] **Server starts** without errors
  - Run: `php artisan serve`
  - Check: No errors in terminal

- [ ] **Application loads** in browser
  - Visit: http://localhost:8000
  - Check: Homepage loads properly

- [ ] **Database connection** works
  - Check: No database errors in browser
  - Check: `storage/logs/laravel.log` માં errors નથી

---

## Quick Test Commands

તમારા terminal માં આ commands run કરીને verify કરો:

```powershell
# 1. Check PHP
php --version

# 2. Check Composer
composer --version

# 3. Check Node.js
node --version
npm --version

# 4. Check Laravel
php artisan --version

# 5. Check database connection
php artisan migrate:status
```

---

## Common Issues & Solutions

### Issue: "composer: command not found"
**Solution**: 
- Composer install કરો: https://getcomposer.org/download/
- અથવા full path use કરો: `C:\ProgramData\ComposerSetup\bin\composer.bat install`

### Issue: "php: command not found"
**Solution**: 
- XAMPP ના PHP ને PATH માં add કરો
- XAMPP PHP path: `C:\xampp\php\`
- System Environment Variables માં add કરો

### Issue: "Database connection refused"
**Solution**: 
- XAMPP માં MySQL Start કરેલ છે?
- `.env` file માં database credentials તપાસો
- Database `rudra_stories` બનાવેલ છે?

### Issue: "Class not found" errors
**Solution**: 
- Run: `composer dump-autoload`
- Check: `vendor/` folder exists

### Issue: "npm: command not found"
**Solution**: 
- Node.js install કરો: https://nodejs.org/
- Terminal restart કરો

---

## Next Steps After Setup

1. ✅ બધું setup થઈ ગયું
2. ✅ Database import કરી દીધું
3. ✅ Server running છે: `php artisan serve`
4. ✅ Browser માં test કર્યું: http://localhost:8000

**તો હવે તમે application use કરી શકો છો! 🎉**

---

## Need Help?

જો કોઈ issue આવે:
1. `storage/logs/laravel.log` file check કરો
2. Browser console માં errors check કરો
3. Terminal માં error messages read કરો
