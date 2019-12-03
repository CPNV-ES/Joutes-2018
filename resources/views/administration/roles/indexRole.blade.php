<!-- @author Yvann Butticaz --> 
@extends('layout')

@section('content')

    <div class="container roleManagement">

		<a href=""><i class="fa fa-4x fa-arrow-circle-left return" aria-hidden="true"></i></a>

		<h1 class="title">Rôles</h1>

        <a href="{{route('roles.create')}}" class="greenBtn addRoles" title="Créer un role">Ajouter</i></a>

		<div class="row">
			<div class="col-lg-6">
				<table id="roles-table" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>id</th>
							<th>Slug</th>
							<th>Nom</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
                        @foreach ($roles as $role)
                            <tr>
                                <td>{{$role->id}}</td>
                                <td>{{$role->slug}}</td>
                                <td>{{$role->name}}</td> 
                                <td class="text-center" style="font-size:16px;">
                                    <a href="{{ route('roles.edit', $role->id)}}" title="Éditer le role" class="edit" style="margin-right: 10%;">
                                        <i class="fa fa-lg fa-pencil action" aria-hidden="true"></i>
                                    </a>

                                    {{ Form::open(array('url' => route('roles.destroy', $role->id), 'method' => 'delete', 'style' => '
                                    display: inline-block;')) }}
                                        <button type="submit" class="button-delete" data-name="{{ $role->name }}" data-type="role">
                                            <i class="fa fa-trash-o fa-lg action" aria-hidden="true"></i>
                                        </button>
								    {{ Form::close() }}
                                </td>
                            </tr>
                        @endforeach
                       
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@stop


@section('pageSpecificJs')

    <script src="{{ asset('js/tables.js') }}"></script>

@stop