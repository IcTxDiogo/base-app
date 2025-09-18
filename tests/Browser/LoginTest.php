<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{

    public function testUmUsuarioPodeFazerLogin()
    {
        $user = User::factory()->create([
            'email' => 'test@base-app.com',
            'password' => bcrypt('password'),
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                    ->assertSee('Acesse sua conta')
                    ->type('email', $user->email)
                    ->type('password', 'password')
                    ->press('Entrar')
                    ->waitForLocation('/dashboard')
                    ->waitForText('Dashboard')
                    ->assertSee('Dashboard');
        });
    }
}