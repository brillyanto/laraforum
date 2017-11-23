<?php

namespace App;

use App\Reply;
use App\User;
use App\Channel;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Builder;

class Thread extends Model
{
    use RecordsActivity;

    protected $guarded = [];
    //protected $owner = ['owner', 'channel'];
    
    protected static function boot(){
        parent::boot();
        static::addGlobalScope('replyCount', function($builder){
            $builder->withCount('replies');
        });

        static::addGlobalScope('owner', function($builder){
            $builder->with('owner');
        });

        static::addGlobalScope('channel', function($builder){
            $builder->with('channel');
        });

        static::deleting(function($thread){

            // $thread->replies()->delete(); // does not fire the model events for replies instead use the following method
            // method 1
            // $thread->replies->each(function($reply){
            //     $reply->delete();
            // });

            // method 2
                $thread->replies->each->delete();

        });
   

    }

    public function path(){
        // eager loading the channel information from gloabscopes definitions
        return '/threads/'.$this->channel->slug.'/'.$this->id;
    }

    public function replies(){
        return $this->hasMany(Reply::class);
    }

    public function owner(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function addReply($reply){
        $this->replies()->create($reply);
    }

    public function channel(){
        return $this->belongsTo(Channel::class);
    }

    public function scopeFilter($query, $filters){
        return $filters->apply($query);
    }
}
