<?php

namespace App\Jobs;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Http\Controllers\StaffController;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Http\Controllers\FormsiteController;

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
        $formData[0] = $this->getFormData($newest = 0);
        (new StaffController)->storeRecord($formData);
        // logger($throwError);
    }

    public function getFormData($newest = 0){
        $formData = [];
        $scheds = [];
        $sched = (object)[];
        $fieldNames = (new FormsiteController)->fieldNames();
        // DefaultSignatureValidatorhas a return true;
        // logger(['webhookCall' => $this->webhookCall]);
        // logger(['webhookCall_header' => $this->webhookCall->headers()]);
        // logger(['webhookCall_payload' => $this->webhookCall['payload']]);

        $formData['result_id'] = $this->webhookCall['id'];
        // $formData['start_date'] = $this->webhookCall['date_start'] ?? '';
        // $formData['finish_date'] = $this->webhookCall['date_finish'] ?? '';
        // $formData['update_date'] = $this->webhookCall['date_update'] ?? '';
        // $formData['result_status'] = $this->webhookCall['result_status'] ?? '';

        foreach ($this->webhookCall['payload'] as $key => $attrib) {
            $form_field_name = 'unk' ;
            if (array_key_exists((int)$key,$fieldNames)) {
                $form_field_name = $fieldNames[(int)$key];
                // 88 => 'Service Hours'
                // if ($form_field_name == '88-0' || $form_field_name == '88'  ) {
                //     logger(['88' => [$form_field_name => $attrib]]);
                // }

                // What data is here?
                if ($form_field_name == '.' ) {
                    $field_name = '.' . $key;
                }
                if ( $form_field_name == 'day' && $attrib != ''){
                    $sched->day = $attrib ;
                } elseif ( $form_field_name == 'start' && $attrib != ''){
                    $sched->start = $attrib ;
                } elseif ( $form_field_name == 'end' && $attrib != ''){
                    $sched->end = $attrib ;
                } elseif ( $form_field_name == 'location' && $attrib != ''){
                    $sched->location = $attrib ;
                    // Last of a set
                    if(isset($sched->day) && isset($sched->start) && isset($sched->end)) {
                        $scheds[] = $sched;
                    }
                    $sched = (object)[];
                } else {
                    $field_name = $form_field_name;
                    $formData[$field_name] = $attrib;
                }

            } else {
                logger([$key => $attrib]);
            }
        }

        return (object)
        [
             'result_id' => $formData['result_id'],
             'start_date' => $formData['date_start'] ?? '',
             'finish_date' => $formData['date_finish'] ?? '',
             'update_date' => $formData['date_update'] ?? '',
             'result_status' => $formData['result_status'] ?? '',
             'first_name' => trim($formData['firstName']),
             'last_name' => trim($formData['lastName']),
             'email' => $formData['emailAddress'],
             'designation' => $formData['designation'],
             'department' => $formData['department'],
             'supervisor' => $formData['supervisor'],
             'super_email1' => $formData['superEmail1'],
             'super_email2' => $formData['superEmail'],
             // 'pipetosomewhere' => $formData['items'],
             'effective' => $formData['effective'],
             'effective_date' => $formData['effective'],
             'sched' => $scheds ?? (object)[],
        ];
    }
}
