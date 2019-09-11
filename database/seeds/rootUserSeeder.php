<?php

use Illuminate\Database\Seeder;
use App\User;

class rootUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        User::create(array(
            'username' => 'root',
            'email'    => 'root@mail.com',
            'firstName'=> 'root',
            'lastName' => 'root',
            'dob'      => '1990-01-01',
            'gender'   => 'male',
            'password' => Hash::make('password'),
        ));
    }
}
