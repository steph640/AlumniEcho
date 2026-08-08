<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;

try {
    $request = Request::create('/api/chatbot/ask', 'POST', ['question' => "Peux-tu présenter AlumniEcho en une phrase ?"]);
    $controller = new App\Http\Controllers\Api\ChatbotController();
    $response = $controller->ask($request, $app->make(App\Services\GeminiChatService::class));
    echo $response->getContent();
} catch (Throwable $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
