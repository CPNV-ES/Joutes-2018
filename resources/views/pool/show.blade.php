<!-- @author Dessaules Loïc -->

@extends('layout')

@section('content')

	<div class="container">
        <a href=""><i class="fa fa-4x fa-arrow-circle-left return" aria-hidden="true"></i></a>

		<h1>
			{{$pool->tournament->name}} - Phase {{ $pool->stage }} - {{$pool->poolName}}

			@if (sizeof($pool->rankings()) > 0 && !$pool->isFinished && $ranking_completed && $games_completed && Auth::check() && (Auth::user()->role == 'administrator' || Auth::user()->role == 'writer'))
				<a class="greenBtn close-pool-btn">Terminer la poule</a>
			@endif

		</h1>

		<div id="match-block">

			<h2>Matchs et Résultats</h2>
			@if(isset($games[0]->start_time))
				<h3>{{Carbon\Carbon::parse($games[0]->start_time)->format('d.m.Y')}}</h3>
			@endif

			@if($pool->isEditable())
				{{ Form::open(array('url' => '/')) }}
				    {{ Form::text('time', '', array('id'=>'shiftTime', 'placeholder'=>'Temps en minutes')) }}
				    {{ Form::submit('Décaler les matchs', array('id'=>'shiftMatch', 'class'=>'btn-success')) }}
				{{ Form::close() }}
			@endif

			<table id="matches" data-tournament="{{$pool->tournament->id}}" data-pool="{{$pool->id}}">
				@if(count($games) > 0)
					@foreach ($games as $game)
						<tr data-game="{{$game->id}}">
							<?php /* No teams - no score */ ?>
							@if (empty($game->contender1->team) || empty($game->contender2->team))

								@if (empty($game->contender1->team))
									<td class="contender1">{{ $game->contender1->rank_in_pool . ($game->contender1->rank_in_pool == 1 ? "er " : 'ème ') . "de " . $game->contender1->fromPool->poolName }}</td>
								@else
									<td class="contender1">{{ $game->contender1->team->name }}</td>
								@endif

								<td class="score1"></td>
								<td class="separator sepTime">{{Carbon\Carbon::parse($game->start_time)->format('H:i')}}</td>
								<td class="score2">{{ $game->court->name }}</td>

								@if (empty($game->contender2->team))
									<td class="contender2">{{ $game->contender2->rank_in_pool . ($game->contender2->rank_in_pool == 1 ? "er " : 'ème ') . "de " . $game->contender2->fromPool->poolName }}</td>
								@else
									<td class="contender2">{{ $game->contender2->team->name }}</td>
								@endif

								@if($pool->isEditable())
									<td class="action"><i class="fa fa-lg fa-clock-o editTime" aria-hidden="true"></i></td>
								@endif
							@else
								<?php /* teams - no score */ ?>
								@if(!isset($game->score_contender1) || !isset($game->score_contender2))
									<td class="contender1">{{$game->contender1->team->name}}</td>
									<td class="score1"></td>
									<td class="separator sepTime">{{Carbon\Carbon::parse($game->start_time)->format('H:i')}}</td>
									<td class="score2">{{ $game->court->name }}</td>
									<td class="contender2">{{$game->contender2->team->name}}</td>
									@if($pool->isEditable())
										<td class="action"><i class="fa fa-lg fa-clock-o editTime" aria-hidden="true"></i> <i class="editScore fa fa-trophy fa-lg" aria-hidden="true"></i></td>
									@endif
								@else
									<?php /*teams and score*/ ?>
									<td class="contender1">{{$game->contender1->team->name}}</td>
									<td class="score1">{{$game->score_contender1}}</td>
									<td class="separator"> - </td>
									<td class="score2">{{$game->score_contender2}}</td>
									<td class="contender2">{{$game->contender2->team->name}}</td>
									@if($pool->isEditable())
										<td class="action"><i class="fa fa-lg fa-trophy editScore" aria-hidden="true"></i></td>
									@endif
								@endif
							@endif
						</tr>
					@endforeach
				@else

					Aucun match pour l'instant ...

			  	@endif
			</table>

			<h2>Classement actuel</h2>

			@if(sizeof($pool->rankings()) > 0)
				<table id="pool-rankings-table" class="table table-striped table-bordered translate" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th title="Position">#</th>
							<th title="Équipes">Équipes</th>
							<th title="Points">Pts</th>
							<th title="Matches gagnés">G</th>
							<th title="Matches perdus">P</th>
							<th title="Matches nuls">N</th>
							<th title="Différence but marqués / encaissés">+/-</th>
						</tr>
					</thead>
					<tbody>
						@for ($i = 0; $i < sizeof($pool->rankings()); $i++)
							<tr data-id="{{ $pool->rankings()[$i]["team_id"] }}" data-rank="{{$i+1}}">
								<td>{{$i+1}}</td>
								<td>{{$pool->rankings()[$i]["team"]}}</td>
								<td>{{$pool->rankings()[$i]["score"]}}</td>
								<td>{{$pool->rankings()[$i]["W"]}}</td>
								<td>{{$pool->rankings()[$i]["L"]}}</td>
								<td>{{$pool->rankings()[$i]["D"]}}</td>
								<td>{{$pool->rankings()[$i]["+-"]}}</td>
							</tr>
						@endfor
					</tbody>
				</table>
			@else
				Indisponible
			@endif
		</div>
	</div>
@stop
