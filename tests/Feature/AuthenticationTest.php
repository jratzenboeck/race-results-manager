<?php

use App\Models\User;
use App\Providers\RouteServiceProvider;

it('has login page')
    ->get('/login')
    ->assertOk()
    ->assertViewIs('auth.login');

it('can login a user using the login screen', function() {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(RouteServiceProvider::HOME);
    $this->assertAuthenticated();
});

it('cannot login with invalid password', function() {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});
