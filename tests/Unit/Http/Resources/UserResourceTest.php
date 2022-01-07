<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserResourceTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_user_resources_must_have_the_necessary_fields()
    {
        $user = User::factory()->create();

        $userResource = UserResource::make($user)->resolve();

        $this->assertEquals($user->id, $userResource['id']);
        $this->assertEquals($user->name, $userResource['user_name']);
        $this->assertEquals($user->link(), $userResource['user_link']);
        $this->assertEquals($user->avatar(), $userResource['user_avatar'] );
    }
}
