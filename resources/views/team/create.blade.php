<!-- @author Dessaules Loïc -->

@extends('layout')

@section('content')
	<div class="container">
		<a href=""><i class="fa fa-4x fa-arrow-circle-left return" aria-hidden="true"></i></a>	
		<h1>Créer une equipe</h1>

		@if (isset($error))
			<div class="alert alert-danger">{{$error}}</div>
		@endif

		{{ Form::open(array('url' => route('teams.store'), 'method' => 'post', 'id' => 'formTeam')) }}

			<div class="form-group">
				{{ Form::label('event', 'Evenement') }}
				{{ Form::select('event', $dropdownListEvent, null, ['placeholder' => 'Sélectionner', 'class' => 'form-control allSameStyle', 'id' => 'event']) }}
			</div>
			<div class="form-group">
				{{ Form::label('tournament', 'Tournoi') }}
				{{ Form::select('tournament', $dropdownListEventTournaments, null, $tournamentsOptions) }}
			</div>
			<div class="form-group">
				{{ Form::label('name', 'Nom') }}
				<span id="errorMessage" class="text-danger"></span>
				{{ Form::text('name', null, $teamNewOptions) }}
			</div>
            <div class="form-group hidden">
                {{ Form::text('from', "team") }}
            </div>
			<div class="send">{{ Form::button('Créer', array('class' => 'btn btn-success formSend', 'id' => 'formValidate', 'disabled' => 'disabled')) }}</div>

		{{ Form::close() }}

	</div>
@stop
