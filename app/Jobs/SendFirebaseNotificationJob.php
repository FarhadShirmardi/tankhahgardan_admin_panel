<?php

namespace App\Jobs;

use App\FirebaseToken;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Kreait\Firebase\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;

class SendFirebaseNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;

    /**
     * Create a new job instance.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $http = new Client;
        $response = $http->post(
            config('app.tankhah_url').'/panel/'.config('app.tankhah_token').'/notification',
            [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'form_params' => $this->data,
            ]
        );
    }
}
