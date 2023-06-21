<?php

namespace App\Observers;

use App\Models\User;
use App\Models\EmailAlias;
use Illuminate\Support\Facades\DB;

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
        }  else {
            // send message
        }  
            
    }


}
