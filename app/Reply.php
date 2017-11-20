<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Reply extends Model
{
    use RecordsActivity;

    protected $guarded = [];
    
    protected $with = ['owner', 'favorites'];

    public function owner(){
        return $this->belongsTo(User::class, 'user_id');
    }

/// chapter 21 end : Refactoring this into a new trai called Favoritable. 

    public function favorites(){
        return $this->morphMany(Favorite::class, 'favorited');
    }

    public function favorite(){

        if(! $this->favorites()->where(['user_id' => auth()->id()])->exists() ){
           return  $this->favorites()->create(['user_id' => auth()->id()]);
        }

    }

    public function isFavorited(){

        return !! $this->favorites->where('user_id', auth()->id())->count();

    }

    public function getFavoritesCountAttribute(){
        return $this->favorites->count();
    }
/// end of Favoritable section

    public function thread(){
        return $this->belongsTo('App\Thread');
    }

}
