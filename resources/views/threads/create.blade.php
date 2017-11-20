@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Create a new Thread</div>

                <div class="panel-body">
                   
                    <form action="/threads" method="POST">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="channel_id">Choose Channel</label>

                            <select name="channel_id" id="channel" class="form-control" required>
                                <option value="">Choose One..</option>
                                @foreach($channels as $channel)
                                <option value="{{$channel->id}}" {{ $channel->id == old('channel_id') ? 'selected' : '' }}>{{$channel->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="title">Forum Title</label>
                            <input type="text" class="form-control" name="title" id="title" value="{{old('title')}}" required></input>
                        </div>
                        <div class="form-group">    
                            <label for="body">Forum Body</label>
                            <textarea class="form-control" name="body" id="body" cols="30" rows="10" placeholder="Have anything to say?" required>{{old('body')}}</textarea>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                        @if(count($errors))
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                            </ul>
                        @endif
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
