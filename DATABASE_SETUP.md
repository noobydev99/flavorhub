# FlavorHub Database Setup Guide

## Quick Setup (Recommended)

### Option 1: Using Setup Script

1. **Start XAMPP**
   - Open XAMPP Control Panel
   - Click "Start" next to MySQL

2. **Run Setup Script**
   - Open browser: `http://localhost/my%20project/setup.php`
   - Script will automatically:
     - ✅ Create `my_project` database
     - ✅ Import schema from `database.sql`
     - ✅ Create all required tables
     - ✅ Verify connection

3. **Test Connection**
   - Open: `http://localhost/my%20project/test_db_connection.php`
   - Should show ✅ for all checks

---

## Manual Setup (If Script Fails)

### Step 1: Start MySQL
- Open XAMPP Control Panel
- Click "Start" next to Apache and MySQL

### Step 2: Create Database
- Open phpMyAdmin: `http://localhost/phpmyadmin`
- Click "New" in left sidebar
- Database name: `my_project`
- Charset: `utf8mb4_unicode_ci`
- Click "Create"

### Step 3: Import Schema
- Select `my_project` database
- Go to "Import" tab
- Click "Choose File"
- Select: `d:\xampp\htdocs\my project\database.sql`
- Click "Go"

### Step 4: Verify
- All tables should now appear in the database

---

## Configuration

**File:** `config/database.php`

```php
'host'     => 'localhost',      // MySQL server
'dbname'   => 'my_project',     // Database name
'username' => 'root',           // MySQL username
'password' => '',               // MySQL password (empty for XAMPP)
'charset'  => 'utf8mb4',        // Character encoding
```

---

## Database Tables

| Table | Purpose |
|-------|---------|
| `users` | Customer & admin accounts |
| `foods` | Menu items (legacy) |
| `recipes` | Admin-created recipes |
| `categories` | Recipe categories |
| `orders` | Customer orders |
| `order_items` | Items in each order |
| `comments` | Recipe comments |
| `settings` | Site settings |

---

## Seed Data Included

**Users:**
- Admin: admin@flavorhub.com / admin123
- Customer: customer@flavorhub.com / password123

**Foods:** 13 items (pizzas, burgers, rice, drinks, desserts)

**Recipes:** Empty (add via admin panel)

---

## Testing

### Connection Test
```php
<?php
require 'config/autoload.php';
use FlavorHub\DataAccess\Database;
$db = Database::getConnection();
echo "Connected!";
?>
```

### Access Admin Panel
- URL: `http://localhost/my%20project/admin/dashboard.php`
- Email: `admin@flavorhub.com`
- Password: `admin123`

---

## Troubleshooting

### "Connection refused"
- MySQL not running
- Solution: Start MySQL in XAMPP Control Panel

### "Database not found"
- database.sql not imported
- Solution: Run setup.php or manually import in phpMyAdmin

### "Access denied"
- Wrong username/password
- Solution: Check config/database.php

### "Character set error"
- Database charset mismatch
- Solution: Recreate database with `utf8mb4_unicode_ci`

---

## Useful Commands (phpMyAdmin SQL)

```sql
-- Check tables
SHOW TABLES;

-- Count records
SELECT COUNT(*) FROM users;
SELECT COUNT(*) FROM recipes;
SELECT COUNT(*) FROM orders;

-- List users
SELECT id, fullname, email FROM users;

-- View orders
SELECT * FROM orders ORDER BY created_at DESC;

-- Check order items
SELECT * FROM order_items WHERE order_id = 1;
```
