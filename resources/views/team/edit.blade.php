<!-- @author Dessauges Antoine -->
@extends('layout')

@section('content')
	<div class="container">
		<a href=""><i class="fa fa-4x fa-arrow-circle-left return" aria-hidden="true"></i></a>
		<h1>Editer une équipe</h1>

		@if(isset($error))
			<div class="alert alert-danger">
				{{ $error }}
			</div>
		@endif

		{{ Form::open(array('url' => route('teams.update', $team->id), 'method' => 'put', 'id' => 'formTeam')) }}

			<div class="form-group">
				{{ Form::label('name', "Nom de l'équipe") }}
				{{ Form::text('name', $team->name, array('class' => 'form-control')) }}
			</div>
			<div class="send">{{ Form::submit('Enregistrer', array('class' => 'btn btn-success')) }}</div>

		{{ Form::close() }}

		<br>


	</div>
@stop
