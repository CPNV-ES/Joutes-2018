<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Participant;
use App\Team;
use Illuminate\Http\Request;
use Cookie;

class ParticipantController extends Controller
{

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     * @author Dessauges Antoine
     */
    public function index()
    {
        $participants = Participant::all();
        return view('participant.index', array(
            "participants" => $participants,
        ));
    }

    /**
    * Export a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    *
    * @author Dessauges Antoine
    */
   public function export(Request $request)
   {
       $participants = Participant::all();
       
       $exporter = new \Laracsv\Export();
       $csv = $exporter->getCsv();
       $csv->setDelimiter("\t");

       $exporter->beforeEach(function ($participant) {
           $participant->team_names = $participant->teams->implode('name', ', ');
           $participant->sport_names = $participant->teams->implode('sport.name', ', ');
           $participant->email = $participant->user ? $participant->user->email : '';
       });
       $output = $exporter->build($participants, ['first_name', 'last_name', 'email', 'team_names', 'sport_names']);
       
       return response(\League\Csv\Reader::BOM_UTF16_LE . mb_convert_encoding($csv->getContent(), 'UTF-16LE', 'UTF-8'))->header('Content-Type', 'text/csv');
   }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
     * @author Dessauges Antoine
     */
    public function show($id)
    {
        $participant = Participant::find($id);
        $teams = Team::all();
        $error = $infos = null;

        $dropdownList = array(); 

        //get all team where the member is not in
        $teamOfThisMember = array();
        foreach ($participant->teams as $teamMember) {
            array_push($teamOfThisMember, $teamMember->name);
        }
        for ($i=0; $i < sizeof($teams); $i++) { 

            if(count($participant->teams) == 0 || !in_array($teams[$i]->name, $teamOfThisMember)) //if this current team is not in the teams of the member
                $dropdownList[$teams[$i]->id] = $teams[$i]->name;
        
        }//for

         if(empty($dropdownList))
            $error = "Aucune team ne peut être ajouté à ce participant car il fait déjà partis de toutes les teams !";

        if(Cookie::get('infos') != null){
            $infos = Cookie::get('infos');
            Cookie::queue(Cookie::forget('infos')); //delete cookie
        }

        return view('participant.show', array(
            'participant'  => $participant,
            'dropdownList' => $dropdownList,
            'error'        => $error,
            'infos'        => $infos,
        ));


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
