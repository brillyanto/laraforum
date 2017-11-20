<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadTest extends TestCase
{
    use DatabaseMigrations;

    public function test_unauthenticated_user_may_not_create_thread(){
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $thread = factory('App\Thread')->make();
        $this->withoutExceptionHandling()->post('/threads', $thread->toArray());
    }

    public function test_authenticated_user_can_create_thread(){
        $this->actingAs( factory('App\User')->create() );
        $thread = factory('App\Thread')->make();
        $response = $this->post('/threads', $thread->toArray());
       // dd($response->headers->get('Location'));
        $this->get($response->headers->get('Location'))
        ->assertSee($thread->title)
        ->assertSee($thread->body);
    }

    public function test_guest_dont_create_thread(){
        $this->withExceptionHandling()->get('threads/create')
        ->assertRedirect('/login');
    }

    public function test_thread_path(){
        $thread = factory('App\Thread')->create();
        $this->assertEquals('/threads/'.$thread->channel->slug.'/'.$thread->id, $thread->path());
        //dd($thread->path());
    }

    public function test_a_thread_requires_a_channel(){

        $channels = factory('App\Channel',2)->create();

        $this->publishThread(['channel_id' => null])
        ->assertSessionHasErrors('channel_id');

         $this->publishThread(['channel_id' => 999])
        ->assertSessionHasErrors('channel_id');
        
    }

    public function test_a_thread_requires_a_title(){
        $this->publishThread(['title' => null])
        ->assertSessionHasErrors('title');
    }

    public function test_a_thread_requires_body(){
        $this->publishThread(['body' => null])
        ->assertSessionHasErrors('body');
    }

    public function publishThread($overrides){
        $this->withExceptionHandling()->actingAs(factory('App\User')->create());
        $thread = factory('App\Thread')->make($overrides);
        return $this->post('/threads', $thread->toArray());
    }

    public function test_unauthorized_users_maynot_delete_threads(){
        $this->withExceptionHandling();
        $thread = factory('App\Thread')->create();
        $this->delete($thread->path())->assertRedirect('/login');
        $user = factory('App\User')->create();
        $this->actingAs($user);
        $this->delete($thread->path())->assertStatus(403);

    }

    public function test_authorized_users_can_delete_threir_threads(){
        $user = factory('App\User')->create();
        $this->actingAs($user);
        $thread = factory('App\Thread')->create(['user_id' => $user->id]);
        $reply = factory('App\Reply')->create(['thread_id' => $thread->id]);
        // browser request
        $response = $this->delete($thread->path());


        $response->assertRedirect('/threads');
        // jason request
        // $response = $this->json('DELETE', $thread->path());
        //$response->assertStatus(204);
        
        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        
    }
}
