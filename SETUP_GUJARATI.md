# રુદ્રા સ્ટોરીઝ - સેટઅપ માર્ગદર્શિકા

## પ્રોજેક્ટ વિશે
આ Laravel 8 પર આધારિત વેબ એપ્લિકેશન છે જે વાર્તાઓ શેર કરવા અને વાંચવા માટે છે. તે PHP, MySQL, અને Laravel ફ્રેમવર્કનો ઉપયોગ કરે છે.

## જરૂરી સોફ્ટવેર

### 1. PHP (વર્ઝન 7.3 અથવા 8.0+)
- ડાઉનલોડ કરો: https://www.php.net/downloads.php
- અથવા XAMPP/WAMP ઇન્સ્ટોલ કરો જેમાં PHP સામેલ છે
- **XAMPP ડાઉનલોડ**: https://www.apachefriends.org/download.html (Windows માટે ભલામણ)
- **WAMP ડાઉનલોડ**: https://www.wampserver.com/en/

### 2. Composer (PHP Dependency Manager)
- ડાઉનલોડ કરો: https://getcomposer.org/download/
- ઇન્સ્ટોલ પછી ચકાસો: `composer --version`

### 3. Node.js અને NPM
- ડાઉનલોડ કરો: https://nodejs.org/
- LTS વર્ઝન ઇન્સ્ટોલ કરો (NPM સામેલ છે)
- ચકાસો: `node --version` અને `npm --version`

### 4. MySQL ડેટાબેઝ
- XAMPP/WAMP માં સામેલ છે
- અથવા અલગથી ડાઉનલોડ કરો: https://dev.mysql.com/downloads/mysql/
- ડેટાબેઝ બનાવવા માટે phpMyAdmin જોઈશે (XAMPP માં સામેલ છે)

### 5. વેબ સર્વર
- Apache (XAMPP/WAMP માં સામેલ છે)
- અથવા Laravel ના built-in સર્વરનો ઉપયોગ કરો

---

## સેટઅપ ની સ્ટેપ-બાય-સ્ટેપ સૂચનાઓ

### સ્ટેપ 1: જરૂરી સોફ્ટવેર ઇન્સ્ટોલ કરો
1. **XAMPP** ઇન્સ્ટોલ કરો (PHP, MySQL, Apache, phpMyAdmin સામેલ છે)
2. **Composer** globally ઇન્સ્ટોલ કરો
3. **Node.js** (LTS વર્ઝન) ઇન્સ્ટોલ કરો

### સ્ટેપ 2: ડેટાબેઝ કન્ફિગર કરો

1. XAMPP શરૂ કરો અને **Apache** અને **MySQL** સેવાઓ શરૂ કરો
2. phpMyAdmin ખોલો: http://localhost/phpmyadmin
3. નવો ડેટાબેઝ બનાવો:
   - ડાબી બાજુ "New" પર ક્લિક કરો
   - ડેટાબેઝ નામ: `rudra_stories`
   - Collation: `utf8mb4_unicode_ci`
   - "Create" પર ક્લિક કરો

### સ્ટેપ 3: Environment File કન્ફિગર કરો

1. પ્રોજેક્ટ રૂટ માં `.env` ફાઇલ ખોલો
2. આ મૂલ્યો અપડેટ કરો:
   ```env
   DB_DATABASE=rudra_stories
   DB_USERNAME=root
   DB_PASSWORD=          (XAMPP default માટે ખાલી છોડો)
   APP_URL=http://localhost:8000
   ```

### સ્ટેપ 4: PHP Dependencies ઇન્સ્ટોલ કરો

પ્રોજેક્ટ ફોલ્ડર માં terminal/command prompt ખોલો અને ચલાવો:

```bash
composer install
```

આ Laravel દ્વારા જરૂરી બધા PHP packages ડાઉનલોડ કરશે.

### સ્ટેપ 5: Application Key જનરેટ કરો

```bash
php artisan key:generate
```

આ તમારા એપ્લિકેશન માટે unique encryption key જનરેટ કરે છે.

### સ્ટેપ 6: Node.js Dependencies ઇન્સ્ટોલ કરો

```bash
npm install
```

આ frontend dependencies (Laravel Mix, વગેરે) ઇન્સ્ટોલ કરે છે.

### સ્ટેપ 7: Frontend Assets Compile કરો

```bash
npm run dev
```

અથવા production માટે:
```bash
npm run production
```

