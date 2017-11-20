<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_records_activity_when_a_thread_is_created(){
        //$this->withoutExceptionHandling();
        $user = factory('App\User')->create();
        $this->actingAs( $user);
        $thread = factory('App\Thread')->create(['user_id' => $user->id]);

        $this->assertDatabaseHas('activities', [
            'type' => 'created_thread',
            'user_id' => $user->id,
            'subject_id' => $thread->id,
            'subject_type' => 'App\Thread'
        ]);

        $activity = \App\Activity::first();
        $this->assertEquals( $activity->subject->id, $thread->id);

    }

    public function test_it_records_activity_when_a_reply_is_created(){
        $user = factory('App\User')->create();
        $this->actingAs( $user);
        $reply = factory('App\Reply')->create(['user_id' => $user->id]);
        $this->assertEquals( 2, \App\Activity::count());
    }

}
