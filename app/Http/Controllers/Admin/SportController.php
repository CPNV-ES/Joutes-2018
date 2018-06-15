<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Sport; // This is the linked model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SportController extends Controller
{
    /**
     * Display a listing of the sports.
     *
     * @return \Illuminate\Http\Response
     *
     * @author Dessaules Loïc
     */
    public function index()
    {
        $sports = Sport::all();
        return view('sport.index')->with('sports', $sports);
    }

    /**
     * Show the form for creating a new sport.
     *
     * @return \Illuminate\Http\Response
     *
     * @author Dessaules Loïc
     */
    public function create()
    {
        return view('sport.create');
    }

    /**
     * Store a newly created sport in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @author Dessaules Loïc
     */
    public function store(Request $request)
    {   

        /* LARAVEL VALIDATION */
        // create the validation rules
        $rules = array(
            'name' => 'required|min:3|max:35|unique:sports,name',
            'description' => 'max:45',
            'min_participant' => 'required|integer',
            'max_participant' => 'required|integer'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return view('sport.create')->withErrors($validator->errors());
        } else {
            $sport = new Sport();
            $sport->name = $request->input("name");
            $sport->description = $request->input("description");
            $sport->min_participant = $request->input("min_participant");
            $sport->max_participant = $request->input("max_participant");
            $sport->save();
            return redirect()->route('sports.index');
        }
    }

    /**
     * Display the specified sport.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
     * @author Dessaules Loïc
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified sport.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
     * @author Dessaules Loïc
     */
    public function edit($id)
    {
        $sport = Sport::find($id);
        return view('sport.edit')->with('sport', $sport);
    }

    /**
     * Update the specified sport in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
     * @author Dessaules Loïc
     */
    public function update(Request $request, $id)
    {   
        $sport = Sport::find($id);


        /* CUSTOM SPECIFIC VALIDATION */
        $customError = null;
        // Check if the name already exists AND is not the same between the form POST and the DB 
        // This way, we can edit just the description and save the same name, but we cannot save the same name as an other sport on DB 
        if($sport->name != $request->input('name') && Sport::where('name', '=', $request->input('name'))->exists()){ 
            $customError = 'le sport "'.$request->input('name').'"'.' existe déjà.'; 
        } 


        /* LARAVEL VALIDATION */
        // create the validation rules
        $rules = array(
            'name' => 'required|min:3|max:35',
            'description' => 'max:45' 
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails() || !empty($customError)) {
            return view('sport.edit')->withErrors($validator->errors())->with('sport', $sport)->with('customError', $customError);
        } else { 
            $sport->update($request->all());
            return redirect()->route('sports.index');
        }

    }

    /**
     * Remove the specified sport from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
     * @author Dessaules Loïc
     */
    public function destroy($id)
    {
        $sport = Sport::findOrFail($id);
        $sport->delete();
        return redirect()->route('sports.index');
    }
}
