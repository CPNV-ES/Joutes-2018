<!-- @author Davide Carboni -->
@extends('layout')

@section('content')

    <div class="container">

        <h1> Bienvenue dans la procedure de changement d'équipe</h1>

        @if (isset($error))
            <div class="alert alert-danger">{{$error}}</div>
        @endif

        {{ Form::open(array('url' => route('profile.update',$id), 'method' => 'put', 'id' => 'formProfileChangeTeam')) }}

        <div class="form-group">
            {{ Form::label('personalTeams', 'Equipe à changer') }}
            {{ Form::select('personalTeams', $dropdownListPersonalTeams, null, ['placeholder' => 'Sélectionner', 'class' => 'form-control allSameStyle', 'id' => 'personalTeams']) }}
        </div>
        <div class="form-group">
            {{ Form::label('event', 'Evénements disponbles') }}
            {{ Form::select('event', [], null, ['placeholder' => 'Sélectionner', 'class' => 'form-control allSameStyle', 'id' => 'event', 'disabled' => 'disabled']) }}
        </div>
        <div class="form-group">
            {{ Form::label('tournament', 'Tournois disponibles') }}
            {{ Form::select('tournament', [], null, ['placeholder' => 'Sélectionner', 'class' => 'form-control allSameStyle', 'id' => 'tournament', 'disabled' => 'disabled']) }}
        </div>
        <div class="form-group">
            {{ Form::label('teamSelected', 'Equipes disponibles') }}
            <span id="errorMessageTeam" class="text-danger"></span>
            {{ Form::select('teamSelected', [], null, ['placeholder' => 'Sélectionner', 'class' => 'form-control allSameStyle', 'id' => 'teamSelected', 'disabled' => 'disabled']) }}
        </div>
        <div class="form-group">
            {{ Form::checkbox('switch', 1, false, ['class' => 'switch', "id"=>'switch', 'disabled' => 'disabled']) }} Je veux créer un equipe <br>
        </div>
        <div class="form-group">
            {{ Form::label('teamNew', 'Nom de l\'équipe') }}
            <span id="errorMessage" class="text-danger"></span>
            {{ Form::text('teamNew', null,['class' => 'form-control', 'disabled' => 'disabled', 'id' => 'teamNew']) }}
        </div>
        <div class="form-group hidden">
            {{ Form::text('toFinish', $toFinish, ['id' => 'toFinish']) }}
        </div>

        <div class="send">{{ Form::button('Terminer', array('class' => 'btn btn-success', 'disabled' => 'disabled', 'id' => 'formValidate')) }}</div>

        {{ Form::close() }}

        </div>

@stop
