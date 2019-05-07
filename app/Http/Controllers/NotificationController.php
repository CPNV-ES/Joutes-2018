<?php

namespace App\Http\Controllers;
use Validator;
use Illuminate\Http\Request;
use App\Event;
use App\Team;
use Illuminate\Support\Facades\Input;
use App\Notification;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // get team for the first event
       $teams = Event::all()->first()->teams->sortBy('name');
        return view('notification.create')->with('teams', $teams);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //check form
        $validator = Validator::make($request->all(), [
            'title'         => 'required|max:50',
            'description'   => 'required|max:255',
            'team'          => 'exists:teams,id'
        ]);
        $participants = Team::find( Input::get('team'))->participants;

        foreach($participants as $participant)
        {
            $notif                  = new Notification();
            $notif->title           = Input::get('title');
            $notif->description     = Input::get('description');
            $notif->participant_id  = $participant->id;
            $notif->viewed          = false;
            $notif->save();
        }
        
        $notifs     = Notification::all();
        $message    = "La notification a été envoyée";
    
        // return view('notification.index')->with('notifications', $notifs)->with('message', $message);
        $teams = Event::all()->first()->teams->sortBy('name');
        return view('notification.create')->with('teams', $teams);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        if ($request->is('api/*')) {
            $data = json_decode($request->getContent());
            $notif = Notification::find($id);
            $notif->viewed  = $data->viewed;
            $notif->save();
        }
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
