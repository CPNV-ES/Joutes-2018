@extends('layout')

@section('content')
    <div class="container">
        <h1>Notifications</h1>
        <br><a href="{{ route('notification.create') }}" class="btn btn-primary">Nouvelle notification</a><br><br>
        <ul class="list-group">      
            @foreach ($notifications as $notification)
                <li class="list-group-item">
                    <h3>Equipe : {{ $notification->team->name }} <span style="float: right; font-size: 14px;">{{ $notification->created_at }}</span></h3>
                    <p><b>{{ $notification->title }}</b></p>
                    <p>{{ $notification->description }}</p>
                </li>
            @endforeach
        </ul>    
    </div>
@stop