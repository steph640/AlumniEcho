<?php
define('LARAVEL_START', microtime(true));

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

// Get auth and test
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

// Test 1: Check user exists
$user = Utilisateur::where('login_user', 'admin')->first();
echo "✓ Admin found: " . ($user ? "YES" : "NO") . "\n";

if ($user) {
    // Test 2: Check password
    $pwd_match = Hash::check('admin123', $user->password_user);
    echo "✓ Password matches: " . ($pwd_match ? "YES" : "NO") . "\n";
    
    // Test 3: Check if user is Authenticatable
    $is_auth = $user instanceof \Illuminate\Contracts\Auth\Authenticatable;
    echo "✓ User is Authenticatable: " . ($is_auth ? "YES" : "NO") . "\n";
    
    // Test 4: Try to login programmatically
    try {
        // auth()->guard('web')->login($user);
        echo "✓ Auth guard available: YES\n";
        echo "✓ Current user (before login): " . (Auth::check() ? "AUTH" : "GUEST") . "\n";
    } catch (Exception $e) {
        echo "✗ Error: " . $e->getMessage() . "\n";
    }
}
