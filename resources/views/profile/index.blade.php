<!-- @author Davide Carboni -->
@extends('layout')

@section('content')
    <div class="container boxList">

        <h1>{{ Auth::user()->username }}</h1>

        <div>
            <h1>
                Equipes
                <a href="{{route('teams.create')}}" class="greenBtn" title="Créer un equipes">Céer</a>
            </h1>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <table id="tournament-teams-table" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Liste des vos équipes</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($teams) > 0)
                        @foreach ($teams as $team)
                            <tr>
                                    <td class="clickable" data-id="{{$team->id}}">{{$team->name}}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td>Aucune équipe pour l'instant ...</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
