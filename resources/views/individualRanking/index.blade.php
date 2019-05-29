@extends('layout')

@section('content')

    <h1>Palmarès individuel</h1>
    <div class="row">
        <div class="col-lg-6">
            <table id="tournament-teams-table" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Tournoi</th>
                    <th>Equipe</th>
                    <th>Classement</th>
                </tr>
                </thead>
                <tbody>

                @foreach ($ranking as $rank)
                    {{ Form::open(array('url' => 'individualRanking', 'method' => 'post',  'id' => 'formAccessTeamRanking')) }}
                        <tr>
                            <td>1</td>
                            <td>{{$rank['tournament']}}</td>
                            <td>{{$rank['team']}} {{ Form::submit("Voir résultats de l'équipe") }}</td>
                            <td>{{$rank['rank']}} {{ Form::hidden('team',$rank['team']) }}</td>
                        </tr>
                    {{ Form::close() }}
                @endforeach

                </tbody>
            </table>
        </div>
    </div>





@stop