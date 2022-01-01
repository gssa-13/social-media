<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateStatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_create_statuses()
    {
        $this->withoutExceptionHandling();
        // 1 given => Getting an authenticated user
        $user = User::factory()->create();
        $this->actingAs($user);

        // 2 When => Make a post request to status
        $response = $this->postJson(route('statuses.store'), ['body' => 'My first post']);
        $response->assertJson([
            'data' => ['body' => 'My first post']
        ]);
        // 3 Then => See a new status from database
        $this->assertDatabaseHas('statuses', [
            'user_id' => $user->id,
            'body' => 'My first post'
        ]);
    }

    /** @test */
    public function guests_can_not_create_statuses()
    {
        $response = $this->postJson(route('statuses.store'), ['body' => 'My first post']);
        $response->assertStatus(401);
    }

    /** @test */
    public function a_status_require_a_body()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson(route('statuses.store'), ['body' => '']);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'message', 'errors' => ['body']
        ]);
    }

    /** @test */
    public function a_status_requires_a_minimun_length()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson(route('statuses.store'), ['body' => 'qwer']);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'message', 'errors' => ['body']
        ]);
    }
}
