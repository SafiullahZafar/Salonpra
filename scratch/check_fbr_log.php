<?php

use App\Models\FbrLog;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$latestLog = FbrLog::latest()->first();
if ($latestLog) {
    echo "INVOICE: " . $latestLog->invoice_no . "\n";
    echo "STATUS: " . $latestLog->status . "\n";
    echo "RESPONSE: " . json_encode($latestLog->response, JSON_PRETTY_PRINT) . "\n";
} else {
    echo "No logs found.";
}
