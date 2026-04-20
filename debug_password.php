<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Password;

try {
    $user = User::first();
    if(!$user) {
        echo "No users found.";
        exit;
    }
    
    $token = Password::broker()->createToken($user);
    echo "Generated Token: " . $token . "\n";
    
    echo "Sending reset link to: " . $user->email . "\n";
    $status = Password::broker()->sendResetLink(['email' => $user->email]);
    echo "Status: " . $status . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
