<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Category;

class CreateBaseCategories
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

        Category::create([
            'user_id' => $user_id,
            'type' => 'income',
            'name' => 'Income',
            'parent_id' => 0
        ]);

        Category::create([
            'user_id' => $user_id,
            'type' => 'outcome',
            'name' => 'Outcome',
            'parent_id' => 0
        ]);
    }
}
