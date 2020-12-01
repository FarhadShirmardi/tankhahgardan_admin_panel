<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Announcement;
use App\FirebaseToken;
use App\AnnouncementUser;
use Kreait\Firebase\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Log;

class AnnouncementJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Announcement
     */
    private $announcement;

    /**
     * Create a new job instance.
     *
     * @param Announcement $announcement
     */
    public function __construct(Announcement $announcement)
    {
        $this->announcement = $announcement;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /** @var Announcement $announcement */
        $announcement = Announcement::query()->find($this->announcement->id);
        if ($announcement->updated_at->toDateTimeString() != $this->announcement->updated_at->toDateTimeString()
            or $announcement->expire_at <= now()->toDateTimeString()) {
            return;
        }
        dispatch(
            (new SendFirebaseNotificationJob([
                'announcement_id' => $announcement->id
            ]))->onQueue('activationSms')
        );
    }
}
