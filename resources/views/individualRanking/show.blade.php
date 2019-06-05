@extends('layout')

@section('content')

    <h1>Palmarès de l'équipe : </h1> <!-- echo team name -->
    <!--
        <table id="pool-rankings-table" class="table table-striped table-bordered translate" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th title="Position">#</th>
                <th title="Équipes">Équipe</th>
                <th title="Points">Pts</th>
                <th title="Matches gagnés">G</th>
                <th title="Matches perdus">P</th>
                <th title="Matches nuls">N</th>
                <th title="Différence but marqués / encaissés">+/-</th>
            </tr>
            </thead>
            <tbody>
            @for ($i = 0; $i < sizeof($pool->rankings()); $i++)
                <tr data-id="{{ $pool->rankings()[$i]["team_id"] }}" data-rank="{{$i+1}}">
                    <td>{{$i+1}}</td>
                    <td>{{$pool->rankings()[$i]["team"]}}</td>
                    <td>{{$pool->rankings()[$i]["score"]}}</td>
                    <td>{{$pool->rankings()[$i]["W"]}}</td>
                    <td>{{$pool->rankings()[$i]["L"]}}</td>
                    <td>{{$pool->rankings()[$i]["D"]}}</td>
                    <td>{{$pool->rankings()[$i]["+-"]}}</td>
                </tr>
            @endfor
            </tbody>
        </table>

    -->

@stop