<?php
namespace Tests\Feature;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
class ReadThreadTest extends TestCase
{
    use DatabaseMigrations;
   
    
    public function setUp(){
        parent::setUp();
        $this->thread = factory('App\Thread')->create();
    }
    
    /**
     * A basic test example.
     *
     * @return void
     * 
     */
        public function test_a_user_can_browse_threads()
    {
        $response = $this->get('/threads');
        //$response->assertStatus(200);
        $response->assertSee($this->thread->title);
    }

    public function test_a_user_can_view_single_thread()
    {
        $response = $this->get($this->thread->path());
        $response->assertSee($this->thread->title);
    }

    public function test_all_replies_corresponding_to_a_thread(){
        $replies = factory('App\Reply')->create([ 'thread_id' => $this->thread->id ]);
        $response = $this->get($this->thread->path())
        ->assertSee($replies->body);
    }

    public function test_thread_have_replies(){
        $response = $this->get($this->thread->path());
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    public function test_thread_has_owner(){
        $response = $this->get($this->thread->path());
        $this->assertInstanceOf('App\User', $this->thread->owner);
    }

    public function test_a_user_can_filter_threads_by_a_channel(){

        $channel = factory('App\Channel')->create();
        $threadinchannel = factory('App\Thread')->create(['channel_id' => $channel->id]);
        $anotherthread = factory('App\Thread')->create();

        $this->get('threads/'.$channel->slug)
        ->assertSee($threadinchannel->title)
        ->assertDontSee($anotherthread->title);

    }

    public function test_a_user_can_browse_his_own_threads(){

        $this->actingAs(factory('App\User')->create(['name' => 'JohnDoe']));
        $threadByJohnDoe = factory('App\Thread')->create(['user_id' => auth()->id()]);
        $threadNotByJohnDoe = factory('App\Thread')->create();

        $response = $this->get('threads?by=JohnDoe')
        ->assertSee($threadByJohnDoe->title)
        ->assertDontSee($threadNotByJohnDoe->title);
    }

    public function test_sort_threads_by_popularity(){

        $threeRepliedThread = factory('App\Thread')->create();
        factory('App\Reply', 3)->create(['thread_id' => $threeRepliedThread->id]);

        $twoRepliedThread = factory('App\Thread')->create();
        factory('App\Reply', 2)->create(['thread_id' => $twoRepliedThread->id]);

        $newThread = $this->thread;

        $response = $this->get('threads/?popular=1');
        $threadsFromResponse = $response->baseResponse->original->getData()['threads'];
        //dd($response->baseResponse->original->getData());
        $this->assertEquals([3,2,0], $threadsFromResponse->pluck('replies_count')->toArray());
        //$this->assertEquals([3,2,0], array_column($response, 'replies_count'));
    }

}