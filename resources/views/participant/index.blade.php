<!-- @author Dessauges Antoine -->
@extends('layout')

@section('content')

	<div class="container">

		<h1 id="titleParticipant">Participants</h1>

		<table id="participants-table" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">

			<thead>
				<tr>
					<th>Nom du participant</th>
					<th>Sport(s)</th>
				</tr>
			</thead>

			<tbody>
				@if(count($participants) > 0)
				  	@foreach ($participants as $participant)
						<tr>
					      <td data-id="{{$participant->id}}" class="clickable">{{ $participant->last_name }} {{ $participant->first_name }}</td>
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
