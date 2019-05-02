<!-- @author Dessaules Loïc -->
@extends('layout')

@section('content')
	<div class="container boxList">
		@if ($fromEvent)
			<a href=""><i class="fa fa-4x fa-arrow-circle-left return" aria-hidden="true"></i></a>
		@endif

		@if ($fromEvent)
			<h1>
				Tournois de l'évenement {{ $event->name }}
				@if(Auth::check() && $fromEvent)
					@if(Auth::user()->role == 'administrator')
						<a href="{{route('events.tournaments.create', $event->id)}}" class="greenBtn" title="Créer un tournoi">Ajouter</i></a>
					@endif
				@endif
			</h1>
		@else
			<h1>Tournois</h1>
		@endif


		<input type="search" placeholder="Recherche" class="search form-control">

		<div class="row searchIn">

			@foreach ($tournaments as $tournament)
				<div class="col-md-4 hideSearch">
					<a href="{{route('tournaments.show', $tournament->id)}}" title="Voir le tournoi">
						<div class="box">

							<div class="imgBox">
								<img src="{{ url('tournament_img/'.$tournament->img) }}" alt="Image de l'événement">
								<div class="title name"> {{$tournament->name}} </div>
							</div>

							<div class="infos">
								<div class="sport"> {{ $tournament->sport->name }} </div>
								<div class="date"> {{$tournament->start_date->format('d.m.Y')}} | {{$tournament->start_date->format('H:i')}}-{{$tournament->end_date->format('H:i')}}</div>

								@if(Auth::check())
									@if(Auth::user()->role == 'administrator')
										<a href="{{route('tournaments.edit', $tournament->id)}}" title="Éditer le tournoi" class="edit"><i class="fa fa-lg fa-pencil action" aria-hidden="true"></i></a>
										<!--{{ Form::open(array('url' => route('tournaments.destroy', $tournament->id), 'method' => 'delete')) }}
											<button type="button" class="button-delete" data-name="{{ $tournament->name }}" data-type="tournament">
							                    <i class="fa fa-lg fa-trash-o action" aria-hidden="true"></i>
							                </button>
										{{ Form::close() }}-->
									@endif
								@endif

							</div>

						</div>
					</a>
				</div>
			@endforeach
			@if(count($tournaments) == 0)
				<div class="col-md-12">Aucun tournoi pour l'instant...</div>
			@endif
		</div>

	</div>
@stop
