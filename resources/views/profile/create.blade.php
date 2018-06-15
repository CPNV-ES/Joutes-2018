<!-- @author Davide Carboni -->
@extends('layout')

@section('content')

    <div class="container">

        @if (isset($from) && ($from != null))
            <h1> Bienvenue dans la procedure de changement d'équipe</h1>
        @else
            <h1> Bienvenue {{ Auth::user()->first_name }} dans les Joutes du CPNV</h1>
        @endif

        @if ($toFinish == null)
            <p>Vous êtes obligés de vous inscrire à un tournoi. Il est également nécessaire d'avoir ou de rejoindre une équipe</p>
            <p>Veuillez terminer votre inscription en utilisant le formulaire ci-dessous.</p><br>
        @else
            @if ($toFinish == "requiredMorning")
                <p>ATTENTION! Il ne vous reste plus qu'à choisir une équipe pour le matin</p>
            @endif
            @if ($toFinish == "requiredAfternoon")
                <p>ATTENTION! Il ne vous reste plus qu'à choisir une equipe pour l'aprés-midi</p>
            @endif
        @endif

        @if (isset($error))
            <div class="alert alert-danger">{{$error}}</div>
        @endif

        {{ Form::open(array('url' => route('profile.store'), 'method' => 'post', 'id' => 'formProfile')) }}

        <div class="form-group">
            {{ Form::label('event', 'Evénements disponbles') }}
            {{ Form::select('event', $dropdownListEvent, null, ['placeholder' => 'Sélectionner', 'class' => 'form-control allSameStyle', 'id' => 'event']) }}
        </div>
        <div class="form-group">
            {{ Form::label('tournament', 'Tournois disponibles') }}
            {{ Form::select('tournament', $dropdownListEventTournaments, null, $tournamentsOptions) }}
        </div>
        <div class="form-group">
            {{ Form::label('teamSelected', 'Equipes disponibles') }}
            {{ Form::select('teamSelected', $dropdownListTournamentTeams, null, $teamsOptions) }}
        </div>
        <div class="form-group">
            {{ Form::checkbox('switch', 1, $checkBoxActive, $checkBoxOptions) }} Je veux créer un equipe <br>
        </div>
        <div class="form-group">
            {{ Form::label('teamNew', 'Nom de l\'équipe') }}
            <span id="errorMessage" class="text-danger"></span>
            {{ Form::text('teamNew', null, $teamNewOptions) }}
        </div>
        <div class="form-group hidden">
            {{ Form::text('toFinish', $toFinish, ['id' => 'toFinish']) }}
        </div>
        <div class="form-group hidden">
            {{ Form::text('from', $from, ['id' => '$from']) }}
        </div>

        <div class="send">{{ Form::button('Terminer', array('class' => 'btn btn-success formSend', 'disabled' => 'disabled', 'id' => 'formValidate')) }}</div>

        {{ Form::close() }}

        <br>

        {{ Form::open(array('url' => route('profile.destroy', Auth::user()->participant->id), 'method' => 'delete')) }}
            <button class="btn btn-success  button-delete"  data-type="participantSigin">Reset</button>
        {{ Form::close() }}

        </div>

@stop
