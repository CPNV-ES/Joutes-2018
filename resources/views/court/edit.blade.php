<!-- @author Dessaules Loïc -->

@extends('layout')

@section('content')
	<div class="container">
		<a href=""><i class="fa fa-4x fa-arrow-circle-left return" aria-hidden="true"></i></a>
		<h1>Editer un terrain</h1>

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

		{{ Form::open(array('url' => route('courts.update', $court->id), 'method' => 'put', 'id' => 'formCourt')) }}

			<div class="form-group">
				{{ Form::label('name', 'Nom') }}
				{{ Form::text('name', $court->name, array('class' => 'form-control')) }}
			</div>
			<div class="form-group">
				{{ Form::label('acronym', 'Acronyme') }}
				{{ Form::text('acronym', $court->acronym, array('class' => 'form-control')) }}
			</div>
			<div class="form-group">
				{{ Form::label('Sport', 'Sport') }}
				{{ Form::select('sport', $dropdownList, $court->sport->id, ['placeholder' => 'Sélectionner', 'class' => 'form-control allSameStyle', 'id' => 'sport']) }}
			</div>
			<div class="send">{{ Form::button('Enregistrer', array('class' => 'btn btn-success formSend')) }}</div>

		{{ Form::close() }}

	</div>
@stop
