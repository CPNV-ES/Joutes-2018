@extends('layout')

@section('content')
<h1>Liste des tournois par sport</h1>
<div class="container boxList">
    <input type="search" placeholder="Recherche" class="search form-control">
    <div class="row searchIn">
        <div class="col-lg-12" style="padding-bottom:100px;">
            {{ Form::open(array('url' => 'tournamentClassification', 'method' => 'post',  'id' => 'formListSports')) }}
                <div class="form-group" style="width:250px;float:left">
                    {{ Form::label('labelListSports', 'Sport : ') }}
                    {{ Form::select('listSports', $sports,'1', array('class' => 'form-control', 'style' => 'width:200px;')) }}
                </div>
                <div class="send" style="float:left;margin-top:25px;">{{ Form::button('Afficher', array('class' => 'btn btn-success formSend')) }}</div>
            {{ Form::close() }}
        </div>
        <div class="col-md-4 hideSearch">
            <?php
            // If the user choosed a tournament in the list and send it, will display the list of every tournament in this sport, order by date (descending)
            if (isset($tournaments))
            {
                $year = 0000;
                foreach ($tournaments as $tournament)
                    {
                        // If the year is different than the previous one, will display a date title.
                        if (substr($tournament->start_date,0,4) != $year)
                        {
                            echo'<h1>';
                            echo substr($tournament->start_date,0,4);
                            echo'</h1>';
                        }
                        $year = substr($tournament->start_date,0,4)
                        ?>
                    <a href="{{route('tournamentClassification.show', $tournament->id)}}" title="Voir le tournoi">
                        <div class="box">

                            <div class="imgBox">
                                <img src="{{ url('tournament_img/'.$tournament->img) }}" alt="Image de l'événement">
                                <div class="title name"> {{$tournament->name}} </div>
                            </div>
                    </a>
                            <div class="infos"  style="padding-top:0px;padding-bottom:100px;">
                                <div class="col-lg-5 sport"> {{ $tournament->sport }} </div>
                                <div class="col-lg-7 date"> {{substr($tournament->start_date,0,10)}} | {{substr($tournament->start_date,11,5)}}-{{substr($tournament->end_date,11,5)}}</div>
                                @if(Auth::check())
                                    @if(Auth::user()->role == 'administrator')
                                        {{ Form::open(array('action' => 'TournamentClassificationController@index', 'method' => 'post',  'id' => 'formDuplicateTournament')) }}
                                            <div class="col-lg-12">Utiliser comme base pour nouveau tournoi de :</div>
                                            <div class="col-lg-6">{{ Form::select('listSports', $sports,'1', array('class' => 'form-control', 'style' => 'width:130px;height:28px;')) }}</div>
                                            <div class="col-lg-6">dans l'évenement :</div>
                                            <div class="col-lg-8">{{ Form::select('listEvents', $events->toArray(),'1', array('class' => 'form-control', 'style' => 'width:130px;height:28px;')) }}</div>
                                            <div class="col-lg-4">{{ Form::submit('Dupliquer') }}</div>
                                        {{ Form::close() }}
                                    @endif
                                @endif

                            </div>
                        </div>


                <?php
                }
            }
            ?>
        </div>
    </div>
</div>
@stop