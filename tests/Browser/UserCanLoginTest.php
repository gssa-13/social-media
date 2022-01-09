<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UserCanLoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @test
     * @throws \Throwable
     */
    public function registered_user_can_login()
    {
        User::factory()->create([
            'email' => 'test@mail.com'
        ]);
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->type('email', 'test@mail.com')
                    ->type('password', 'password')
                    ->press('@login-btn')
                    ->assertAuthenticated()
                    ->assertPathIs('/');
        });
    }

    /**
     * @test
     *
     * @throws \Throwable
     */
    public function user_can_not_login_with_invalid_information()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email' , '')
                ->press('@login-btn')
                ->assertPathIs('/login')
                ->assertPresent('@validation-errors')
            ;
        });
    }
}
