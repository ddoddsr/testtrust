<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Str;
use App\Listeners\DeleteProfilePhoto;
use Illuminate\Support\Facades\Storage;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function creating(User $user): void
    {
        $user->first_name = $this->fixCase($user->first_name);
        $user->last_name = $this->fixCase($user->last_name);
        $user->review = $this->detirmineReviewStatus($user);
    }
    
    // public function created(User $user): void
    // {
    //     //
    // }

    /**
     * Handle the User "updated" event.
     */
    public function updating(User $user): void
    {
        $user->first_name = $this->fixCase($user->first_name);
        $user->last_name = $this->fixCase($user->last_name);
        $user->review = $this->detirmineReviewStatus($user);   
        // if ( $user->isDirty('profile_photo_path') ) {
        //     logger('profile photo changed');
        // }
        
    }

    // public function updated(User $user): void
    // {
    //     //
    // }

    /**
     * Handle the User "deleting" event.
     */
    public function deleting(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //TODO test
        if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }
        if ($user->avatar_url && Storage::disk('public')->exists($user->avatar_url)) {
            Storage::disk('public')->delete($user->avatar_url);
        }
    }

    protected function detirmineReviewStatus($user) {
        
        $match = "/[^a-zA-Z \'\. \`-]+/i";
        // $match = "/[^a-zA-Z \'\. \`-]+/i";
        
        // $match = "/[^a-zA-Z \'\. \`-\p{L}]+/i";
        
        $matchEmail = "/[^a-zA-Z 0-9\'\.\@]/i";
        if( 
            strlen( $user->first_name) < 2  ||
            strlen( $user->last_name ) < 2  ||
            // preg_match($match, $user->first_name ) ||
            // preg_match($match, $user->last_name ) ||
            preg_match($matchEmail, $user->super_email1 )
            
        ) {
            return true;
        } 
        if( $user->getOriginal != null &&
            (
                $user->first_name !== $user->getOriginal->first_name
                || $user->last_name !== $user->getOriginal->last_name
            )
        ) {
            return true;
        } 
        return false ;
    }

    protected function fixCase($name)
    {
        if ($name === strtoUpper($name) ) {
            return ucwords(strtolower($name));
        } 
        
        if ($name === strtoLower($name)) {
            return ucwords($name);
        } 
        
        return $name;
    }
}
