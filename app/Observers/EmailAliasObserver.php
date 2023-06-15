<?php

namespace App\Observers;

use App\Models\EmailAlias;

class EmailAliasObserver
{
    
    public function created(EmailAlias $emailAlias): void
    {
        logger($emailAlias->email);
    }
}
