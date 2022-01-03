<?php

namespace Tests\Browser;

use App\Models\Comment;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UserCanCommentStatusTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function authenticated_users_can_comment_statuses()
    {
        $status = Status::factory()->create();
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($status, $user){
            $browser->loginAs($user)
                ->visit('/')
                ->waitForText($status->body)
                ->type('comment', 'My first comment')
                ->press('@comment-btn')
                ->waitForText('My first comment')
                ->assertSee('My first comment')
            ;
        });
    }

    /** @test */
    public function users_can_see_all_comments()
    {
        $status = Status::factory()->create();
        $comments = Comment::factory()->count(3)->create([ 'status_id' => $status->id ]);

        $this->browse(function (Browser $browser) use ($status, $comments){
            $browser->visit('/')->waitForText($status->body);
                foreach ($comments as $comment) {
                    $browser->assertSee($comment->first()->body)
                        ->assertSee($comment->user->name)
                    ;
                }
            ;
        });


    }
}
