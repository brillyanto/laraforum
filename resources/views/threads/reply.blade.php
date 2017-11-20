<div class="panel panel-default">
    <div class="panel-heading">
        <div class="level">
            <h5  class="flex">
                <a href="{{route('profile',$reply->owner->name)}}">{{ $reply->owner->name }}</a> Posted {{ $thread->created_at->diffForHumans() }}
            </h5>
            <div>
                <form action="/replies/{{$reply->id}}/favorites" method="post">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-default" {{ $reply->isFavorited() ? 'disabled' : ''}} >{{ $reply->favorites_count }} {{ str_plural('Favorite', $reply->favorites_count )}}</button>
                </form>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div class="body">{{ $reply->body }}</div>
    </div>

</div>