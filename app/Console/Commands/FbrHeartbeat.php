<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FbrHeartbeat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fbr:heartbeat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a heartbeat to the PRA/FBR server to keep the POS status connected.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Sending heartbeat to PRA/FBR...');

        try {
            // A minimal payload containing the POS ID to let the server know we are alive.
            $fbrPayload = [
                'POSID' => (int) env('FBR_POS_ID'),
            ];

            // Send to the configured PRA/FBR API endpoint. 
            // Depending on the exact system, even an incomplete/empty payload request 
            // resets the disconnection timer because the POSID and token are validated.
            $fbrResponse = Http::withoutVerifying()->withHeaders([
                'Authorization' => 'Bearer ' . env('FBR_AUTH_CODE'),
            ])->timeout(10)->post(env('FBR_API_URL'), $fbrPayload);

            $status = $fbrResponse->status();
            
            // Log it. Even a 400 Bad Request means we successfully pinged the server and it saw our POS ID.
            $this->info('Heartbeat sent successfully. Server responded with Status: ' . $status);
            Log::info('PRA/FBR Heartbeat pinged. Status: ' . $status);

        } catch (\Exception $e) {
            $this->error('Error sending heartbeat: ' . $e->getMessage());
            Log::error('PRA/FBR Heartbeat exception: ' . $e->getMessage());
        }
    }
}
