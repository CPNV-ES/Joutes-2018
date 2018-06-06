<!-- @author Davide Carboni -->
@extends('layout')

@section('content')
    <div class="container boxList">

        <div class="col-md-12">
            <h1> Bienvenue {{ Auth::user()->first_name }} dans le Joutes du CPNV</h1>
            <p>Vous êtes obligé de vous inscrire à un tournoi. Il est également nécessaire d'avoir ou de rejoindre une équipe</p>
            <p>Veuillez terminer votre inscription en utilisant le formulaire ci-dessous.</p>

            {{ Form::open(array('url' => route('courts.store'), 'method' => 'post', 'id' => 'formCourt')) }}


            <div class="form-group">
                {{ Form::label('event', 'Evenement') }}
                {{ Form::select('event', $dropdownListEvent, null, ['placeholder' => 'Sélectionner', 'class' => 'form-control allSameStyle', 'id' => 'sport']) }}
            </div>
            <div class="form-group">
                {{ Form::label('tourmenent', 'Tournoi') }}
                {{ Form::select('tourmenent', $dropdownListTournements, null, ['placeholder' => 'Sélectionner', 'class' => 'form-control allSameStyle', 'id' => 'sport']) }}
            </div>
            <div class="form-group">
                {{ Form::label('team', 'Equipes disponibles') }}
                {{ Form::select('team', $dropdownListTeams, null, ['placeholder' => 'Sélectionner', 'class' => 'form-control allSameStyle', 'id' => 'sport']) }}
            </div>
            <div class="form-group">
                {{ Form::label('name', 'ou créer un equipe') }}
                {{ Form::text('name', null, array('class' => 'form-control')) }}
            </div>

            <div>
                <div class="send">{{ Form::button('Terminer', array('class' => 'btn btn-success formSend')) }}</div>
            </div>

            {{ Form::close() }}

        </div>
    </div>
@stop
