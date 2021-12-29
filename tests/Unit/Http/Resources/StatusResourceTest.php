<?php

namespace Tests\Unit\Http\Resources;

use App\Models\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Http\Resources\StatusResource;

class StatusResourceTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_status_resources_must_have_the_necessary_fields()
    {


        $status = Status::factory()->create();

        $statusResource = StatusResource::make($status)->resolve();

        $this->assertEquals($status->body, $statusResource['body']);
        $this->assertEquals($status->user->name, $statusResource['user_name']);
        $this->assertEquals('https://images.pexels.com/photos/3118694/pexels-photo-3118694.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940', $statusResource['user_avatar']);
        $this->assertEquals($status->created_at->diffForHumans(), $statusResource['ago']);

    }
}
