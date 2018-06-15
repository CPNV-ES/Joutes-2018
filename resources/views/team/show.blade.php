<!-- @author Dessauges Antoine -->
@extends('layout')

@section('content')


	<div class="container">
		<a href=""><i class="fa fa-4x fa-arrow-circle-left return" aria-hidden="true"></i></a>

		<h1> {{ $team->name }}</h1>

		@if (isset($infos))
			<div class="alert alert-success">
				{{ $infos }}
			</div>
		@endif

		<h2>Membres de l'équipe</h2>

		@if ( count($team->participants) == 0  )
			<div class="alert alert-danger">
				Aucun membre dans cette équipe !
			</div>
		@else
			<table id="teams-show-table" class="table table-striped table-bordered table-hover translate" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th>Nom du membre</th>
						@if (($team->owner_id == Auth::user()->id) || Auth::user()->role == "administrator")
							<th>Actions</th>
						@endif
					</tr>
				</thead>

				<tbody>

				  	@foreach ($team->participants as $participant)
						<tr>
							@if($participant->pivot->isCaptain)
								<td data-id="{{$participant->id}}" class="clickable"> {{ $participant->last_name }} {{ $participant->first_name }} <i class="isCaptain fa fa-star" aria-hidden="true"></i></td>
							@else
								<td data-id="{{$participant->id}}" class="clickable"> {{ $participant->last_name }} {{ $participant->first_name }}</td>
							@endif
							@if (($team->owner_id == Auth::user()->id) || Auth::user()->role == "administrator")
								<td class="action">
									{{ Form::open(array('url' => route('teams.participants.destroy', [$participant->pivot['participant_id'], $participant->pivot['team_id']]), 'method' => 'delete')) }}
											<button type="submit" class="button-delete" data-type="teamMember" data-name="{{ $participant->last_name }} {{ $participant->first_name }}">
										<i class="fa fa-lg fa-trash-o action" aria-hidden="true"></i>
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
			<h2>Ajouter un membre</h2>
			@if (isset($error))
				<div class="alert alert-danger">
					{{ $error }}
				</div>
			@else
				{{ Form::open(array('url' => route('teams.participants.store',  $team->id), 'method' => 'post')) }}

					{{ Form::checkbox('isCaptain', true) }} Captain
					{{ Form::select('pepole', $dropdownList, null, ['placeholder' => 'Sélectionner', 'class' => 'form-control addMember']) }}

				{{ Form::close() }}

			@endif
		@endif

	</div>

@stop
