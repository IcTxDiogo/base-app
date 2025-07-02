<?php

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard.index', absolute: false));
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

test('login request gets rate limited after too many failed attempts', function () {
    Event::fake();
    
    $user = User::factory()->create();
    
    // Make exactly 5 failed login attempts to trigger rate limiting
    for ($i = 0; $i < 5; $i++) {
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);
        
        // All attempts should fail with validation errors
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }
    
    // The 6th attempt should be rate limited and trigger a different error
    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password', // Even with wrong password, rate limiting should trigger first
    ]);
    
    // Assert that the response contains validation errors
    $response->assertSessionHasErrors(['email']);
    
    // Get the error message and check if it's about rate limiting
    $errors = session('errors');
    $emailErrors = $errors->get('email');
    
    // The error should mention throttling/rate limiting
    $hasThrottleError = false;
    foreach ($emailErrors as $error) {
        if (str_contains($error, 'Too many login attempts') || 
            str_contains($error, 'throttle') || 
            str_contains($error, 'seconds') ||
            str_contains($error, 'minutes') ||
            str_contains($error, 'Muitas tentativas') ||
            str_contains($error, 'tentativas de login') ||
            str_contains($error, 'minutos') ||
            str_contains($error, 'segundos')) {
            $hasThrottleError = true;
            break;
        }
    }
    
    expect($hasThrottleError)->toBeTrue('Should have rate limiting error message');
    
    // Assert that Lockout event was dispatched
    Event::assertDispatched(Lockout::class);
    
    $this->assertGuest();
});

test('failed login attempts are tracked for rate limiting', function () {
    $user = User::factory()->create();
    
    // Make multiple failed attempts to test rate limiting tracking
    for ($i = 0; $i < 3; $i++) {
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);
        
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }
    
    // After 3 failed attempts, we should still be able to attempt
    // (rate limiting kicks in after 5 attempts)
    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);
    
    $response->assertSessionHasErrors(['email']);
    expect($response->status())->toBe(302); // Redirect back with errors
});

test('successful login after failed attempts works correctly', function () {
    $user = User::factory()->create();
    
    // Make a few failed attempts first
    for ($i = 0; $i < 2; $i++) {
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);
    }
    
    // Now make a successful login - this should clear rate limiting
    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);
    
    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard.index', absolute: false));
});
