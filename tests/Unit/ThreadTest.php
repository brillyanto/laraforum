<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
class ThreadTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_a_thread_can_add_a_reply(){
        $thread = factory('App\Thread')->create();
        $thread->addReply(
            [
                'body' => 'Lorem Ipsem',
                'user_id' => 1
            ]
        );
        $this->assertCount(1, $thread->replies);
    }
}
