<!-- @author Davide Carboni -->
@extends('layout')

@section('content')
    <div class="container boxList">

        <div class="row">

            <div><h1>{{ Auth::user()->username }}</h1></div>

            <div class="col-md-4 hideSearch">
                <div class="box">
                    <div class="imgBox">
                        <a href="{{ route('profile.teams.index', $participant->id) }}" title="Voir l'événement">
                            <img src="/images/teams.jpg" alt="Image du sport">
                            <div class="title name"> Vos équipes </div>
                        </a>
                    </div>
                </div>
            </div>

            {{ Form::open(array('url' => route('profile.destroy', $participant->id), 'method' => 'delete')) }}
            <div class="col-md-4 hideSearch button-delete" data-type="participantSigin">
                <div class="box" >
                    <div class="imgBox">
                        <a href="" title="New Sigin">
                            <img src="/images/new.png" alt="Image du sport">
                            <div class="title name">Refaire mon inscription </div>
                        </a>
                    </div>
                </div>
            </div>
            {{ Form::close() }}


            <div class="col-md-4 hideSearch">
                <div class="box">
                    <div class="imgBox">
                        <a href="{{ route('profile.edit', $participant->id) }}" title="Change les inscription">
                            <img src="/images/transfer.png" alt="Image du sport">
                            <div class="title name"> Changer d'équipe </div>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@stop
