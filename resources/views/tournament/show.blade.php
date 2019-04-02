<!-- @author Dessaules Loïc -->

@extends('layout')

@section('content')
	<div class="container singleTournament">

		<a href=""><i class="fa fa-4x fa-arrow-circle-left return" aria-hidden="true"></i></a>

		<h1 class="tournamentName">
			{{ $tournament->name }}
			@if (Auth::check() && (Auth::user()->role == 'administrator' || Auth::user()->role == 'writer'))
				<a href="{{ route('tournaments.schedule.index', $tournament->id) }}" class="greenBtn big-screen" title="Affichage écran géant">Affichage écran geant</i></a>
			@endif
		@if ( Auth::check() && (Auth::user()->role == "administrator"))
			<a href="{{route('tournaments.export', $tournament->id)}}" class="greenBtn">Exporter en CSV les équipes et participants</a>
		@endif
		</h1>

		<div class="right">
			<div>
				@if(isset($tournament->sport))
					<strong>Sport :</strong> {{ $tournament->sport->name }}
				@else
					<strong>Sport :</strong> Aucun, veuillez en choisir un.
				@endif
			</div>

			<div><strong>Début du tournoi :</strong> {{ $tournament->start_date->format('d.m.Y à H:i') }}</div>
		</div>




		@if(Auth::check())
		@if(Auth::user()->role == 'administrator')
			@if ($participants != array())

		<!-- Vérifie la connexion en tant qu'admin.
		Affiche une liste et un bouton permettant de désigner une personne comme Manager de tournoi
		Affiche également la vue permettant de duplique la formule de ce tournoi sur un autre. (fonctionnalité n2)
		-->
		{{ Form::open(array('url' => 'tournaments/'.$tournament->id.'/addManager', 'method' => 'post')) }}
		<div class="col-lg-12">
			<div class="col-lg-4">
				{{ Form::label('newManager', 'Désigner un responsable de ce tournoi') }}
			</div>
			<div class="col-lg-8">
				{{ Form::label('duplicateTournament', 'Dupliquer la formule de ce tournoi sur : ') }}
			</div>
			<div class="form-group col-lg-2">
				{{ Form::select('userID', array($participants), null, ['style' => 'height:38px;' ]) }}
			</div>
			<div class="form-group col-lg-2">
				{{ Form::submit('Enregister', array('class' => 'btn btn-success formSend')) }}
			</div>
			{{ Form::Close() }}

			<div class="form-group col-lg-2">
				{{ Form::select('userID', array('test'), null, ['style' => 'height:38px;' ]) }}
			</div>
			<div class="form-group col-lg-2">
				{{ Form::submit('Enregister', array('class' => 'btn btn-success formSend')) }}
			</div>
		</div>

			@else
					<br><div class="form-group col-lg-12"><b>Aucun inscrit au tournoi. Impossible de désigner un responsable</b></div>
			@endif
		@endif
		@endif


		<div class="row"><br>
		<!-- Vérifie que la personne connectée est un manager. (admin pr test) -->
		@if(Auth::check())
		@if(Auth::user()->role == 'administrator')
		{{ Form::open(array('url' => 'tournaments/'.$tournament->id.'/addNews', 'method' => 'post')) }}
		<div class="col-lg-10">
			<div class="form-group">
				{{ Form::label('newsLabel', 'Publier une actualité') }}
				{{ Form::textarea('news', "", array('class' => 'form-control', "style" => "height:150px;")) }}
			</div>
		</div>
		<div class="col-lg-1" style="margin-top: 150px;">
			<div class="form-group" style="">
				{{ Form::checkbox('status', 'isUrgent') }}
				{{ Form::label('statusLabel', "Urgent", array('style' => 'font-size: 12pt;')) }}
			</div>
		</div>
		<div class="col-lg-1" style="margin-top: 150px;">
			<div class="send">
				{{ Form::submit('Publier', array('class' => 'btn btn-success formSend')) }}
			</div>
		</div>
		{{ Form::Close() }}
		@endif
		@endif



			<!-- Affichage des news -->
			<h4>Informations</h4><br>
			<div class="col-lg-12" style="overflow:auto; height:165px;margin-bottom: 50px;border-bottom: solid;border-top:solid;padding:0;">
				@foreach ($news as $singleNews)
					<div class="col-lg-12" style="border-style: solid;">
						<div class="col-lg-6" style="height:40px;"><h4>Responsable du tournoi</h4></div>
						<div class="col-lg-3" style="height:40px;"><h5>Il y a une heure</h5></div>
						<div class="col-lg-3" style="height:40px;"><h5>{{ $singleNews['creation_datetime'] }}</h5></div>

						<div class="col-lg-12"><h5>{{ $singleNews['content']}}</h5></div>
					</div>
				@endforeach
			</div>

			<div class="col-lg-6">
				<table id="tournament-teams-table" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Liste des équipes participantes</th>
							<th>Nb participants</th>
							<th>Complet</th>
							<th>Valide</th>
						</tr>
					</thead>
					<tbody>
						@if(count($tournament->teams) > 0)
					  		@foreach ($tournament->teams as $team)
					  			<tr>
					  				@if(Auth::check() && Auth::user()->role == 'administrator')
   										<td class="clickable" data-id="{{$team->id}}">{{$team->name}}</td>
									@else
										<td data-id="{{$team->id}}">{{$team->name}}</td>
									@endif
									<td>{{$team->participants()->count()}}</td>
        							<td><i class="{{ $team->isComplete() ? 'fa fa-check' : 'fa fa-close' }}" aria-hidden="true"></i></td>
        							<td><i class="{{ $team->isValid() ? 'fa fa-check' : 'fa fa-close' }}" aria-hidden="true"></i></td>
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

			<div class="col-lg-6">
				<table id="tournament-courts-table" class="table table-striped table-bordered translate" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Liste des terrains</th>
						</tr>
					</thead>
					<tbody>
						@if(count($tournament->sport->courts) > 0)
							@foreach ($tournament->sport->courts as $court)
					  		<tr>
								<td class="clickable">{{$court->name}}</td>
							</tr>
							@endforeach
						@else
							<tr>
								<td>Aucun terrain pour l'instant ...</td>
							</tr>
						@endif
					</tbody>
				</table>
			</div>
		</div>

		<h2>Visualisation du tournoi</h2>

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
						@for ($i = 1; $i <= $totalStage; $i++)
							<td class="noPadding">
								<table id="pools-table" class="table-hover table-striped table-bordered" width="100%" data-tournament="{{$tournament->id}}">
									<tbody>
										@foreach ($pools as $pool)
											@if ($pool->stage == $i)
											<tr>
												<td data-id="{{$pool->id}}" class="clickable">{{$pool->poolName}}</td>
											</tr>
											@endif
										@endforeach
									</tbody>
								</table>
							</td>
						@endfor
					</tr>


				</tbody>
			</table>
		@else
			Indisponible pour le moment ...
		@endif


@stop
