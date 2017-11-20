@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row">
        <div class="col-md-8">
        
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="level">
                        <span class="flex">
                            <a href="{{route('profile',$thread->owner->name)}}">{{ $thread->owner->name }}</a> posted {{$thread->title}}
                        </span>
                        @can('update', $thread)
                        <form action="{{ $thread->path() }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <button type="submit" class="btn btn-link">Delete Thread</button>
                        </form>
                        @endcan
                    </div>
                </div>
                <div class="panel-body">
                      <div class="body">{{ $thread->body }}</div>
                </div>
            </div>

            @foreach($replies as $reply)
                @include('threads.reply')
            @endforeach

            {{ $replies->links() }}

            @if(auth()->check())
                    <form action="{{ $thread->path().'\replies' }}" method="post">
                    {{ csrf_field() }}
                        <div class="form-group">
                            <textarea class="form-control" name="body" id="" cols="30" rows="10" placeholder="Have anything to say?"></textarea>
                        </div>
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </form>
            @else
                    <p>Please <a href="/login">login</a> to reply</p>
            @endif

        </div>

        <div class="col-md-4">

            <div class="panel panel-default">
                <div class="panel-body">This thread was created at {{ $thread->created_at->diffForHumans() }} 
                by {{ $thread->owner->name }}, and currently has {{ $thread->replies_count }} {{ str_plural('comment', $thread->replies_count)}}.
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
