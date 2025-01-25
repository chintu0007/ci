<?php

use App\Models\User;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    Redis::incr('landing-page-views');
    $seed = Session::remember('users.seed', fn () => rand(0, 100));

    return view('welcome', [
        'users' => User::inRandomOrder($seed)->paginate(3),
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
