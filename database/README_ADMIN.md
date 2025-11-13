# Adding Admin User to Database

This guide explains how to add an admin user to the clinic management system database.

## Method 1: Using PHP Script (Recommended)

The easiest way to add an admin user is using the PHP script, which automatically hashes the password.

### Windows:
```batch
cd backend
php database\scripts\add_admin.php
```

Or use the batch file:
```batch
database\scripts\add_admin.bat
```

### Linux/Mac:
```bash
cd backend
php database/scripts/add_admin.php
```

### Custom Credentials:
```bash
php database/scripts/add_admin.php admin mypassword admin@clinic.com "Admin Name"
```

**Default credentials:**
- Username: `admin`
- Password: `admin123`
- Email: `admin@clinic.com`
- Full Name: `Administrator`

## Method 2: Using SQL Script

Run the SQL migration script directly in your MySQL client:

```sql
-- Run this file:
backend/database/migrations/add_admin_user.sql
```

Or execute in MySQL:
```bash
mysql -u root -p your_database < backend/database/migrations/add_admin_user.sql
```

**Default credentials:**
- Username: `admin`
- Password: `admin123`

## Method 3: Manual SQL Insert

If you need to create an admin with a custom password hash:

```sql
INSERT INTO users (
    username,
    password_hash,
    role,
    full_name,
    email,
    created_at,
    updated_at
) VALUES (
    'admin',
    '$2y$10$YOUR_PASSWORD_HASH_HERE',
    'admin',
    'Administrator',
    'admin@clinic.com',
    NOW(),
    NOW()
);
```

To generate a password hash, use PHP:
```php
<?php
echo password_hash('your_password', PASSWORD_DEFAULT);
?>
```

## Verify Admin User

After creating the admin, verify it was created:

```sql
SELECT id, username, role, full_name, email, created_at 
FROM users 
WHERE username = 'admin';
```

## Security Note

⚠️ **IMPORTANT:** Change the default password (`admin123`) immediately after first login!

## Troubleshooting

1. **"User already exists" error:**
   - The admin user already exists in the database
   - To update the password, use the UPDATE statement or delete and recreate

2. **Database connection error:**
   - Make sure your `.env` file in `backend/config/` has correct database credentials
   - Verify the database exists and is accessible

3. **Password hash issues:**
   - Always use PHP's `password_hash()` function
   - Never store plain text passwords
   - The hash must start with `$2y$` for bcrypt

