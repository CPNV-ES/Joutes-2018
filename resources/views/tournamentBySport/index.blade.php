@extends('layout')

@section('content')
<h1>Liste des tournois par sport</h1>
<div class="container boxList">
    <input type="search" placeholder="Recherche" class="search form-control">
    <div class="row searchIn">
        <div class="col-lg-12" style="padding-bottom:100px;">
            {{ Form::open(array('url' => 'tournamentsBySport', 'method' => 'post',  'id' => 'formListSports')) }}
                <div class="form-group" style="width:250px;float:left">
                    {{ Form::label('labelListSports', 'Sport : ') }}
                    {{ Form::select('listSports', $sports,'1', array('class' => 'form-control', 'style' => 'width:200px;')) }}
                </div>
                <div class="send" style="float:left;margin-top:25px;">{{ Form::button('Afficher', array('class' => 'btn btn-success formSend')) }}</div>
            {{ Form::close() }}
        </div>
        <?php
        if (isset($tournaments))
        {
            foreach ($tournaments as $tournament)
                {
                ?>
                <div class="col-md-4 hideSearch">
                    <a href="{{route('tournaments.show', $tournament->id)}}" title="Voir le tournoi">
                        <div class="box">

                            <div class="imgBox">
                                <img src="{{ url('tournament_img/'.$tournament->img) }}" alt="Image de l'événement">
                                <div class="title name"> {{$tournament->name}} </div>
                            </div>

                            <div class="infos">
                                <div class="sport"> {{ $tournament->sport }} </div>
                                <div class="date"> {{substr($tournament->start_date,0,10)}} | {{substr($tournament->start_date,11,5)}}-{{substr($tournament->end_date,11,5)}}</div>
                            </div>

                        </div>
                    </a>
                </div>
            <?php
            }
        }
        ?>
    </div>
</div>
@stop