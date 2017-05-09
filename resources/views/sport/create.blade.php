<!-- @author Dessaules Loïc -->

@extends('layout')

@section('content')
	<div class="container">
		<a href="{{ route('sports.index') }}"><i class="fa fa-4x fa-arrow-circle-left return" aria-hidden="true"></i></a>	
		<h1>Créer un sport</h1>

		@if ($errors->any())
			<div class="alert alert-danger">
	            @foreach ($errors->all() as $error)
	                {{ $error }}<br>
	            @endforeach
	        </div>
        @endif

		{{ Form::open(array('url' => route('sports.store'), 'method' => 'post', 'id' => 'formSport')) }}

			<div class="form-group">
				{{ Form::label('name', 'Nom') }} 
				{{ Form::text('name', null, array('class' => 'form-control')) }}
			</div>
			<div class="form-group"> 
				{{ Form::label('description', 'Description') }}
				{{ Form::text('description', null, array('class' => 'form-control')) }}
			</div>
			<div class="send">{{ Form::button('Créer', array('class' => 'btn btn-success formSend')) }}</div>

		{{ Form::close() }}

	</div>
@stop
