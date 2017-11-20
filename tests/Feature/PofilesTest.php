<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PofilesTest extends TestCase
{

    use DatabaseMigrations;

    public function test_a_user_has_a_profile(){
        $user = factory('App\User')->create();
        $this->withoutExceptionHandling()->get("/profiles/{$user->name}")
        ->assertSee($user->name);
    }

    public function test_display_all_threads_belongs_to_that_user(){
        $user = factory('App\User')->create();
        $thread = factory('App\Thread')->create(['user_id' => $user->id]);
        $this->withoutExceptionHandling()->get("/profiles/{$user->name}")
        ->assertSee($thread->title)
        ->assertSee($thread->body);
    }

}
