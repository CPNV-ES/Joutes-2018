@extends('layout')

@section('content')

    <div class="row">
        <div class="col-lg-6">
            <table id="tournament-teams-table" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Prénom Nom</th>
                    <th>Equipe</th>
                    <th>Points par match</th>
                    <th>Points totaux</th>
                    <th>Matchs gagnés</th>
                    <th>Matchs perdus</th>
                    <th>Matchs nuls</th>
                </tr>
                </thead>
                <tbody>
                <!--if(count($tournament->teams) > 0)
                    foreach ($tournament->teams as $team)-->
                        <tr>
                            <td data-id=""></td>
                            <td></td>
                        </tr>
                </tbody>
            </table>
        </div>



@stop