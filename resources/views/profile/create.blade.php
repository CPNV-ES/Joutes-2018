<!-- @author Davide Carboni -->
@extends('layout')

@section('content')

    <div class="container">

        <h1> Bienvenue {{ Auth::user()->first_name }} dans le Joutes du CPNV</h1>
        <p>Vous êtes obligé de vous inscrire à un tournoi. Il est également nécessaire d'avoir ou de rejoindre une équipe</p>
        <p>Veuillez terminer votre inscription en utilisant le formulaire ci-dessous.</p><br>

        {{ Form::open(array('url' => route('profile.store'), 'method' => 'post', 'id' => 'formProfile')) }}

        <div class="form-group">
            {{ Form::label('event', 'Evenement') }}
            {{ Form::select('event', $dropdownListEvent, null, ['placeholder' => 'Sélectionner', 'class' => 'form-control allSameStyle', 'id' => 'event']) }}
        </div>
        <div class="form-group">
            {{ Form::label('tournament', 'Tournoi') }}
            {{ Form::select('tournament', [], null, ['placeholder' => 'Sélectionner', 'class' => 'form-control allSameStyle', 'id' => 'tournament', 'disabled' => 'disabled']) }}
        </div>
        <div class="form-group">
            {{ Form::label('teamSelected', 'Equipes disponibles') }}
            {{ Form::select('teamSelected', [], null, ['placeholder' => 'Sélectionner', 'class' => 'form-control allSameStyle', 'id' => 'teamSelected', 'disabled' => 'disabled']) }}
        </div>
        <div class="form-group">
            {{ Form::checkbox('switch', 1, false, ['class' => 'switch', "id"=>'switch', 'disabled' => 'disabled']) }} Je veux créer un equipe <br>
        </div>
        <div class="form-group">
            {{ Form::label('teamNew', 'Nom de l\'equipe') }}
            <span id="errorMessage" class="text-danger"></span>
            {{ Form::text('teamNew', null, ['class' => 'form-control', 'disabled' => 'disabled', 'id' => 'teamNew']) }}
        </div>

        <div class="send">{{ Form::button('Terminer', array('class' => 'btn btn-success', 'disabled' => 'disabled', 'id' => 'formValidate')) }}</div>

        {{ Form::close() }}


        </div>

@stop
