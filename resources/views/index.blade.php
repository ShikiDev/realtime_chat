@extends('layouts.app')

@section('content')
    @if(!empty($user_id))
        <div class="row">
            <div class="col">
                <div class="alert alert-info">
                    Вы  вошли, как {{$user_name}}.
                </div>
            </div>
        </div>
        <hr>
        <chat-component :messages="{{$messages}}" :user_id="{{$user_id}}"></chat-component>
    @else
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="alert alert-warning">
                    <strong>Внимание!</strong> Для доступа к чату <a href="{{route('login')}}">авторизуйтесь</a> или <a
                            href="{{route('register')}}">зарегистрируйтесь</a>.
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection