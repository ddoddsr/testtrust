<?php

namespace App\Observers;

use App\Models\User;
use App\Models\EmailAlias;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class EmailAliasObserver
{
    
    public function created(EmailAlias $emailAlias): void
    {
        $this->moveUser($emailAlias);
    }
    public function editing(EmailAlias $emailAlias): void {
        $this->moveUser($emailAlias);
    }

    private function moveUser(EmailAlias $emailAlias): void {
        $bogusUser = User::where('email', $emailAlias->email)
        // ->andWhere('is_admin', false) 
            ->first();
        if ($bogusUser) {     
            User::where('supervisor_id', $bogusUser->id)
                ->update([
                    'supervisor_id' => $emailAlias->user_id,
                    'super_email1' => $emailAlias->user->email
                ]);
            
          // successful move
            $bogusUser->delete();
            Notification::make() 
            ->title('Alias Added and Staff Moved.')
            ->success()
            ->send(); 
        }  else {
            // send message
            Notification::make() 
            ->title('Alias record not found. Did you copy it all?')
            ->success()
            ->send(); 
        }  
            
    }


}
