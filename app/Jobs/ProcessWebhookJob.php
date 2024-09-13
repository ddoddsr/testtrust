<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessWebhookJob extends \Spatie\WebhookClient\Jobs\ProcessWebhookJob
{
    // use Queueable;

    /**
     * Create a new job instance.
     */
    // public function __construct()
    // {
    //     //
    // }

//     public function __construct(WebhookCall $webhookCall)
// {
//     $this->webhookCall = $webhookCall;
// }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // DefaultSignatureValidatorhas a return true;
        // logger(['webhookCall' => $this->webhookCall['attributes']]);
        foreach ($this->webhookCall['payload'] as $key => $attrib) {
            logger([$key => $attrib]);
        }
        // logger(
        // [
        //     'note' => 'works still',
        //     'id'=> $this->webhookCall['id'],
        //     'first_name'=> $this->webhookCall['first_name'],
        //     'last_name'=> $this->webhookCall['last_name'],
        //     'payload'=> $this->webhookCall['payload'],
        // ]);

    }
}
