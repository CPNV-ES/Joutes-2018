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
								</tr>
							@endif
						@endforeach
					</tbody>
				</table>
			</div>
		
			{{ Form::open(array('url' => route('tournaments.pools.store', request()->route()->parameters), 'method' => 'post', 'id' => 'formPool')) }}
			<div class="col-lg-6">
				<div class="form-group">
					<label>Nom de la phase</label>
					<input name="poolName" type="text" class="form-control">
				</div>
				<div class="form-group">
					<label>Phase</label>
					<input name="stage" type="number" class="form-control">
				</div>
				<div class="form-group">
					<label>Nombre de pool par phase</label>
					<input name="pool" type="number" class="form-control">
				</div>
				<div class="form-group">
					<label>Nombre d'équipe par pool</label>
					<input name="nb_team" type="number" class="form-control">
				</div>
				<input type="hidden" name="isFinished" value="0">
				<div class="send">
					<button type="submit" class="btn btn-success formSend">Créer</button>
				</div>
			</div>
			{{ Form::close() }}
		</div>

		<div class="col-lg-12" style="padding-top: 10px;">

			<!-- Stages and pools -->
			@if (sizeof($tournament->pools) > 0)

				<table class="table pools">
					<thead>
						<tr>
							<th class="sizedTh"></th>
							@for ($i = 1; $i <= $totalStage; $i++)

								<th class="nav-item">
									Phase {{$i}}
								</th>

							@endfor
						</tr>
					</thead>
					<tbody>
						<tr>
							<th class="verticalText"><span>Poules</span></th>
							@for ($i = 0; $i < $totalStage; $i++)
								<td class="noPadding">
									<table id="pools-table" class="table-hover table-striped table-bordered" width="100%" data-tournament="{{$tournament->id}}">
										<tbody>
											@for ($j = 1; $j <= $pools[$i]->poolSize; $j++)
												
												<tr>
													<td data-id="" class="clickable">1</td>
												</tr>
											@endfor
										</tbody>
									</table>
								</td>
							@endfor
						</tr>
					</tbody>
				</table>
			@endif

		</div>

	</div>
@endsection