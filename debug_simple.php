<?php
define('LARAVEL_START', microtime(true));

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

// Get kernel
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);

// Try PDO directly
try {
    $pdo = new PDO(
        'mysql:host=127.0.0.1;dbname=alumniecho',
        'root',
        ''
    );
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM utilisateurs");
    $count = $stmt->fetchColumn();
    echo "✓ Database connected, Total users: $count\n";
    
    // Find admin
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE login_user = ?");
    $stmt->execute(['admin']);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($admin) {
        echo "✓ Admin found:\n";
        echo "  - Login: {$admin['login_user']}\n";
        echo "  - Role: {$admin['role_user']}\n";
        echo "  - State: {$admin['etat_user']}\n";
    } else {
        echo "✗ Admin NOT found\n";
        $stmt = $pdo->query("SELECT login_user, role_user FROM utilisateurs LIMIT 3");
        echo "Available users:\n";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "  - {$row['login_user']} ({$row['role_user']})\n";
        }
    }
} catch (PDOException $e) {
    echo "✗ Database error: " . $e->getMessage() . "\n";
}
