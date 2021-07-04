<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class SMSCampaignSend implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $phones;
    public $sms;
    public $twilio;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($phones, $sms)
    {
        //
        $this->phones = $phones;
        $this->sms = $sms;
        $this->twilio = $twilio = new \Aloha\Twilio\Twilio(env('TWILIO_SID'), env('TWILIO_TOKEN'), env('TWILIO_FROM'));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        foreach ($this->phones as $phone) {
            if ($phone) {
                Log::info($phone);
                $this->twilio->message($phone, $this->sms);
            }
        }
    }
}
