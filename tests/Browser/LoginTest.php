<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;
    /** @test */
    public function registered_user_can_login()
    {
        User::factory()->create([
            'email' => 'test@mail.com'
        ]);
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->type('email', 'test@mail.com')
                    ->type('password', 'password')
                    ->press('#login-btn')
                    ->assertAuthenticated()
                    ->assertPathIs('/');
        });
    }
}
