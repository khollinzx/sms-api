<?php

namespace App\Console\Commands;

use App\Models\SMS;
use App\Services\SmsService;
use Illuminate\Console\Command;

class SendScheduledSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:send-scheduled-sms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send queue and pending sms';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pendingMessages = SMS::whereIn('status', ['queued', 'pending'])
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', now())
            ->orderByDesc('id')
            ->get();;

        $this->info("Found {$pendingMessages->count()} pending messages ".now());

        foreach ($pendingMessages as $sms) {
            $this->info("Sending SMS to {$sms->recipient}");
            (new SmsService())->sendSms($sms);
        }

        $this->info('Scheduled SMS sending completed');
    }
}
