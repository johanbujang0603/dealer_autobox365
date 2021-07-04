<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use App\Mail\EmailCampaign;
use Mail;

class EmailCampignSend implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $emails = [];
    public $html = "";
    public $from = "admin@autoobox365.com";
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($emails, $html, $from)
    {
        $this->emails = $emails;
        $this->html = $html;
        if ($from) {
            $this->from = $from;
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->emails as $email) {
            $html = $this->html;
            $from = $this->from;
            Log::info($email);
            Log::info($from);
            if ($email) {
                Mail::to($email)->queue(new EmailCampaign($html, $from));
            }
        }
    }
}
