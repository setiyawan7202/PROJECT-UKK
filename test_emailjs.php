<?php

use Illuminate\Support\Facades\Http;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing EmailJS...\n";
echo "Service ID: " . env('EMAILJS_SERVICE_ID') . "\n";
// echo "Template ID: " . env('EMAILJS_TEMPLATE_ID') . "\n";
// echo "Public Key: " . env('EMAILJS_PUBLIC_KEY') . "\n";

try {
    $response = Http::post('https://api.emailjs.com/api/v1.0/email/send', [
        'service_id' => env('EMAILJS_SERVICE_ID'),
        'template_id' => env('EMAILJS_TEMPLATE_ID'),
        'user_id' => env('EMAILJS_PUBLIC_KEY'),
        'template_params' => [
            'to_name' => 'Test User',
            'to_email' => 'admin@siapras.id', // Using sender as recipient for test
            'password' => 'test-password',
            'login_id' => '123456',
            'role' => 'admin',
            'login_url' => 'http://localhost/login'
        ]
    ]);

    echo "Status Code: " . $response->status() . "\n";
    echo "Body: " . $response->body() . "\n";

    if ($response->successful()) {
        echo "SUCCESS!\n";
    } else {
        echo "FAILED!\n";
    }

} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
