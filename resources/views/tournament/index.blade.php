@extends('layout')

@section('content')
	<div id="container">
		<a href="/"><img src="{{ asset("images/return-arrow.png") }}" alt="Retour en arrière" class="return"></a>
		<h1>Tournois</h1>
		<table>
			<tr>
				<th>Nom</th>
				<th>Action</th>
			</tr>
			@foreach ($tournaments as $tournament)
				<tr>
					<td class="name" style="width:80%">{{$tournament->name}}</td>
					<td class="action">
						<a href="{{route('tournaments.edit',$tournament->id)}}" title="Éditer le tournoi" class="edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>
						{{ Form::open(array('url' => route('tournaments.destroy', $tournament->id), 'method' => 'delete')) }}
							<button type="button" class="button-delete" data-name="{{ $tournament->name }}" data-type="tournament">
			                    <i class="fa fa-trash-o" aria-hidden="true"></i>
			                </button>
						{{ Form::close() }}
					</td>
				</tr>
			@endforeach
		</table>

		<br>

		<a href="{{route('tournaments.create')}}" title="Créer un tournoi"><input type="button" value="Nouveau", class="btn btn-primary"></a>

	</div>
@stop