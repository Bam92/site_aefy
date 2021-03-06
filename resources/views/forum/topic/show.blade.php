@extends('layouts.app')

@section('content')


    @extends('layouts.session_messages')
    <h1>{{ $topic->subject }}</h1>
    @if ($topic->is_opened == false)
        @if (!Auth::guest()&& Auth::user()->hasRole('Administrateur') || !Auth::guest()&& Auth::user()->can('Modérer les messages du forum'))
            <a href="{{route('forum.topic.open', $topic)}}">
                <button type="button">Réouvrir le topic</button>
            </a>
        @endif
    @else
        @if (!Auth::guest()&& Auth::user()->hasRole('Administrateur') || !Auth::guest()&& Auth::user()->can('Modérer les messages du forum') || !Auth::guest()&& Auth::user()->id == $topic->user_id)
            <a href="{{route('forum.topic.close', $topic)}}">
                <button type="button">Fermer le topic</button>
            </a>
        @endif
    @endif<br>
    @if (!Auth::guest() && Auth::user()->hasPermissionTo('Déplacer les topics'))
        {!! Form::open([ 'url' => route('forum.message.store', $topic), 'method' => 'POST', 'id' => 'move_topic']) !!}

        {!! Form::label('forum_subcategory_id', 'Déplacer ce topic vers : ')!!}<br>
        {!! Form::select('forum_subcategory_id', $subcategories, null) !!}

        {!! Form::hidden('url', route('forum.topic.move', $topic)) !!}
        <button type='submit'>Déplacer</button>
        {!! Form::close() !!}<br><br>
    @endif



    @foreach($messages as $m)
        <div>
            <!----- GONFIGURATION DE LA DATE ------->
            {{ \Carbon\Carbon::setLocale('fr') }}
            <span style="display: none"> {{$time = new \Carbon\Carbon($m->created_at)}}</span>
            <!-------- FIN ------->

            Par {{ $m->user->nickname }} - {{ $time->diffForHumans(\Carbon\Carbon::now()) }}<br>
            @if($m->user->avatar == true)
                <img src="{{ url('/img/avatars/'.$m->user->id.'.jpg')  }}" alt="Erreur"/>
            @endif<br>
            @if($m->is_moderated == false)
                {{$m->content}}<br> <a href="{{ route('forum.message.signal', $m) }}">Signaler ce message</a>
                @if (!Auth::guest()&& Auth::user()->hasPermissionTo('Modérer les messages du forum'))
                    <a href="{{ route('forum.message.moderate', $m) }}">Modérer le message</a>
                @endif
            @else
                <i>Message modéré.</i>
                @if (!Auth::guest()&&  Auth::user()->hasPermissionTo('Modérer les messages du forum'))
                    <a href="{{ route('forum.message.unmoderate', $m) }}">Démodérer le message</a>
                @endif
            @endif

        </div><br><br>
    @endforeach
    {{ $messages->links() }}
    @if ($topic->is_opened != false)
        <h3>Répondre à ce topic : </h3>
        {!! Form::open([ 'url' => route('forum.message.store'), 'method' => 'POST']) !!}

                <!--{!! Form::label('content', 'Votre message ')!!}<br>-->
        {!! Form::textarea('content', null)!!}<br><br>

        {!! Form::hidden('forum_topic_id', $topic->id) !!}

        <button type='submit'>Poster votre réponse</button>
        {!! Form::close() !!}

    @endif
@endsection 

@section('javascript')
    <script src="{{ URL::asset('js/forum_topic_move.js') }}"></script>
@endsection