@extends('layout')

@section('content')

    <h1>Palmarès individuel</h1>
    <div class="row">
        <div class="col-lg-6">
            <table id="tournament-teams-table" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Equipe</th>
                    <th>Classement</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($ranking as $rank)
                        <td>1</td>
                        <td>{{$rank['team']}}</td>
                        <td>{{$rank['rank']}}</td>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>





@stop