<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test  */
    public function route_key_name_is_set_to_name()
    {
        $user = User::factory()->create();

        $this->assertEquals('name', $user->getRouteKeyName(), 'The route key name must be name');
    }

    /** @test  */
    public function user_has_a_link_to_their_profile()
    {
        $user = User::factory()->create();

        $this->assertEquals( route('users.show', $user), $user->link() );
    }

    /** @test  */
    public function user_has_an_avatar()
    {
        $user = User::factory()->create();

        $this->assertEquals(
            'https://images.pexels.com/photos/3118694/pexels-photo-3118694.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940',
            $user->avatar()
        );
    }

}
