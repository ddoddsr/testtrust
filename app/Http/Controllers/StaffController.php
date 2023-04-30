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

           if( $form->resultStatus == 'Complete') {
                $this->superRecord = User::where( 'email' ,  $form->superEmail1 )->first() ;   
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
                    if ( $staffRecord->resultId < (int)$form->resultId ) {
                        // replace data in DB with new record
                        $staffRecord->first_name = $form->firstName;
                        $staffRecord->last_name = $form->lastName;
                        $staffRecord->resultId = $form->resultId ;
                        $staffRecord->startDate = \Carbon\Carbon::parse($form->startDate)->format('Y-m-d H:i:s');
                        $staffRecord->finishDate = \Carbon\Carbon::parse($form->finishDate) ->format('Y-m-d H:i:s');
                        $staffRecord->updateDate = \Carbon\Carbon::parse($form->updateDate)->format('Y-m-d H:i:s');
                        $staffRecord->resultStatus = $form->resultStatus;
                        $staffRecord->designation = $form->designation;
                        $staffRecord->supervisor = $form->supervisor;
                        $staffRecord->superEmail1 = $form->superEmail1;
                        $staffRecord->supervisorId = $this->superRecord->id ?? null;
                        $staffRecord->effectiveDate = \Carbon\Carbon::parse($form->effectiveDate)->format('Y-m-d');
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

           if( $form->resultStatus == 'Complete' && $form->superEmail1 != '') {
                $first_name = substr($form->supervisor, 0, strpos($form->supervisor, ' '));
                $last_name = substr($form->supervisor, strlen($first_name));
                
                $this->superRecord = tap(
                    User::firstOrCreate(
                        [  'email' => $form->superEmail1  ], // search params
                        // of any additional data to add to record
                        [ 
                            'first_name' => $first_name,
                            'last_name' => $last_name,
                            //TODO use random password 
                            // 'password' => Str::password(),
                            //TODO Not asdf
                            'password' => Hash::make("asdf"),
                        ]
                    ), function (User $user) {
                        $this->createCompany($user);
                    }
                );
           }
        }
    }  
   
    public function formPrep($form) {
        $this->superRecord = User::where( 'email' ,  $form->superEmail1 )->first() ;

        return [
            'first_name' => $form->firstName,
            'last_name' => $form->lastName,
            //TODO use random password 
            // 'password' => Str::password(),
            //TODO Not asdf
            'password' => Hash::make("asdf"),
            
            'resultId' => $form->resultId ,
            'startDate' => \Carbon\Carbon::parse($form->startDate)->format('Y-m-d H:i:s'),
            'finishDate' => \Carbon\Carbon::parse($form->finishDate) ->format('Y-m-d H:i:s'),
            'updateDate' => \Carbon\Carbon::parse($form->updateDate)->format('Y-m-d H:i:s'),
            'resultStatus' => $form->resultStatus,
            'designation' => $form->designation,
            'supervisorId' => $this->superRecord->id ?? null,
            'supervisor' => $form->supervisor,
            'superEmail1' => $form->superEmail1,
            'effectiveDate' => \Carbon\Carbon::parse($form->effectiveDate)->format('Y-m-d'),
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
            'name' => $user->fullName() ." 's Company",
            'personal_company' => true,
        ]));
    }
}
