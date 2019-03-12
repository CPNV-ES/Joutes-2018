<!-- @author Dessauges Antoine -->
@extends('layout')

@section('content')

	<div class="container">

		<h1 id="titleTeam">Equipes
		@if (Auth::user()->role == "administrator")
			<a href="{{route('teams.create')}}" class="greenBtn" title="Créer une équipe">Créer</a>
		@endif
		</h1>

		<table id="teams-table" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">

			<thead>
				<tr>
					<th>Nom de l'équipe</th>
					<th>Sport</th>
					<th>Complet</th>
					<th>Valide</th>
					<th>Actions</th>
				</tr>
			</thead>

			<tbody>
				@if(count($teams) > 0)
				  	@foreach ($teams as $team)
						<tr>
					      	<td data-id="{{$team->id}}" class="clickable">{{ $team->name }}</td>
					      	<td>{{ $team->sport->name }}</td>
							<td><i aria-hidden="true" class="hidden">{{ $team->isComplete() ? '1' : '0' }}</i><i  class="{{ $team->isComplete() ? 'fa fa-check' : 'fa fa-close' }}"></i></td>
							<td><i aria-hidden="true" class="hidden">{{ $team->validation ? '1' : '0' }}</i><i class="{{ $team->validation ? 'fa fa-check' : 'fa fa-close' }}"></i></td>
					      	<td class="action">
							  @if (($team->owner_id == Auth::user()->id) || Auth::user()->role == "administrator")
						      	<a href="{{ route('teams.edit', $team->id) }}" alt="Modifier la team"> <i class="fa fa-pencil fa-lg action" aria-hidden="true"></i> </a>
							  @endif
						      {{-- {{ Form::open(array('url' => route('teams.destroy', $team->id), 'method' => 'delete')) }}
						      	<button type="submit" class="button-delete">
						      		<i class="fa fa-lg fa-trash-o action" aria-hidden="true"></i>
						      	</button>
						      {{ Form::close() }} --}}
					      	</td>
					    </tr>

					@endforeach
				@else
					<tr>
						<td>Aucune équipe pour l'instant ...</td>
					</tr>
			  	@endif

		  	</tbody>

		</table>

	</div>

@stop
