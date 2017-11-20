<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
class ReplyOwnerTest extends TestCase
{

    use DatabaseMigrations;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_reply_has_owner()
    {
        $reply = factory('App\Reply')->create();
        $this->assertInstanceOf('App\User', $reply->owner);
        //$this->assertTrue(true);
    }

    public function test_authenticated_user_can_reply_to_a_thread(){
        $this->be(factory('App\User')->create());
        $thread = factory('App\Thread')->create();
        $reply = factory('App\Reply')->make();
        $this->post($thread->path().'/replies', $reply->toArray());
        $this->get($thread->path())
        ->assertSee( $reply->body );                                       
    }

    public function test_reply_require_body(){

        $this->withExceptionHandling()->be(factory('App\User')->create());
        $thread = factory('App\Thread')->create();
        $reply = factory('App\Reply')->make(['body' => null]);
        $response = $this->post($thread->path().'/replies', $reply->toArray());
        $response->assertSessionHasErrors('body');

    }

}
