<?php
/**
 * Author: Jun Chen
 */

namespace App\Jobs;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GenerateQRCodeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const QR_CODE_API_URL = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=';

    protected User $user;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Generate the QR code using an external API (https://goqr.me/api/)
        $client = new Client();
        $response = $client->get(self::QR_CODE_API_URL . urlencode($this->user->address));

        if ($response->getStatusCode() == 200) {
            // Save the QR code image locally
            $qrCodeImage = $response->getBody()->getContents();

            // Use the user's ID as the filename
            $filename = $this->user->id . '.png';

            // Save the QR code image to the public disk
            Storage::disk('public')->put('qr_codes/' . $filename, $qrCodeImage);
        } else {
            // Handle the case where QR code generation failed, e.g., log an error.
            Log::error('QR code generation failed for user ' . $this->user->id);
        }
    }
}
