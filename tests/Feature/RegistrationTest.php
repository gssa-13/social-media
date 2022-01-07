<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test  */
    public function user_can_register()
    {
        $response = $this->post( route('register'), $this->userValidaData() );

        $response->assertRedirect('/');

        $this->assertDatabaseHas('users', array(
            'name' => 'JohnDoe_2',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@mail.com',
        ));

        $this->assertTrue(
            Hash::check('password', User::first()->password),
            'The password needs to be hashed'
        );
    }

    /** @test */
    public function the_name_is_required()
    {
        $this->post(
            route('register'),
            $this->userValidaData(array('name' => null))
        )->assertSessionHasErrors('name');
    }

    /** @test */
    public function the_name_must_be_a_string()
    {
        $this->post(
            route('register'),
            $this->userValidaData(array('name' => 1223))
        )->assertSessionHasErrors('name');
    }

    /** @test */
    public function the_name_may_not_be_greater_than_60_characters()
    {
        $this->post(
            route('register'),
            $this->userValidaData(array('name' => Str::random(61) ))
        )->assertSessionHasErrors('name');
    }

    /** @test */
    public function the_name_must_be_unique()
    {
        User::factory()->create(['name' => 'JohnDoe']);
        $this->post(
            route('register'),
            $this->userValidaData(array('name' => 'JohnDoe' ))
        )->assertSessionHasErrors('name');
    }

    /** @test */
    public function the_name_may_only_contain_letters_and_numbers()
    {
        $this->post(
            route('register'),
            $this->userValidaData(array('name' => 'John.Doe' ))
        )->assertSessionHasErrors('name');

        $this->post(
            route('register'),
            $this->userValidaData(array('name' => 'John Doe' ))
        )->assertSessionHasErrors('name');
    }

    /** @test */
    public function the_name_must_be_at_least_6_characters()
    {
        $this->post(
            route('register'),
            $this->userValidaData(array('name' => '12345'))
        )->assertSessionHasErrors('name');
    }

    /** @test */
    public function the_first_name_is_required()
    {
        $this->post(
            route('register'),
            $this->userValidaData(array('first_name' => null))
        )->assertSessionHasErrors('first_name');
    }

    /** @test */
    public function the_first_name_must_be_a_string()
    {
        $this->post(
            route('register'),
            $this->userValidaData(array('first_name' => 1223))
        )->assertSessionHasErrors('first_name');
    }

    /** @test */
    public function the_first_name_may_not_be_greater_than_60_characters()
    {
        $this->post(
            route('register'),
            $this->userValidaData(array('first_name' => Str::random(61) ))
        )->assertSessionHasErrors('first_name');
    }

    /** @test */
    public function the_first_name_must_be_at_least_3_characters()
    {
        $this->post(
            route('register'),
            $this->userValidaData(array('first_name' => '12'))
        )->assertSessionHasErrors('first_name');
    }

    /** @test */
    public function the_first_name_may_only_contain_letters()
    {
        $this->post(
            route('register'),
            $this->userValidaData(array('first_name' => 'Doe3' ))
        )->assertSessionHasErrors('first_name');

        $this->post(
            route('register'),
            $this->userValidaData(array('first_name' => 'Doe<>' ))
        )->assertSessionHasErrors('first_name');
    }

    /** @test */
    public function the_last_name_is_required()
    {
        $this->post(
            route('register'),
            $this->userValidaData(array('last_name' => null))
        )->assertSessionHasErrors('last_name');
    }

    /** @test */
    public function the_last_name_must_be_a_string()
    {
        $this->post(
            route('register'),
            $this->userValidaData(array('last_name' => 1223))
        )->assertSessionHasErrors('last_name');
    }

    /** @test */
    public function the_last_name_may_not_be_greater_than_60_characters()
    {
        $this->post(
            route('register'),
            $this->userValidaData(array('last_name' => Str::random(61) ))
        )->assertSessionHasErrors('last_name');
    }

    /** @test */
    public function the_last_name_must_be_at_least_3_characters()
    {
        $this->post(
            route('register'),
            $this->userValidaData(array('last_name' => '12'))
        )->assertSessionHasErrors('last_name');
    }

    /** @test */
    public function the_last_name_may_only_contain_letters()
    {
        $this->post(
            route('register'),
            $this->userValidaData(array('last_name' => 'Doe3' ))
        )->assertSessionHasErrors('last_name');

        $this->post(
            route('register'),
            $this->userValidaData(array('name' => 'Doe<>' ))
        )->assertSessionHasErrors('name');
    }

    /** @test */
    public function the_email_is_required()
    {
        $this->post(
            route('register'),
            $this->userValidaData(array('email' => null))
        )->assertSessionHasErrors('email');
    }

    /** @test */
    public function the_email_must_be_a_string()
    {
        $this->post(
            route('register'),
            $this->userValidaData(array('email' => 1223))
        )->assertSessionHasErrors('email');
    }

    /** @test */
    public function the_email_must_be_a_valid_email_address()
    {
        $this->post(
            route('register'),
            $this->userValidaData(array('email' => 'invalid.@email') )
        )->assertSessionHasErrors('email');
    }

    /** @test */
    public function the_email_may_not_be_greater_than_100_characters()
    {
        $this->post(
            route('register'),
            $this->userValidaData(array('email' => Str::random(101) ))
        )->assertSessionHasErrors('email');
    }

    /** @test */
    public function the_email_must_be_unique()
    {
        User::factory()->create(['email' => 'john.doe@mail.com']);
        $this->post(
            route('register'),
            $this->userValidaData(array('email' => 'john.doe@mail.com' ))
        )->assertSessionHasErrors('email');
    }

    /** @test */
    public function the_password_is_required()
    {
        $this->post(
            route('register'),
            $this->userValidaData(array('password' => null))
        )->assertSessionHasErrors('password');
    }

    /** @test */
    public function the_password_must_be_a_string()
    {
        $this->post(
            route('register'),
            $this->userValidaData(array('password' => 1223))
        )->assertSessionHasErrors('password');
    }

    /** @test */
    public function the_password_must_be_at_least_6_characters()
    {
        $this->post(
            route('register'),
            $this->userValidaData(array('password' => 12345678))
        )->assertSessionHasErrors('password');
    }

    /** @test */
    public function the_password_must_be_confirmed()
    {
        $this->post(
            route('register'),
            $this->userValidaData(array(
                'password' => 12345678,
                'password_confirmation' => null
            ))
        )->assertSessionHasErrors('password');
    }

    /**
     * @param array $overrides
     * @return string[]
     */
    public function userValidaData(array $overrides = []): array
    {
        return array_merge(array(
            'name' => 'JohnDoe_2',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@mail.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ), $overrides);
    }
}
