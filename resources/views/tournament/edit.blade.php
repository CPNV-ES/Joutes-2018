<!-- @author Dessaules Loïc -->

@extends('layout')

@section('content')
	<div class="container">
		<a href=""><i class="fa fa-4x fa-arrow-circle-left return" aria-hidden="true"></i></a>	
		<h1>Modifier un tournoi</h1>

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

		{{ Form::open(array('url' => route('tournaments.update', $tournament->id), 'method' => 'put', 'id' => 'formTournament', 'enctype' => 'multipart/form-data')) }}

			<div class="form-group">
				{{ Form::label('name', 'Nom') }}
				{{ Form::text('name', $tournament->name, array('class' => 'form-control')) }}
			</div>
			<div class="form-group">
				{{ Form::label('Sport', 'Sport') }}
				@if(!empty($sport)/*Normal case : a sport is linked to the tournament*/)
					{{ Form::select('sport', array('Terrain(s) lié(s)' => $dropdownListSportsWithCourt, 'Aucun terrain lié' => $dropdownListSportsWithNoCourt), $sport->id, array('placeholder' => 'Sélectionner', 'class' => 'form-control allSameStyle', 'id' => 'sport')) }}
				@else
					{{ Form::select('sport', array('Avec terrains' => $dropdownListSportsWithCourt, 'Sans terrains' => $dropdownListSportsWithNoCourt), null, array('placeholder' => 'Sélectionner', 'class' => 'form-control allSameStyle', 'id' => 'sport')) }}
				@endif
			</div>
			<div class="form-group">
				{{ Form::label('img', "Nombre d'équipes par poule") }}
				{{ Form::number('teamsPerPools', $tournament->nbTeamPerPool, array('class' => 'allSameStyle form-control')) }}
			</div>
			<div class="form-group">
				{{ Form::label('startDate', 'Date de début') }}
				{{ Form::date('startDate', $tournament->start_date, array('class' => 'form-control allSameStyle')) }}
			</div>
			<div class="form-group">
				{{ Form::label('startTime', 'Heure de début') }}
				{{ Form::text('startTime', date("H:i", strtotime($tournament->start_date)), array('placeholder' => 'hh:mm', 'class' => 'form-control')) }}
			</div>
		<!--
			<div class="form-group">
				{{-- Form::label('teams', 'Équipes participantes') }}
				@if(!empty($teamsAreParticipatingId))
					{{ Form::select('teams[]', $dropdownListTeams, $teamsAreParticipatingId, array('class' => 'form-control allSameStyle', 'id' => 'multiple-teams-select', 'multiple')) }}
				@else
					{{ Form::select('teams[]', $dropdownListTeams, null, array('class' => 'allSameStyle form-control', 'id' => 'multiple-teams-select', 'multiple')) --}}
				endif
			</div>
		-->
			<div class="form-group">
				{{ Form::label('img', 'Image') }}
				{{ Form::file('img', null) }}
			</div>

			<div class="send">{{ Form::button('Enregistrer', array('class' => 'btn btn-success formSend')) }}</div>

		{{ Form::close() }}

		<br>


	</div>
@stop
