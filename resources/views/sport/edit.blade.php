<!-- @author Dessaules Loïc -->

@extends('layout')

@section('content')
	<div class="container">
		<a href=""><i class="fa fa-4x fa-arrow-circle-left return" aria-hidden="true"></i></a>
		<h1>Editer un sport</h1>

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

		{{ Form::open(array('url' => route('sports.update', $sport->id), 'method' => 'put',  'id' => 'formSport')) }}

			<div class="form-group">
				{{ Form::label('name', 'Nom : ') }}
				{{ Form::text('name', $sport->name, array('class' => 'form-control')) }}
			</div>
			<div class="form-group">
				{{ Form::label('description', 'Description : ') }}
				{{ Form::text('description', $sport->description, array('class' => 'form-control')) }}
			</div>
			<div class="form-group">
				{{ Form::label('min_participant', 'Min Participant') }}
				{{ Form::text('min_participant',$sport->min_participant , array('class' => 'form-control')) }}
			</div>
			<div class="form-group">
				{{ Form::label('max_participant', 'Max participant') }}
				{{ Form::text('max_participant', $sport->max_participant, array('class' => 'form-control')) }}
			</div>

			<div class="send">{{ Form::button('Enregistrer', array('class' => 'btn btn-success formSend')) }}</div>

		{{ Form::close() }}

	</div>
@stop
