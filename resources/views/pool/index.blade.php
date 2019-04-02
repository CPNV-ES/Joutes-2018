<!-- @author Dessaules Loïc -->

@extends('layout')

@section('content')

	<div class="container">
		<a href=""><i class="fa fa-4x fa-arrow-circle-left return" aria-hidden="true"></i></a>	
		<h1>Créer une pool</h1>

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

		{{ Form::open(array('url' => route('tournaments.pools.store', request()->route()->parameters), 'method' => 'post', 'id' => 'formPool')) }}
		<div class="col-lg-6">
			<table id="tournament-teams-table" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th>Liste des équipes participantes</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($tournament->teams as $team)
						@if(count($tournament->teams) > 0 && $team->isValid())
							<tr>
								@if(Auth::check() && Auth::user()->role == 'administrator')
									<td class="clickable" data-id="{{$team->id}}">{{$team->name}}</td>
								@endif
								<td>{{$team->participants()->count()}}</td>
								<td><i class="{{ $team->isComplete() ? 'fa fa-check' : 'fa fa-close' }}" aria-hidden="true"></i></td>
								<td><i class="{{ $team->isValid() ? 'fa fa-check' : 'fa fa-close' }}" aria-hidden="true"></i></td>
							</tr>
						@endif
					@endforeach
					@if(count($tournament->teams) > 0 && $team->isValid())
						<tr>
							<td>Aucune équipe pour l'instant ...</td>
						</tr>
					@endif
				</tbody>
			</table>
		</div>
		{{ Form::close() }}

	</div>
	
@endsection
