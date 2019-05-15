@extends('layout')

@section('content')
<h1>Liste des tournois par sport</h1>
<div class="container boxList">
    <input type="search" placeholder="Recherche" class="search form-control">
    <div class="row searchIn">
       {{ Form::open(array('url' => 'tournamentsBySport', 'method' => 'put',  'id' => 'formListSports')) }}
            <div class="form-group" style="width:250px;float:left">
                {{ Form::label('labelListSports', 'Sport : ') }}
                {{ Form::select('listSports', array('0' => 'bonjour','1' => 'monsieur'),'0', array('class' => 'form-control', 'style' => 'width:200px;')) }}
            </div>
            <div class="send" style="float:left;margin-top:25px;">{{ Form::button('Enregistrer', array('class' => 'btn btn-success formSend')) }}</div>
        {{ Form::close() }}


    </div>
</div>
@stop