<!-- @author Dessaules Loïc -->

@extends('layout')

@section('content')
	<div class="container">
		<a href=""><i class="fa fa-4x fa-arrow-circle-left return" aria-hidden="true"></i></a>
		<h1>Créer une equipe</h1>

		@if ($errors->any() || isset($customError))
			<div class="alert alert-danger">
				@if ($errors->any())
					@foreach ($errors->all() as $error)
						{{ $error }}<br>
					@endforeach
				@endif
				@if (isset($customError))
					{{ $customError}}
				@endif
			</div>
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
			<div class="send">{{ Form::button('Créer', array('class' => 'btn btn-success formSend', 'disabled' => 'disabled', 'id' => 'formValidate' )) }}</div>

		{{ Form::close() }}

	</div>
@stop
