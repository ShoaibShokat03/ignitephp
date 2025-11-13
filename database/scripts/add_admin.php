<?php
/**
 * Script to add admin user to database
 * 
 * Usage: php add_admin.php [username] [password] [email]
 * 
 * Example: php add_admin.php admin admin123 admin@clinic.com
 */

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Database\Db;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../config/');
$dotenv->load();

// Get command line arguments or use defaults
$username = $argv[1] ?? 'admin';
$password = $argv[2] ?? 'admin123';
$email = $argv[3] ?? 'admin@clinic.com';
$fullName = $argv[4] ?? 'Administrator';

try {
    // Connect to database
    $db = new Db();
    $db->connect();
    $conn = $db->getConnection();

    // Hash password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Check if admin already exists
    $usernameEscaped = $db->escape($username);
    $result = $db->query("SELECT id FROM users WHERE username = '{$usernameEscaped}'");
    
    if ($result && $result->num_rows > 0) {
        echo "❌ User '{$username}' already exists!\n";
        echo "   To update password, use: UPDATE users SET password_hash = ? WHERE username = ?;\n";
        exit(1);
    }

    // Insert admin user
    $fullNameEscaped = $db->escape($fullName);
    $emailEscaped = $db->escape($email);
    $passwordHashEscaped = $db->escape($passwordHash);
    
    $sql = "INSERT INTO users (
        username,
        password_hash,
        role,
        full_name,
        email,
        created_at,
        updated_at
    ) VALUES (
        '{$usernameEscaped}',
        '{$passwordHashEscaped}',
        'admin',
        '{$fullNameEscaped}',
        '{$emailEscaped}',
        NOW(),
        NOW()
    )";

    $db->query($sql);
    $userId = $db->insert_id();

    echo "✅ Admin user created successfully!\n";
    echo "   ID: {$userId}\n";
    echo "   Username: {$username}\n";
    echo "   Password: {$password}\n";
    echo "   Email: {$email}\n";
    echo "   Full Name: {$fullName}\n";
    echo "   Role: admin\n";
    echo "\n⚠️  IMPORTANT: Change the password after first login!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}

