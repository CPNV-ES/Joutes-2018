<!-- @author De la Gouj Ilias -->
@extends('layout')

@section('content')
<div class="container">
  <h1>Bonjour Maitre Dafflon</h1>

  <form  action="/notifications" method="POST">
  {{ csrf_field() }}
      <label for="title">Titre de la notification</label>
      <input type="text" id="title" name="title" placeholder="Ex: Retard du tournoi">
      <label for="description">description</label>
      <input type="text" name="description" id="description">
      <select name="team" id="team">
      @foreach ($teams as $team)
        <option value="{{$team->id}}">{{$team->name}}</option>
      @endforeach
      </select>
      <input type="submit">
  </form>
</div>
@stop