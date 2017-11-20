@component('profiles.activities.activity')
@slot('heading')
        <a href="route('profile', $activity->subject->owner->name)">{{ $activity->subject->owner->name }}</a> posted a Thread 
        <a href="{{ $activity->subject->path() }}">{{$activity->subject->title}}</a>
@endslot

@slot('time')
    {{$activity->subject->created_at->diffForHumans()}}
@endslot

@slot('body')
    {{ $activity->subject->body }} 
@endslot

@endcomponent