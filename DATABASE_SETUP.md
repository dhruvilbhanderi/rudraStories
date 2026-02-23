# Database Setup Guide - Rudra Stories

## Database Tables List (સંપૂર્ણ ડેટાબેઝ ટેબલ્સ)

આ application માં નીચેની tables જરૂરી છે:

### Main Tables (મુખ્ય ટેબલ્સ):

1. **usersignupinfo** - User registration અને profile information
   - Columns: S_No, UserName, Password, Email, UserMobile, status, uidenkk, images, created_at, updated_at

2. **all_stories** - Main stories (મુખ્ય વાર્તાઓ)
   - Columns: story_id, story_heading, story_type, story_desc, written_by, main_story, stry_likes, images, view, story_identy, post_time, created_at, updated_at

3. **story_type** - Story categories/types (વાર્તાના પ્રકાર)
   - Columns: sno, Story_type, type_iden, view, created_at, updated_at

4. **comment_section** - Comments on main stories (મુખ્ય વાર્તાઓ પર comments)
   - Columns: id, comment_by, comment, stry_iden, created_at, updated_at

5. **story_parts** - Parts/chapters of stories (વાર્તાના ભાગો/અધ્યાયો)
   - Columns: id, part_no, mainstry_id, story_heading, story_desc, story_identy, story_type, stry_likes, view, created_at, updated_at

6. **stry_part_comments** - Comments on story parts (વાર્તાના ભાગો પર comments)
   - Columns: id, comment_by, comment, part_stry_identy, created_at, updated_at

7. **thoughts** - Thoughts/quotes displayed on homepage (હોમપેજ પર દેખાડવામાં આવતા વિચારો)
   - Columns: id, Mainthought, thought_iden, created_at, updated_at

8. **subs** - Subscribers list (સબ્સ્ક્રાઇબર્સની યાદી)
   - Columns: id, Subscriber_Identy, Subscriber_Email, created_at, updated_at

9. **helpquery** - Help/contact form queries (મદદ/સંપર્ક ફોર્મ queries)
   - Columns: id, name, email, msg, created_at, updated_at

### Laravel Standard Tables:

10. **password_resets** - Password reset tokens
11. **failed_jobs** - Failed queue jobs
12. **personal_access_tokens** - API tokens

---

## Database Import કેવી રીતે કરવું (How to Import Database)

### Method 1: phpMyAdmin માં Import કરવું (Recommended)

1. **XAMPP શરૂ કરો:**
   - Apache અને MySQL બંને Start કરો

2. **phpMyAdmin ખોલો:**
   - Browser માં જાઓ: http://localhost/phpmyadmin

3. **Database બનાવો:**
   - ડાબી બાજુ "New" પર ક્લિક કરો
   - Database name: `rudra_stories`
   - Collation: `utf8mb4_unicode_ci`
   - "Create" પર ક્લિક કરો

4. **SQL File Import કરો:**
   - `rudra_stories` database select કરો
   - Top menu માં "Import" tab પર ક્લિક કરો
   - "Choose File" પર ક્લિક કરો
   - `database_schema.sql` file select કરો
   - "Go" button પર ક્લિક કરો
   - Success message આવશે

### Method 2: Command Line માં Import કરવું

```bash
# MySQL command line માં:
mysql -u root -p rudra_stories < database_schema.sql
```

---

## Database Structure Details

### Table: usersignupinfo
- **Purpose**: User accounts અને profile information
- **Key Fields**:
  - `UserName` - Unique username
  - `Password` - Hashed password
  - `Email` - Unique email
  - `uidenkk` - User identity hash
  - `images` - Profile picture filename

### Table: all_stories
- **Purpose**: Main stories storage
- **Key Fields**:
  - `story_heading` - Story title
  - `story_type` - Reference to story_type.sno
  - `story_desc` - Short description
  - `main_story` - Full story content
  - `story_identy` - Unique story identifier (hash)
  - `stry_likes` - Like count
  - `view` - View count

### Table: story_type
- **Purpose**: Story categories (Romance, Horror, Comedy, etc.)
- **Key Fields**:
  - `Story_type` - Category name
  - `type_iden` - Unique type identifier

### Table: comment_section
- **Purpose**: Comments on main stories
- **Key Fields**:
  - `comment_by` - Username (references usersignupinfo.UserName)
  - `stry_iden` - Story identifier (references all_stories.story_identy)

### Table: story_parts
- **Purpose**: Multi-part stories (chapters)
- **Key Fields**:
  - `mainstry_id` - Reference to all_stories.story_id
  - `part_no` - Part number
  - `story_identy` - Unique part identifier

### Table: stry_part_comments
- **Purpose**: Comments on story parts
- **Key Fields**:
  - `part_stry_identy` - Reference to story_parts.story_identy
  - `comment_by` - Username

---

## Verification (તપાસ)

Import પછી તપાસો કે બધી tables બની ગઈ છે:

```sql
SHOW TABLES;
```

આ command બધી table names દેખાડશે. તમારે આ tables જોવી જોઈએ:
- usersignupinfo
- all_stories
- story_type
- comment_section
- story_parts
- stry_part_comments
- thoughts
- subs
- helpquery
- password_resets
- failed_jobs
- personal_access_tokens

---

## Sample Data (વૈકલ્પિક)

જો તમે test માટે sample data ઇમ્પોર્ટ કરવા માંગો છો, તો `database_schema.sql` file ના અંતમાં sample INSERT statements છે જેને uncomment કરી શકો છો.

---

## Troubleshooting

### Error: "Table already exists"
- **Solution**: પહેલા existing tables drop કરો અથવા `CREATE TABLE IF NOT EXISTS` statement use કરો (જે already file માં છે)

### Error: "Access denied"
- **Solution**: `.env` file માં database credentials તપાસો:
  ```
  DB_USERNAME=root
  DB_PASSWORD=        (XAMPP માં સામાન્ય રીતે ખાલી)
  ```

### Error: "Unknown database"
- **Solution**: પહેલા database `rudra_stories` બનાવો phpMyAdmin માં

---

## Next Steps

Database import પછી:

1. `.env` file verify કરો કે database credentials સાચા છે
2. Application run કરો: `php artisan serve`
3. Browser માં જાઓ: http://localhost:8000

---

## Notes

- બધી tables `utf8mb4_unicode_ci` collation use કરે છે (Gujarati અને other languages support માટે)
- Foreign keys optional છે (commented out in SQL file)
- Timestamps (`created_at`, `updated_at`) Laravel automatically manage કરે છે
