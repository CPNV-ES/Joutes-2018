<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Team;
use App\Participant;
use Cookie;
use URL;
use Illuminate\Support\Facades\Input;

use Illuminate\Http\Request;

class TeamParticipantController extends Controller
{

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $idParticipant
     * @param  int idTeam
     * @return \Illuminate\Http\Response
     *
     * @author Dessauges Antoine
     */
    public function destroy($idParticipant, $idTeam)
    {
        $team = Team::find($idTeam);

        $participant = Participant::find($idParticipant);
        $participantName = $participant->last_name. " " .$participant->first_name;

        $team->participants()->detach($idParticipant); //delete the row in intemrediate table

        //redirect to the correct page with infos message
        if (URL::previous() === URL::route('teams.show', ['id' => $idTeam])){

            $infosMessage = 'Le participant "'. $participantName .'" à bien été supprimer de la team "'. $team->name .'" !';
            Cookie::queue('infos', $infosMessage, 1);

            return redirect()->route('teams.show', ['id' => $idTeam]);
        }
        else{

            $infosMessage = 'Le participant "'. $participantName .'" à bien été supprimer de la team "'. $team->name .'" !';
            Cookie::queue('infos', $infosMessage, 1);

            return redirect()->route('participants.show', ['id' => $idParticipant]);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @author Dessauges Antoine
     */
    public function store(Request $request, $id)
    {
        $isCaptain = $request->input('isCaptain') ? true : false;

        //redirect to the correct page with message
        if (URL::previous() === URL::route('teams.show', ['id' => $id])){

            $idParticipant = $request->input('pepole'); 
            $team = Team::find($id);
            
            $team->participants()->attach([$idParticipant => array('isCaptain' => $isCaptain )]); //add the row in intemrediate table 

            $participant = Participant::find($idParticipant);
            $participantName = $participant->last_name. " " .$participant->first_name;

            $infosMessage = 'Le participant "'. $participantName .'" à bien été ajouté à la team "'. $team->name .'" !';
            Cookie::queue('infos', $infosMessage, 1);

            return redirect()->route('teams.show', ['id' => $id]);
        }
        else{

            $idTeam= $request->input('team'); 
            $team = Team::find($idTeam);

            $team->participants()->attach([$id => array('isCaptain' => $isCaptain )]); //add the row in intemrediate table 

            $participant = Participant::find($id);
            $participantName = $participant->last_name. " " .$participant->first_name;

            $infosMessage = 'Le participant "'. $participantName .'" à bien été ajouté à la team "'. $team->name .'" !';
            Cookie::queue('infos', $infosMessage, 1);

            return redirect()->route('participants.show', ['id' => $id]);
        }

    }

}
