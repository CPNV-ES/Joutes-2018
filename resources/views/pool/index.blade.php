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
		<div class="row">
			<div class="col-lg-4">
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
								</tr>
							@endif
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
		{{ Form::open(array('url' => route('tournaments.pools.store', request()->route()->parameters), 'method' => 'post', 'id' => 'formPool')) }}
		<div class="col-lg-4">
			<div class="form-group">
				<label>Nom de la phase</label>
				<input name="poolName" type="text" class="form-control">
			</div>
			<div class="form-group">
				<label>Phase</label>
				<input name="stage" type="number" class="form-control">
			</div>
			<div class="form-group">
				<label>Taille de la pool</label>
				<input name="pool" type="number" class="form-control">
			</div>
			<div class="form-group">
				<label>Mode du tournoi</label>
				<select name="mode_tounament" class="form-control">
					@foreach($poolModes as $poolMode)
						<option value='{{ $poolMode->id }}'>{{ $poolMode->mode_description }}
					@endforeach
				</select>
			</div>
			<input type="hidden" name="isFinished" value="0">
			<input type="hidden" name="game_type_id" value="1">
			<div class="send">
				<button type="submit" class="btn btn-success formSend">Créer</button>
			</div>
		</div>
		{{ Form::close() }}

	</div>
	
@endsection
