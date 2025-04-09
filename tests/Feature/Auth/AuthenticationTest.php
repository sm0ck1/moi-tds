<?php

use App\Models\User;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('first user can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('user can authenticate using the login screen but not use dashboard without status admin', function () {
    $first_user = User::factory()->create();

    $response = $this->post('/login', [
        'email' => $first_user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticatedAs($first_user);
    $response->assertRedirect(route('dashboard', absolute: false));
    $this->get('/dashboard');
    $this->assertGuest();

});

test('user can authenticate using the login screen and can see dashboard', function () {
    $first_user = User::factory()->create([
        'is_admin' => true,
    ]);

    $response = $this->post('/login', [
        'email' => $first_user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticatedAs($first_user);
    $response->assertRedirect(route('dashboard', absolute: false));
    $this->get('/dashboard');
    $this->assertAuthenticatedAs($first_user);

});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});
