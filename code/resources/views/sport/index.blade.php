<!-- @author Dessaules Loïc -->

@extends('layout')

@section('content')
	<div class="container">
		<h1>
			Sports
			<a href="{{route('sports.create')}}" class="greenBtn" title="Créer un sport">Ajouter</i></a>
		</h1>
		<table id="sports-table" class="table table-striped table-bordered translate" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th>Nom</th>
					<th>Description</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				@if(count($sports) > 0)
					@foreach ($sports as $sport)
						<tr>
							<td>{{$sport->name}}</td>
							<td>{{$sport->description}}</td>
							<td class="action">
								<a href="{{route('sports.edit',$sport->id)}}" title="Éditer le sport" class="edit"><i class="fa fa-lg fa-pencil action" aria-hidden="true"></i></a>
								<!--{{ Form::open(array('url' => route('sports.destroy', $sport->id), 'method' => 'delete')) }}
									<button type="button" class="button-delete" data-name="{{ $sport->name }}" data-type="sport">
					                    <i class="fa fa-lg fa-trash-o action" aria-hidden="true"></i>
					                </button>
								{{ Form::close() }}-->
							</td>
						</tr>
					@endforeach
				@else
					<tr>
						<td>Aucun sport pour l'instant ...</td>
					</tr>
			  	@endif
			</tbody>
		</table>

	</div>
@stop
