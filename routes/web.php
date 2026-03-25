<?php

use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


if (app()->isLocal()) {
    Route::get('/playground', function () {
        $user = User::factory()->make();
        Mail::to($user)->send(new WelcomeMail($user));

        return (new WelcomeMail($user))->render();
    });
}
