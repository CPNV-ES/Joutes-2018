<!-- @author Dessaules Loïc -->

@extends('layout')

@section('content')
	<div class="container">
		<h1>
			Terrains
			<a href="{{route('courts.create')}}" class="greenBtn" title="Créer un tournoi">Ajouter</i></a>
		</h1>
		<table id="courts-table" class="table table-striped table-bordered translate" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th>Nom</th>
					<th>Sport</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				@if(count($courts) > 0)
					@foreach ($courts as $court)
						<tr>
							<td>{{$court->name}}</td>
							<td>{{$court->sport->name}}</td>
							<td class="action">
								<a href="{{route('courts.edit',$court->id)}}" title="Éditer le terrain" class="edit"><i class="fa fa-pencil fa-lg action" aria-hidden="true"></i></a>
								{{-- {{ Form::open(array('url' => route('courts.destroy', $court->id), 'method' => 'delete')) }}
									<button type="button" class="button-delete" data-name="{{ $court->name }}" data-type="court">
					                    <i class="fa fa-trash-o fa-lg action" aria-hidden="true"></i>
					                </button>
								{{ Form::close() }} --}}
							</td>
						</tr>
					@endforeach
				@else
					<tr>
						<td>Aucun terrains pour l'instant ...</td>
					</tr>
			  	@endif
			</tbody>
		</table>

	</div>
@stop
