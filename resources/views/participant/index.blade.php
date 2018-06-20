<!-- @author Dessauges Antoine -->
@extends('layout')

@section('content')

	<div class="container">

		<h1 id="titleParticipant">Participants
		@if (Auth::user()->role == "administrator")
			<a href="{{route('participants.export')}}" class="greenBtn">Exporter en CSV</a>
		@endif
        </h1>

		<table id="participants-table" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">

			<thead>
				<tr>
					<th>Nom du participant</th>
					<th>Mail</th>
					<th>Sport(s)</th>
				</tr>
			</thead>

			<tbody>
				@if(count($participants) > 0)
				  	@foreach ($participants as $participant)
						<tr>
					      <td data-id="{{$participant->id}}" class="clickable">{{ $participant->last_name }} {{ $participant->first_name }}</td>
							<td><a href="mailto:{{$participant->user->email}}">{{$participant->user->email}}</a></td>
					      <td>
							@foreach ($participant->teams as $team)
								
								@if($participant->teams->last() == $team)
									{{ $team->sport->name }}
								@else
									{{ $team->sport->name }},
								@endif

							@endforeach
					      </td>
					    </tr>
					@endforeach
				@else
					<tr>
						<td>Aucun participant pour l'instant ...</td>
					</tr>
			  	@endif

		  	</tbody>

		</table>

	</div>

@stop