### સ્ટેપ 8: Database Migrations ચલાવો

**મહત્વપૂર્ણ**: આ પ્રોજેક્ટ custom database tables નો ઉપયોગ કરે છે. તમારે કદાચ:
1. અસ્તિત્વમાં database SQL ફાઇલ import કરવી પડશે (જો ઉપલબ્ધ હોય)
2. અથવા models પર આધારિત tables manually બનાવવા પડશે

Standard Laravel migrations આથી ચલાવી શકાય છે:
```bash
php artisan migrate
```

**નોંધ**: કોડ પર આધારિત, એપ્લિકેશન આ custom tables નો ઉપયોગ કરે છે:
- `usersignupinfo` (user registration)
- `thoughts` (thoughts/stories)
- `subs` (subscribers)
- અને stories, comments, likes, વગેરે માટે અન્ય custom tables

જો તમારી પાસે database SQL ફાઇલ છે, તો તેને phpMyAdmin દ્વારા import કરો.

### સ્ટેપ 9: Storage Permissions સેટ કરો (જો જરૂરી હોય)

```bash
php artisan storage:link
```

### સ્ટેપ 10: Development Server શરૂ કરો

```bash
php artisan serve
```

એપ્લિકેશન આ address પર ઉપલબ્ધ હશે: **http://localhost:8000**

---

## ઝડપી સેટઅપ Commands (ક્રમમાં ચલાવો)

```bash
# 1. PHP dependencies ઇન્સ્ટોલ કરો
composer install

# 2. App key જનરેટ કરો
php artisan key:generate

# 3. Node dependencies ઇન્સ્ટોલ કરો
npm install

# 4. Assets compile કરો
npm run dev

# 5. (વૈકલ્પિક) Migrations ચલાવો
php artisan migrate

# 6. Server શરૂ કરો
php artisan serve
```

---

## સમસ્યા નિવારણ

### સમસ્યા: "composer: command not found"
- **ઉકેલ**: Composer ને તમારા system PATH માં ઉમેરો, અથવા composer નો full path ઉપયોગ કરો

### સમસ્યા: "php: command not found"
- **ઉકેલ**: PHP ને તમારા system PATH માં ઉમેરો
- XAMPP માં, PHP સામાન્ય રીતે આ address પર છે: `C:\xampp\php\php.exe`

### સમસ્યા: Database connection error
- **ઉકેલ**: 
  - XAMPP માં MySQL ચાલી રહ્યું છે તેની ખાતરી કરો
  - `.env` માં database name, username, password તપાસો
  - phpMyAdmin માં database અસ્તિત્વમાં છે તેની ખાતરી કરો

### સમસ્યા: "npm: command not found"
- **ઉકેલ**: Node.js ઇન્સ્ટોલ કરો અને terminal restart કરો

### સમસ્યા: Permission errors (Linux/Mac)
- **ઉકેલ**: 
  ```bash
  chmod -R 775 storage bootstrap/cache
  ```

---

## પ્રોજેક્ટ સ્ટ્રક્ચર

- `app/` - Application code (Models, Controllers)
- `config/` - Configuration files
- `database/` - Migrations અને seeders
- `public/` - Public assets (CSS, JS, images)
- `resources/views/` - Blade templates
- `routes/` - Route definitions
- `.env` - Environment configuration (મહત્વપૂર્ણ - database credentials સામેલ છે)

---

## વધારાની નોંધો

1. **ડેટાબેઝ**: એપ્લિકેશન custom tables નો ઉપયોગ કરે છે. જો તમારી પાસે database backup/SQL ફાઇલ છે, તો તેને phpMyAdmin દ્વારા import કરો.

2. **ફાઇલ અપલોડ્સ**: Story images `public/storyImages/` માં અને user profiles `public/userProfile/` માં સંગ્રહિત છે

3. **Development Mode**: `.env` ફાઇલમાં `APP_DEBUG=true` development માટે છે. Production માટે `false` માં બદલો.

---

## મદદ જોઈએ છે?

જો કોઈ સમસ્યા આવે:
1. તપાસો કે બધા prerequisites ઇન્સ્ટોલ થયેલા છે
2. `.env` માં database connection settings verify કરો
3. ખાતરી કરો કે બધી સેવાઓ (Apache, MySQL) ચાલી રહી છે
4. Laravel logs તપાસો: `storage/logs/laravel.log`
