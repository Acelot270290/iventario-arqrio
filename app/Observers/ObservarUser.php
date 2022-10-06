<?php

namespace App\Observers;

use App\Models\User;
use App\Notifications\NovoCadastroNotification;

class ObservarUser

{

     /**
     * Handle the Food "created" event.
     *
     * @param  User  $user
     * @return void
     */

    public function created(User $user){
        $user->notify(new NovoCadastroNotification());
    }
}
