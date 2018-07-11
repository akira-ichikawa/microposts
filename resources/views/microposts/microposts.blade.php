<ul class="media-list">
@foreach ($microposts as $micropost) <!-- microposts変数をどこから持ってきて、１つ１つ -->
    <?php $user = $micropost->user; ?>
    <li class="media">
        <div class="media-left">
            <img class="media-object img-rounded" src="{{ Gravatar::src($user->email, 50) }}" alt="">
        </div>
        <div class="media-body">
            <div>
                {!! link_to_route('users.show', $user->name, ['id' => $user->id]) !!} <span class="text-muted">posted at {{ $micropost->created_at }}</span>
            </div>
            <div>
                <p>{!! nl2br(e($micropost->content)) !!}</p>
            </div>
            
            <div>
            @if (Auth::User()->is_favorite($micropost->id))
                {!! Form::open(['route' => ['user.unfavorite', $micropost->id], 'method' => 'delete']) !!}
                <!--{!! Form::submit('Unfavorite', ['class' => 'btn btn-danger btn-xs']) !!}-->
                {!! Form::submit('Unfavorite', ['class' => 'btn btn-warning btn-xs']) !!}
                {!! Form::close() !!}
            @else
                {!! Form::open(['route' => ['user.favorite', $micropost->id], 'method' => 'store']) !!} 
                <!--ここで命令がなされて、ここのマイクロポストにはidがそれぞれあるから、web.phpの命令の時には、micropost_idを指定する。-->
                {!! Form::submit('Favorite', ['class' => 'btn btn-primary btn-xs']) !!}  <!--ボタン変えた。-->
                {!! Form::close() !!}
            @endif
            </div>
            
            
            <div>
                @if (Auth::id() == $micropost->user_id)
                    {!! Form::open(['route' => ['microposts.destroy', $micropost->id], 'method' => 'delete']) !!}
                        {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-xs']) !!}
                    {!! Form::close() !!}
                @endif
            </div>
        </div>
    </li>
@endforeach
</ul>
{!! $microposts->render() !!}