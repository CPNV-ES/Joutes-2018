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
                            <div class="title name"> Vos Equipes </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 hideSearch">
                <div class="box">
                    <div class="imgBox">
                        <a href="" title="Voir les match">
                            <img src="/images/sport.png" alt="Image du sport">
                            <div class="title name"> Prochaine Match </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 hideSearch">
                <div class="box">
                    <div class="imgBox">
                        <a href="" title="Voir les inscription">
                            <img src="/images/inscription.jpg" alt="Image du sport">
                            <div class="title name"> Comunications </div>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@stop
