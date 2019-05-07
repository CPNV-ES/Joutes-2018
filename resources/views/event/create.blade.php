<!-- @author Dessauges Antoine -->

@extends('layout')

@section('content')
	<div class="container">
		<a href=""><i class="fa fa-4x fa-arrow-circle-left return" aria-hidden="true"></i></a>	
		<h1>Créer un événement</h1>

		@if ($errors->any())
			<div class="alert alert-danger">
	            @foreach ($errors->all() as $error)
	                {{ $error }}<br>
	            @endforeach
	        </div>
        @endif

		{{ Form::open(array('url' => route('events.store'), 'method' => 'post', 'class' => 'add', 'id' => 'formEvent', 'enctype' => 'multipart/form-data')) }}

			<div class="form-group">
				{{ Form::label('name', 'Nom') }}
				{{ Form::text('name', null, array('class' => 'form-control')) }}
			</div>
			<div class="form-group">
				{{ Form::label('img', 'Image') }}
				{{ Form::file('img', null) }}
			</div>
			<div class="send"> {{ Form::button('Créer', array('class' => 'btn btn-success formSend')) }} </div>

		{{ Form::close() }}


	</div>
@stop
