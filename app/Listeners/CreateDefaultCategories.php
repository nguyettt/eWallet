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
        
        $income_id = Category::where('user_id', $user_id)
                             ->where('type', 1)
                             ->first()
                             ->id;

        $outcome_id = Category::where('user_id', $user_id)
                              ->where('type', 2)
                              ->first()
                              ->id;

        Category::create([
            'user_id' => $user_id,
            'type' => 2,
            'name' => 'Education',
            'parent_id' => $outcome_id
        ]);

        Category::create([
            'user_id' => $user_id,
            'type' => 2,
            'name' => 'Entertainment',
            'parent_id' => $outcome_id
        ]);

        Category::create([
            'user_id' => $user_id,
            'type' => 2,
            'name' => 'Family',
            'parent_id' => $outcome_id
        ]);

        Category::create([
            'user_id' => $user_id,
            'type' => 2,
            'name' => 'Food & Beverage',
            'parent_id' => $outcome_id
        ]);

        Category::create([
            'user_id' => $user_id,
            'type' => 2,
            'name' => 'Friends & Lovers',
            'parent_id' => $outcome_id
        ]);

        Category::create([
            'user_id' => $user_id,
            'type' => 2,
            'name' => 'Health & Fitness',
            'parent_id' => $outcome_id
        ]);

        Category::create([
            'user_id' => $user_id,
            'type' => 2,
            'name' => 'Investment',
            'parent_id' => $outcome_id
        ]);

        Category::create([
            'user_id' => $user_id,
            'type' => 2,
            'name' => 'Shopping',
            'parent_id' => $outcome_id
        ]);

        Category::create([
            'user_id' => $user_id,
            'type' => 2,
            'name' => 'Transportation',
            'parent_id' => $outcome_id
        ]);

        Category::create([
            'user_id' => $user_id,
            'type' => 2,
            'name' => 'Travel',
            'parent_id' => $outcome_id
        ]);

        Category::create([
            'user_id' => $user_id,
            'type' => 2,
            'name' => 'Others',
            'parent_id' => $outcome_id
        ]);

        Category::create([
            'user_id' => $user_id,
            'type' => 2,
            'name' => 'Bills & Utilities',
            'parent_id' => $outcome_id
        ]);

        Category::create([
            'user_id' => $user_id,
            'type' => 1,
            'name' => 'Award',
            'parent_id' => $income_id
        ]);

        Category::create([
            'user_id' => $user_id,
            'type' => 1,
            'name' => 'Gifts',
            'parent_id' => $income_id
        ]);

        Category::create([
            'user_id' => $user_id,
            'type' => 1,
            'name' => 'Interest Money',
            'parent_id' => $income_id
        ]);

        Category::create([
            'user_id' => $user_id,
            'type' => 1,
            'name' => 'Salary',
            'parent_id' => $income_id
        ]);

        Category::create([
            'user_id' => $user_id,
            'type' => 1,
            'name' => 'Selling',
            'parent_id' => $income_id
        ]);

        Category::create([
            'user_id' => $user_id,
            'type' => 1,
            'name' => 'Others',
            'parent_id' => $income_id
        ]);
    }
}
