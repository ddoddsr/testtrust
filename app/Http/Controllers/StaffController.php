<?php
namespace App\Http\Controllers;

set_time_limit(0);
ini_set ('max_execution_time',  0);

use App\Models\Team;
use App\Models\User;
use App\Models\Location;
use App\Models\Schedule;
use App\Models\EmailAlias;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public $superRecord;
    // public $superAlias;
    public $designations;
    public $locations;

    public function __construct()
    {
        $this->designations = User::designations_key();
        $this->locations = array_flip(Location::all()->pluck('name', 'id')->toArray());
    }

    public function storeRecord($formData)
    {
        foreach($formData as $form) {

           if( $form->result_status == 'Complete') {
            // Post::withTrashed()->find($post_id)->restore();
                $staffRecord = User::withTrashed()->firstOrCreate(
                        [  'email' => $form->email  ], 
                        $this->formPrep($form)
                    );
                if ($staffRecord->trashed()) {
                    $staffRecord->restore();
                }
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
                        $staffRecord->designation_id = $this->designations[strtolower(substr($form->designation, 0, 4))]  ?? null;
                        $staffRecord->supervisor = $form->supervisor;
                        $staffRecord->super_email1 = $form->super_email1;
                        $staffRecord->supervisor_id = $this->superFromAlias($form->super_email1 ) ?? null;
                        $staffRecord->effective_date = \Carbon\Carbon::parse($form->effective_date)->format('Y-m-d');
                        $staffRecord->exit_date = null;
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
                
                // $this->superAlias = $this->superFromAlias($form->super_email1 ) ?? null; 
                $superRecord = EmailAlias::where( 'email' ,  $form->super_email1 )->first() ; 
                if( $superRecord  == null ) {
                    $superRecord = User::where( 'email' ,  $form->super_email1 )->first() ;   
                }
                if( $superRecord  == null ) {
                    
                    $first_name = trim(substr($form->supervisor, 0, strpos($form->supervisor, ' ')));
                    $last_name = trim(substr($form->supervisor, strlen($first_name)));
                
                    $this->superRecord =User::Create(
                        [  
                            'email' => str::lower($form->super_email1),
                            'first_name' => $first_name,
                            'last_name' => $last_name,
                            'password' => Str::password(), 
                            'active' => true,
                            'is_supervisor' => true,
                            
                        ] );
                }
           }
        }
    }  
   
    public function formPrep($form) {
        // $this->superAlias = $this->superFromAlias($form->super_email1 ) ; 
        // logger(['super create' => $this->superAlias]);
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
            'designation_id' => $this->designations[strtolower(substr($form->designation, 0, 4))] ?? null,
            'designation' => $form->designation,
            // 'department_id' => 1 ,
            'supervisor_id' => $this->superFromAlias($form->super_email1 ) ?? null,
            'supervisor' => $form->supervisor,
            'super_email1' => $form->super_email1,
            'effective_date' => \Carbon\Carbon::parse($form->effective_date)->format('Y-m-d'),
            'exit_date' => null,
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
                            'location_id' => $this->locations[$schedLine->location] ?? null,
                        ]
                    );
                $staffRecord->schedules()->save($scheds);
            }
        }
        return;
    }

    protected function superFromAlias($super_email) {
        // logger($super_email);
        $superRecord = EmailAlias::where( 'email' ,  $super_email )->first() ;   
        if ($superRecord == ! null) {
            // logger($superRecord->user_id );
            return $superRecord->user_id ;
        } else {
            // logger('Alias null');
            $superRecord = User::where( 'email' ,  $super_email )->first() ;   
            return $superRecord->id ?? null ;
        }
        
    }
}
