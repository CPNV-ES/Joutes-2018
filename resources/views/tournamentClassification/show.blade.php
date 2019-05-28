@extends('layout')

@section('content')
<h1>Classement général du tournoi</h1>
    <div class="row">
        <div class="col-lg-6">
            @if (isset($ranking))
                <table id="tournament-teams-table" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Classement</th>
                        <th>Equipe</th>
                        <!--<th>Points totaux</th>
                        <th>Matchs gagnés</th>
                        <th>Matchs perdus</th>
                        <th>Matchs nuls</th>-->
                    </tr>
                    </thead>
                    <tbody>

                            @foreach ($ranking as $rankByTeam)
                                {{ Form::open(array('url' => 'individualRanking', 'method' => 'post',  'id' => 'formAccessTeamRanking')) }}
                                <tr>
                                    <td>{{$rankByTeam['rank']}}</td>
                                    <td>{{$rankByTeam['team']}}</td>
                                    {{ Form::hidden('team',$rankByTeam['team']) }}
                                </tr>
                                {{ Form::close }}
                            @endforeach

                    </tbody>
                </table>
            @else
                <h3>Le tournoi n'est pas encore fini ...</h3>
            @endif
        </div>
    </div>


@stop