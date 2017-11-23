<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use App\Activity;


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

    public function test_it_fetches_feed_of_any_user(){
        $user = factory('App\User')->create();
        $this->actingAs($user);

        factory('App\Thread')->create(['user_id' => $user->id]);

        $thread = factory('App\Thread')->create([
            'user_id' => $user->id,
            'created_at' => Carbon::now()->subWeek()
        ]);
        $thread->activity()->update(['created_at' => Carbon::now()->subWeek()]);

        $feed = Activity::feed(auth()->user());

        //dd($feed->toArray());

       
        $this->assertTrue( $feed->keys()->contains(
            Carbon::now()->format('Y-m-d')
        ));

        $this->assertTrue( $feed->keys()->contains(
            Carbon::now()->subWeek()->format('Y-m-d')
        ));
        
       
    }

}
