<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);

// Test authentification
$user = \App\Models\Utilisateur::where('login_user', 'admin')->first();

echo "User found: " . ($user ? "YES" : "NO") . "\n";
if ($user) {
    echo "Code: " . $user->code_user . "\n";
    echo "Login: " . $user->login_user . "\n";
    echo "Role: " . $user->role_user . "\n";
    echo "State: " . $user->etat_user . "\n";
    echo "Password hash exists: " . (!empty($user->password_user) ? "YES" : "NO") . "\n";
    echo "Password matches 'admin123': " . (Illuminate\Support\Facades\Hash::check('admin123', $user->password_user) ? "YES" : "NO") . "\n";
}

// Also check total users
$total = \App\Models\Utilisateur::count();
echo "\nTotal users: " . $total . "\n";

// List first 3 users
echo "\nFirst 3 users:\n";
\App\Models\Utilisateur::take(3)->get()->each(function ($u) {
    echo "  - {$u->login_user} ({$u->role_user})\n";
});
