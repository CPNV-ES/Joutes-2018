<!-- @author De la Gouj Ilias -->
@extends('layout')

@section('content')
<div class="container">
  <h1>Créér une notification</h1>

  {{ Form::open(array('route' => 'notification.store')) }}
  {{ csrf_field() }}
    <div class="form-group">
      {{ Form::label('title', 'Titre de la notification') }}
      {{ Form::text('title', null, array('class' => 'form-control', 'placeholder' => 'Attente, Retard')) }}
    </div>
    <div class="form-group">
      {{ Form::label('description', 'Description') }}
      {{ Form::text('description', null, array('class' => 'form-control', 'placeholder' => 'Equipe 2 attendue, Retard au terrain de volley')) }}
    </div>
    <div class="form-group">
      {{ Form::label('teams', 'Equipes') }}
      <select class="form-control" name="team" id="team">
        @foreach ($teams as $team)
          <option value="{{$team->id}}">{{$team->name}}</option>
        @endforeach
        </select>
    </div>
    <div class="form-group">
        <input class="btn btn-primary" type="submit">
    </div>    
  {{ Form::close() }}
</div>
@stop