<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Category;

class CreateDefaultCategories
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
            'type' => 1,
            'name' => 'Income',
            'parent_id' => 0
        ]);

        Category::create([
            'user_id' => $user_id,
            'type' => 2,
            'name' => 'Expense',
            'parent_id' => 0
        ]);

        Category::create([
            'user_id' => $user_id,
            'type' => 3,
            'name' => 'Transfer to another wallet',
            'parent_id' => 0
        ]);
    }
}
