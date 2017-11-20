<?php

namespace Tests\Feature;

use Tests\TestCase;
//use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FavoriteTest extends TestCase
{
    use DatabaseMigrations;
    
    public function test_guest_cannot_favorite_reply(){
        $this->post('replies/1/favorites')
        ->assertRedirect('/login');
    }

    public function test_authenticated_user_can_favorite_any_reply()
    {
        $this->withoutExceptionHandling()->actingAs(factory('App\User')->create());
        $reply = factory('App\Reply')->create();
        $this->post('replies/'.$reply->id.'/favorites');
        $this->assertCount(1, $reply->favorites);
    }

    public function test_authenticated_user_can_favorite_any_reply_once(){
        $this->withoutExceptionHandling()->actingAs( factory('App\User')->create());
        $reply = factory('App\Reply')->create();
        try{
            $this->post('replies/'.$reply->id.'/favorites');
            $this->post('replies/'.$reply->id.'/favorites');
        } catch(\Exception $e){
            $this->fail('did not expect to insert the same record twice.'. $e);
        }
        $this->assertCount(1, $reply->favorites);

    }


}
