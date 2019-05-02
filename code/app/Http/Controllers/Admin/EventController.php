<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Event;
use Illuminate\Support\Facades\Validator;
use File;

class EventController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     *
     * @author Dessauges Antoine
     */
    public function create()
    {
        return view('event.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @author Dessauges Antoine
     */
    public function store(Request $request)
    {
        
        // create the validation rules
        $rules = array(
            'name' => 'required|min:3|max:35|unique:events,name',
            'img' => 'required|image|mimes:jpeg,png,jpg'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return view('event.create')->withErrors($validator->errors());
        } else {

            //move and rename img
            $imageName = date('YmdHis').'.'.pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
            File::move($_FILES['img']['tmp_name'], public_path().'/event_img/'.$imageName);
            
            //create and save event
            $event = new Event;
            $event->name = $request->name;
            $event->img = $imageName;
            $event->save();
            
            return redirect()->route('events.index');
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @author  Dessauges Antoine
     */
    public function edit($id)
    {
        $event = Event::find($id);
        return view('event.edit')->with('event', $event);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @author  Dessauges Antoine
     */
    public function update(Request $request, $id)
    {
        $event = Event::find($id);

        // create the validation rules
        $rules = array(
            'name' => 'required|min:3|max:35',
            'img' => 'image|mimes:jpeg,png,jpg'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return view('event.edit')->with('event', $event)->withErrors($validator->errors());
        } else {

            //move and rename img if new is choose
            if($_FILES['img']['name'] != ""){

                //delete old file
                $oldFile = $event->img;
                File::delete(public_path().'/uploads/'.$oldFile);

                //add new file
                $imageName = date('YmdHis').'.'.pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
                File::move($_FILES['img']['tmp_name'], public_path().'/event_img/'.$imageName);
                $event->img = $imageName;
            }

            $event->name = $request->input('name');
            $event->update();
            return redirect()->route('events.index');
        }
    }

    /**
     * Remove the specified event from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
     * @author Dessauges Antoine
     */
    public function destroy($id)
    {
        // $event = Event::findOrFail($id);
        // $event->delete();
        // return redirect()->route('events.index');
    }
}
