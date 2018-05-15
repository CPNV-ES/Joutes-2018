<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     *
     * @author Doran Kayoumi
     */
    public function index(Request $request) {

        //Check for off-line user
        if (isset($_ENV['OFFLINE'])) {
            $offline = $_ENV['OFFLINE'];
        }
        else
        {
            $offline = "NO";
        }

        $events = Event::all()->sortByDesc("id");

        // check is it's an api request
        if ($request->is('api/*')) {
            return $events;
        }

        foreach ($events as $event) {
            if (empty($event->img)) {
                $event->img = 'default.jpg';
            }
        }

        return view('event.index')->with('events', $events)->with('offline', $offline);

    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
     * @author Doran Kayoumi
     */
    public function show(Request $request, $id) {

        // check is it's an api request
        if ($request->is('api/*')) {
            return Event::findOrFail($id);
        }

        return true;
    }


}
