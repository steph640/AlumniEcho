<?php
define('LARAVEL_START', microtime(true));

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$app->make('db');

// Test database connection
try {
    $test = \Illuminate\Support\Facades\DB::connection()->getPdo();
    echo "✓ Database connection OK\n";
} catch (Exception $e) {
    echo "✗ Database connection FAILED: " . $e->getMessage() . "\n";
    exit(1);
}

// Check if users table exists
try {
    $count = \Illuminate\Support\Facades\DB::table('utilisateurs')->count();
    echo "✓ Total utilisateurs: $count\n";
} catch (Exception $e) {
    echo "✗ Query failed: " . $e->getMessage() . "\n";
    exit(1);
}

// Try to find admin
try {
    $admin = \Illuminate\Support\Facades\DB::table('utilisateurs')->where('login_user', 'admin')->first();
    if ($admin) {
        echo "✓ Admin user found\n";
        echo "  - Code: {$admin->code_user}\n";
        echo "  - Login: {$admin->login_user}\n";
        echo "  - Role: {$admin->role_user}\n";
        echo "  - State: {$admin->etat_user}\n";
        echo "  - Password hash: " . substr($admin->password_user, 0, 30) . "...\n";
        
        // Test password
        if (\Illuminate\Support\Facades\Hash::check('admin123', $admin->password_user)) {
            echo "  - Password 'admin123' matches ✓\n";
        } else {
            echo "  - Password 'admin123' does NOT match ✗\n";
        }
    } else {
        echo "✗ Admin user NOT found\n";
        echo "Available logins:\n";
        $users = \Illuminate\Support\Facades\DB::table('utilisateurs')->limit(5)->get();
        foreach ($users as $user) {
            echo "  - {$user->login_user} ({$user->role_user})\n";
        }
    }
} catch (Exception $e) {
    echo "✗ Query failed: " . $e->getMessage() . "\n";
    exit(1);
}
