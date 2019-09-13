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
            'type' => 'income',
            'name' => 'Income',
            'parent_id' => 0
        ]);

        Category::create([
            'user_id' => $user_id,
            'type' => 'outcome',
            'name' => 'Expense',
            'parent_id' => 0
        ]);
        
        $income_id = Category::where('user_id', $user_id)
                             ->where('type', 'income')
                             ->first()
                             ->id;

        $outcome_id = Category::where('user_id', $user_id)
                              ->where('type', 'outcome')
                              ->first()
                              ->id;

        Category::create([
            'user_id' => $user_id,
            'type' => 'outcome',
            'name' => 'Education',
            'parent_id' => $outcome_id
        ]);

        Category::create([
            'user_id' => $user_id,
            'type' => 'outcome',
            'name' => 'Entertainment',
            'parent_id' => $outcome_id
        ]);

        Category::create([
            'user_id' => $user_id,
            'type' => 'outcome',
            'name' => 'Family',
            'parent_id' => $outcome_id
        ]);

        Category::create([
            'user_id' => $user_id,
            'type' => 'outcome',
            'name' => 'Food & Beverage',
            'parent_id' => $outcome_id
        ]);

        Category::create([
            'user_id' => $user_id,
            'type' => 'outcome',
            'name' => 'Friends & Lovers',
            'parent_id' => $outcome_id
        ]);

        Category::create([
            'user_id' => $user_id,
            'type' => 'outcome',
            'name' => 'Health & Fitness',
            'parent_id' => $outcome_id
        ]);

        Category::create([
            'user_id' => $user_id,
            'type' => 'outcome',
            'name' => 'Investment',
            'parent_id' => $outcome_id
        ]);

        Category::create([
            'user_id' => $user_id,
            'type' => 'outcome',
            'name' => 'Shopping',
            'parent_id' => $outcome_id
        ]);

        Category::create([
            'user_id' => $user_id,
            'type' => 'outcome',
            'name' => 'Transportation',
            'parent_id' => $outcome_id
        ]);

        Category::create([
            'user_id' => $user_id,
            'type' => 'outcome',
            'name' => 'Travel',
            'parent_id' => $outcome_id
        ]);

        Category::create([
            'user_id' => $user_id,
            'type' => 'outcome',
            'name' => 'Others',
            'parent_id' => $outcome_id
        ]);

        Category::create([
            'user_id' => $user_id,
            'type' => 'outcome',
            'name' => 'Bills & Utilities',
            'parent_id' => $outcome_id
        ]);

        Category::create([
            'user_id' => $user_id,
            'type' => 'income',
            'name' => 'Award',
            'parent_id' => $income_id
        ]);

        Category::create([
            'user_id' => $user_id,
            'type' => 'income',
            'name' => 'Gifts',
            'parent_id' => $income_id
        ]);

        Category::create([
            'user_id' => $user_id,
            'type' => 'income',
            'name' => 'Interest Money',
            'parent_id' => $income_id
        ]);

        Category::create([
            'user_id' => $user_id,
            'type' => 'income',
            'name' => 'Salary',
            'parent_id' => $income_id
        ]);

        Category::create([
            'user_id' => $user_id,
            'type' => 'income',
            'name' => 'Selling',
            'parent_id' => $income_id
        ]);

        Category::create([
            'user_id' => $user_id,
            'type' => 'income',
            'name' => 'Others',
            'parent_id' => $income_id
        ]);
    }
}
