<!-- @author Dessauges Antoine -->
@extends('layout')

@section('content')

	<div class="container">
		<a href=""><i class="fa fa-4x fa-arrow-circle-left return" aria-hidden="true"></i></a>

		<h1> {{ $participant->last_name }} {{ $participant->first_name }}</h1>

		@if (isset($infos))
			<div class="alert alert-success">
				{{ $infos }}
			</div>
		@endif

		<h2>Equipes du participant</h2>


		@if ( count($participant->teams) == 0  )
			<div class="alert alert-danger">
				Aucun équipe lié à ce membre !
			</div>
		@else
			<table id="participants-show-table" class="table table-striped table-bordered table-hover translate" cellspacing="0" width="100%">

				<thead>
					<tr>
						<th>Nom de l'équipe</th>
						@if (Auth::user()->role == "administrator")
							<th>Actions</th>
						@endif
					</tr>
				</thead>

				<tbody>

				  	@foreach ($participant->teams as $team)
						<tr>
					      <td data-id="{{$team->id}}" class="clickable"> {{ $team->name }} </td>
							@if (Auth::user()->role == "administrator")
							  <td class="action">
									  {{ Form::open(array('url' => route('teams.participants.destroy', [$team->pivot['participant_id'], $team->pivot['team_id']]), 'method' => 'delete')) }}
										<button type="submit" class="button-delete" data-type="memberTeam" data-name='"{{ $participant->last_name }} {{ $participant->first_name }}" de "{{ $team->name }}"'>
											<i class="fa fa-trash-o fa-lg action" aria-hidden="true"></i>
										</button>
									  {{ Form::close() }}
							  </td>
							@endif
					    </tr>
					@endforeach

			  	</tbody>

			</table>
		@endif

		@if (Auth::user()->role == "administrator")
			<h2>Ajouter ce membre à une équipe</h2>
			@if (isset($error))
				<div class="alert alert-danger">
					{{ $error }}
				</div>
			@else
				{{ Form::open(array('url' => route('teams.participants.store',  $participant->id), 'method' => 'post')) }}
				{{ Form::checkbox('isCaptain','1', false) }} Capitaine
				{{ Form::select('team', $dropdownList, null, ['placeholder' => 'Sélectionner', 'class' => 'form-control addMember']) }}
				{{ Form::close() }}
			@endif
		@endif
	</div>

@stop
