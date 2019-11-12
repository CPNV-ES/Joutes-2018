<!-- @author Yvann Butticaz --> 
@extends('layout')

@section('content')

    <div class="container singleTournament">

		<a href=""><i class="fa fa-4x fa-arrow-circle-left return" aria-hidden="true"></i></a>

		<h1 class="tournamentName">Rôles</h1>

		<div class="right">
			<div><strong>Sport :</strong> Badminton</div>

			<div><strong>Début du tournoi :</strong> 11.06.2020 à 00:00</div>
		</div>

		<div class="row">
			<div class="col-lg-6">
				<table id="tournament-teams-table" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>id</th>
							<th>Slug</th>
							<th>Nom</th>
						</tr>
					</thead>
					<tbody>
                        <tr>
                            <td data-id="1">Badboys</td>
                            <td>2</td>
                            <td><i class="fa fa-check" aria-hidden="true"></i></td>
                        </tr>
                        
                        <tr>
                            <td data-id="2">Super Nanas</td>
                            <td>2</td>
                            <td><i class="fa fa-check" aria-hidden="true"></i></td>
                        </tr>

                        <tr>
                            <td data-id="3">CPVN Crew</td>
                            <td>2</td>
                            <td><i class="fa fa-check" aria-hidden="true"></i></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@stop