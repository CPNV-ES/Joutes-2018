@extends('layout')

@section('content')
<h1>Classement général du tournoi</h1>
    <div class="row">
        <div class="col-lg-6">
            <table id="tournament-teams-table" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Equipe</th>
                    <th>Points totaux</th>
                    <th>Matchs gagnés</th>
                    <th>Matchs perdus</th>
                    <th>Matchs nuls</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    @foreach ($ranking as $rankByTeam)
                        <td>{{$rankByTeam['rank']}}</td>
                        <td>{{$rankByTeam['team']}}</td>
                        <td>{{$rankByTeam['score']}}</td>
                        <td>{{$rankByTeam['W']}}</td>
                        <td>{{$rankByTeam['L']}}</td>
                        <td>{{$rankByTeam['D']}}</td>
                        </tr><tr>
                        @endforeach

                </tr>
                </tbody>
            </table>
        </div>
    </div>



@stop