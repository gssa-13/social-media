<?php

namespace Tests\Feature;

use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CanLikeStatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_like_statuses()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $status = Status::factory()->create();

        $this->actingAs($user)->postJson( route('statuses.like.store', $status) );

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'status_id' => $status->id
        ]);
    }

    /** @test */
    public function guest_users_can_not_like_statuses()
    {
        $status = Status::factory()->create();
        $response = $this->post(route('statuses.like.store', $status));
        $response->assertRedirect('login');
    }

    /** @test */
    public function an_authenticated_user_can_unlike_statuses()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $status = Status::factory()->create();
        $this->actingAs($user)->postJson( route('statuses.like.store', $status) );
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'status_id' => $status->id
        ]);

        $this->actingAs($user)->deleteJson( route('statuses.like.destroy', $status) );

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'status_id' => $status->id
        ]);
    }
}
