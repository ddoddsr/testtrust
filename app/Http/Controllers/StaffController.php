<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\Models\Team;
use App\Models\Schedule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public $superRecord;
    public function storeRecord($formData)
    {
       foreach($formData as $form) {

           if( $form->result_status == 'Complete') {
                $this->superRecord = User::where( 'email' ,  $form->super_email1 )->first() ;   
                $staffRecord = tap(
                    User::firstOrCreate(
                        [  'email' => $form->email  ], // search params
                        // returns [] of any additional data to add to record
                        $this->formPrep($form)
                    ), function (User $user) {
                        $this->createCompany($user);
                    }
                );
                
                // was not RecentlyCreated
                if( ! $staffRecord->wasRecentlyCreated ) {
                
                // We found an existing record
                    if ( $staffRecord->result_id < (int)$form->result_id ) {
                        // replace data in DB with new record
                        $staffRecord->first_name = $form->first_name;
                        $staffRecord->last_name = $form->last_name;
                        $staffRecord->result_id = $form->result_id ;
                        $staffRecord->start_date = \Carbon\Carbon::parse($form->start_date)->format('Y-m-d H:i:s');
                        $staffRecord->finish_date = \Carbon\Carbon::parse($form->finish_date) ->format('Y-m-d H:i:s');
                        $staffRecord->update_date = \Carbon\Carbon::parse($form->update_date)->format('Y-m-d H:i:s');
                        $staffRecord->result_status = $form->result_status;
                        $staffRecord->designation = $form->designation;
                        $staffRecord->supervisor = $form->supervisor;
                        $staffRecord->super_email1 = $form->super_email1;
                        $staffRecord->supervisor_id = $this->superRecord->id ?? null;
                        $staffRecord->effective_date = \Carbon\Carbon::parse($form->effective_date)->format('Y-m-d');
                        $staffRecord->save();
                    }
                }
                // remove schedule info
                $staffRecord->schedules()->delete();
                // add new schedule info
                $this->saveSched($staffRecord, $form->sched);
            }
        }
    }
    public function storeSuperRecord($formData)
    {
       foreach($formData as $form) {

           if( $form->result_status == 'Complete' && $form->super_email1 != '') {
                $first_name = trim(substr($form->supervisor, 0, strpos($form->supervisor, ' ')));
                $last_name = trim(substr($form->supervisor, strlen($first_name)));
                
                $this->superRecord = tap(
                    User::firstOrCreate(
                        [  'email' => $form->super_email1  ], // search params
                        // of any additional data to add to record
                        [ 
                            'first_name' => $first_name,
                            'last_name' => $last_name,
                            //TODO use random password 
                            // 'password' => Str::password(),
                            //TODO Not asdf
                            'password' => Hash::make("asdf"),
                            'active' => true,
                            'is_supervisor' => true,
                        ]
                    ), function (User $user) {
                        $this->createCompany($user);
                    }
                );
           }
        }
    }  
   
    public function formPrep($form) {
        $this->superRecord = User::where( 'email' ,  $form->super_email1 )->first() ;

        return [
            'first_name' => $form->first_name,
            'last_name' => $form->last_name,
            //TODO use random password 
            // 'password' => Str::password(),
            //TODO Not asdf
            'password' => Hash::make("asdf"),
            
            'result_id' => $form->result_id ,
            'start_date' => \Carbon\Carbon::parse($form->start_date)->format('Y-m-d H:i:s'),
            'finish_date' => \Carbon\Carbon::parse($form->finish_date) ->format('Y-m-d H:i:s'),
            'update_date' => \Carbon\Carbon::parse($form->update_date)->format('Y-m-d H:i:s'),
            'result_status' => $form->result_status,
            'designation' => $form->designation,
            'supervisor_id' => $this->superRecord->id ?? null,
            'supervisor' => $form->supervisor,
            'super_email1' => $form->super_email1,
            'effective_date' => \Carbon\Carbon::parse($form->effective_date)->format('Y-m-d'),
        ];
    }


    public function saveSched($staffRecord,$formSched) {
        foreach($formSched as $schedLine){
            if  (
                property_exists($schedLine, 'day' ) 
                && property_exists($schedLine, 'start' )
                && property_exists($schedLine, 'end' )
                && property_exists($schedLine, 'location' )
            ){
                 $scheds =
                    new Schedule(
                        [
                            'day' => $schedLine->day,
                            'start' => $schedLine->start,
                            'end' => $schedLine->end,
                            'location' => $schedLine->location,
                        ]
                    );
                $staffRecord->schedules()->save($scheds);
            }
        }
        return;
    }

    /**
     * Create a personal team for the user.
     */
    protected function createCompany(User $user): void
    {
        $user->ownedCompanies()->save(Company::forceCreate([
            'user_id' => $user->id,
            'name' => $user->full_name ." 's Company",
            'personal_company' => true,
        ]));
    }
}
