<!-- @author Davide Carboni -->
@extends('layout')

@section('content')
    <div class="container boxList">



        <div class="row">

            @if ($participant->isUnsigned($participant->id))
                @include ('profile.check',['$participant' => $participant])
            @else
                @include ('profile.control',['$participant' => $participant]);
            @endif

        </div>
    </div>
@stop
