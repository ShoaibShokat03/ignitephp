-- Migration: Add default admin user
-- Run this script to create an admin user in the database
-- Default credentials: username='admin', password='admin123'
-- IMPORTANT: Change the password after first login!

-- Note: The password_hash below is for 'admin123' generated using PHP password_hash()
-- If you need to generate a new hash, use PHP: password_hash('your_password', PASSWORD_DEFAULT)
-- Or use the PHP script: php database/scripts/add_admin.php

-- Check if admin already exists
SET @admin_exists = (SELECT COUNT(*) FROM users WHERE username = 'admin');

-- Only insert if admin doesn't exist
INSERT INTO users (
    username,
    password_hash,
    role,
    full_name,
    email,
    phone,
    created_at,
    updated_at
) 
SELECT 
    'admin',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: admin123
    'admin',
    'Administrator',
    'admin@clinic.com',
    NULL,
    NOW(),
    NOW()
WHERE @admin_exists = 0;

-- To verify the admin was created:
-- SELECT id, username, role, full_name, email FROM users WHERE username = 'admin';

