<!-- @author Dessaules Loïc -->

@extends('layout')

@section('content')

<div class="container">
		<a href=""><i class="fa fa-4x fa-arrow-circle-left return" aria-hidden="true"></i></a>	
		<h1>Créer une pool</h1>

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

		{{ Form::open(array('url' => route('tournaments.pools.store', ['tournament' => request()->id_sport]), 'method' => 'post', 'id' => 'formPool')) }}

			<div class="form-group">
				{{ Form::label('Sport', 'Sport') }}
				<select class="form-control allSameStyle" id="sport" name="sport">
					<option selected>Sélectionner</option>
				</select>
			</div>
			<div class="form-group">
				{{ Form::label('name', 'Nom') }}
				{{ Form::text('name', null, array('class' => 'form-control')) }}
			</div>
			<div class="form-group">
				{{ Form::label('acronym', 'Acronyme') }}
				{{ Form::text('acronym', null, array('class' => 'form-control')) }}
			</div>
			

			<div class="send">{{ Form::button('Créer', array('class' => 'btn btn-success formSend')) }}</div>

		{{ Form::close() }}

	</div>
	
@endsection
