<!-- @author Dessauges Antoine -->

@extends('layout')

@section('content')
	<div class="container">
		<a href=""><i class="fa fa-4x fa-arrow-circle-left return" aria-hidden="true"></i></a>	
		<h1>Editer un événement</h1>

		@if ($errors->any())
			<div class="alert alert-danger">
	            @foreach ($errors->all() as $error)
	                {{ $error }}<br>
	            @endforeach
	        </div>
        @endif

		{{ Form::open(array('url' => route('events.update', $event->id), 'method' => 'put', 'id' => 'formEvent', 'enctype' => 'multipart/form-data')) }}

			<div class="form-group">
				{{ Form::label('name', 'Nom') }}
				{{ Form::text('name', $event->name, array('class' => 'form-control')) }}
			</div>
			<div class="form-group">
				{{ Form::label('img', 'Image') }}
				{{ Form::file('img', null, array('class' => 'form-control')) }}
			</div>
			<div class="send">{{ Form::button('Enregistrer', array('class' => 'btn btn-success formSend')) }}</div>

		{{ Form::close() }}

	</div>
@stop
