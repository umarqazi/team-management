<?php

namespace App\Http\Controllers;

use App\Sprint;
use Illuminate\Http\Request;

use App\Http\Requests;

class SprintController extends Controller
{
    public function index(){

        $sprint = Sprint::all();
        $view = View::make('sprints.view', compact('sprint'));
        return $view;
    }

    public function create(){
        return view("sprints.create");
    }

    public function store(Request $request){

        $rules = array(
            'name'       => 'required|unique:sprints|max:255',
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/sprints/create')
                ->withErrors($validator)
                ->withInput();
        }

        $sprint = new Sprint;
        $sprint->name = $request->name;
        $sprint->save();

        return redirect('/sprint');
    }

    public function edit(){
        return view('sprints.edit');
    }

    public function update( Request $request , $id){

        $rules = array(
            'name'       => 'required|unique:sprints,name,'.$id.'|max:255',
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('/sprint/'.$id.'/edit')
                ->withErrors($validator)
                ->withInput();
        }

        $sprint = Sprint::find($id);

        $sprint->name = $request->name;
        $sprint->update();

        return redirect('/sprint');
    }

    public function destroy($id){

        $sprint    = Sprint::find($id);
        $sprint->delete();

        Session::flash('message', 'Successfully deleted the Project!');
        Session::flash('alert-class', 'alert-success');
        return Redirect::to('/sprint');
    }
}
