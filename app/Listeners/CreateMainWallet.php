<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Wallet;

class CreateMainWallet
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $user_id = $event->user->id;
        Wallet::create([
            'user_id' => $user_id,
            'name' => 'Main Wallet',
            'balance' => 0
        ]);
    }
}
