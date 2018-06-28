@if (Auth::user()->is_favoring($micropost->id))
        {!! Form::open(['route' => ['user.unfavorite', $user->id], 'method' => 'delete']) !!}
            {!! Form::submit('Unfollow', ['class' => "btn btn-danger btn-block"]) !!}
        {!! Form::close() !!}
    @else
        {!! Form::open(['route' => ['user.favorite', $user->id]]) !!}
            {!! Form::submit('Follow', ['class' => "btn btn-primary btn-block"]) !!}
        {!! Form::close() !!}
@endif
