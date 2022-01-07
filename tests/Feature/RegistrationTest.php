<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test  */
    public function user_can_register()
    {
        $userData = array(
            'name' => 'JohnDoe',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@mail.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        );

        $response = $this->post( route('register'), $userData );

        $response->assertRedirect('/');

        $this->assertDatabaseHas('users', array(
            'name' => 'JohnDoe',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@mail.com',
        ));

        $this->assertTrue(
            Hash::check('password', User::first()->password),
            'The password needs to be hashed'
        );
    }
}
