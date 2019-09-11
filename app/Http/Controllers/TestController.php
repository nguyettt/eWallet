<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail; 
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function test(Request $request)
    {
        // echo 'test';
        // Mail::send('mail', [], function($m) {
        //     $m->from('vagrant.mail.server@gmail.com', 'eWallet');
        //     $m->to('kuro.keita94@gmail.com', 'kurokeita')->subject("Test server");
        // });

        // dd(env('MAIL_HOST'));

        // $user = Auth::user();
        // if($user->hasVerifiedEmail()) echo 'ye';
        // else echo 'no';
        $user = $request->user();
        if ($user instanceof User) echo 'ye';
        else echo 'no';
        if($user->hasVerifiedEmail()) echo 'ye';
        else echo 'no';
    }
}
