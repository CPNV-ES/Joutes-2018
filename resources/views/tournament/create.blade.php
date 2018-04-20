<!-- @author Dessaules Loïc -->

@extends('layout')

@section('content')
	<div class="container">
		<a href=""><i class="fa fa-4x fa-arrow-circle-left return" aria-hidden="true"></i></a>	
		<h1>Créer un tournoi</h1>

		@if ($errors->any() || isset($customErrors))
			<div class="alert alert-danger">
				@if ($errors->any())
		            @foreach ($errors->all() as $error)
		                {{ $error }}<br>
		            @endforeach
		        @endif
		        @if (isset($customErrors))
		        	@foreach ($customErrors as $customError)
		                {{ $customError }}<br>
		            @endforeach
		        @endif
	        </div>
        @endif

		{{ Form::open(array('url' => route('events.tournaments.store', $eventId), 'method' => 'post', 'class' => 'add', 'id' => 'formTournament', 'enctype' => 'multipart/form-data')) }}

			<div class="form-group">
				{{ Form::label('name', 'Nom') }}
				{{ Form::text('name', null, array('class' => 'form-control')) }}
			</div>
			<div class="form-group">
				{{ Form::label('Sport', 'Sport') }}
				{{ Form::select('sport', array('Avec terrains' => $dropdownListSportsWithCourt, 'Sans terrains' => $dropdownListSportsWithNoCourt), null, array('placeholder' => 'Sélectionner', 'class' => 'allSameStyle form-control', 'id' => 'sport')) }}
			</div>
			<div class="form-group">
				{{ Form::label('teamPerPool', "Nombre d'équipes par poule") }}
				{{ Form::number('teamPerPool', 0, array('class' => 'allSameStyle form-control')) }}
			</div>
			<div class="form-group">
				{{ Form::label('startDate', 'Date de début') }}
				{{ Form::date('startDate', \Carbon\Carbon::now(), array('class' => 'allSameStyle form-control')) }}
			</div>
			<div class="form-group">
				{{ Form::label('startTime', 'Heure de début') }}
				{{ Form::text('startTime', null, array('placeholder' => 'hh:mm', 'class'=>'form-control')) }}
			</div>
			<!--
			<div class="form-group">
				{{-- Form::label('teams', 'Équipes participantes') }}
				{{ Form::select('teams[]', $dropdownListTeams, null, array('class' => 'allSameStyle form-control', 'id' => 'multiple-teams-select', 'multiple')) --}}
			</div>
			-->

			<div class="form-group">
				{{ Form::label('img', 'Image') }}
				{{ Form::file('img', null) }}
			</div>
			<div class="send">{{ Form::button('Créer', array('class' => 'btn btn-success formSend')) }}</div>

		{{ Form::close() }}

	</div>
@stop
