<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\Models\User;
use App\Models\Status;
use App\Events\StatusCreated;
use App\Http\Resources\StatusResource;

class CreateStatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_create_statuses()
    {
        Event::fake([StatusCreated::class]);

        // 1 given => Getting an authenticated user
        $user = User::factory()->create();
        $this->actingAs($user);

        // 2 When => Make a post request to status
        $response = $this->postJson(route('statuses.store'), ['body' => 'My first status']);

        Event::assertDispatched(StatusCreated::class, function ($e) {
            return $e->status->id === Status::first()->id
                && $e->status instanceof StatusResource
                && $e->status->resource instanceof Status
                && $e instanceof ShouldBroadcast;
        });

        $response->assertJson([
            'data' => ['body' => 'My first status'],
        ]);

        // 3 Then => See a new status from database
        $this->assertDatabaseHas('statuses', [
            'user_id' => $user->id,
            'body' => 'My first status'
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
