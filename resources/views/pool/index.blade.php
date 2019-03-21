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

		{{ Form::open(array('url' => route('tournaments.pools.store', request()->route()->parameters), 'method' => 'post', 'id' => 'formPool')) }}

		<div class="form-group">
			{{ Form::label('pool', 'Type de pool') }}
			<select class="form-control allSameStyle" id="pool" name="pool">
				<option selected>Sélectionner</option>
				@foreach($pools as $pool)
					<option value='{{ $pool->id }}'>{{ $pool->mode_description }}</option>
				@endforeach
			</select>
		</div>
		<div class="form-group">
			{{ Form::label('start_hour', 'Heure de début') }}
			{{ Form::text('start_hour', null, array('class' => 'form-control')) }}
		</div>
		<div class="form-group">
			{{ Form::label('time_match', 'Temps par match') }}
			{{ Form::text('time_match', null, array('class' => 'form-control')) }}
		</div>
		<div class="form-group">
			{{ Form::label('nb_team', 'Nombre d\'équipe') }}
			{{ Form::text('nb_team', null, array('class' => 'form-control')) }}
		</div>	

		<div class="send">{{ Form::button('Créer', array('class' => 'btn btn-success formSend')) }}</div>

		{{ Form::close() }}

	</div>
	
@endsection
