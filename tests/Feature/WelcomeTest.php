<?php

use App\Models\User;
use Illuminate\Support\Facades\Redis;

it('increments the page count for each visit', function () {
    // Ensure Redis is clean before the test starts
    Redis::del('landing-page-views');

    // Simulate three visits
    $this->get('/');
    $this->get('/');
    $this->get('/');

    // Assert the Redis value equals 3
    expect((int) Redis::get('landing-page-views'))->toBe(3);
});

it('provides users in random paginated order', function () {
    // Create 3 user records
    $users = User::factory()->count(3)->create();

    // Retrieve users from the paginated response
    $usersFromPage1 = collect($this->get('/')->viewData('users')->items());
    $usersFromPage2 = collect($this->get('/?page=2')->viewData('users')->items());

    // Merge paginated results and assert uniqueness
    $mergedUsers = $usersFromPage1->merge($usersFromPage2);

    expect($mergedUsers->count())->toBe($mergedUsers->unique('id')->count());
})->repeat(3);
