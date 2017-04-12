<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Session;

class UsersController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $user   = User::where('email', 'shaban@gems.techverx.com')->first();

        // $user->revokePermissionTo('edit articles');
        // echo $user->hasPermissionTo('edit articles');
        $users = User::all();
        $view   = View::make('users.index', compact('users'));
        return $view;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles  = Role::all();
        return view('/users.create')->with(array('roles' => $roles));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       // validate
        // read more on validation at http://laravel.com/docs/validation
        $rules = array(
            'name'       => 'required',
            'email'      => 'required|email|unique:users',
            'password' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('/users.create')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            // store
            $user = new User;
            $user->name       = Input::get('name');
            $user->email      = Input::get('email');
            $user->password = bcrypt(Input::get('password'));
            $user->save();

            $user->assignRole(Input::get('role-name'));

            // redirect
            Session::flash('message', 'Successfully created a user!');
            Session::flash('alert-class', 'alert-success'); 
            return Redirect::to('/users');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);

        return view('/users.show' , compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles  = Role::all();

        // show the edit form and pass the nerd
        return view('/users.edit')->with('user', $user)->with('roles', $roles);
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
        // validate
        // read more on validation at http://laravel.com/docs/validation
        $rules = array(
            'name'       => 'required',
            'email'      => 'required|email|unique:users,email,'.$id
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('/users/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            // store
            $user = User::find($id);
            $user->name       = Input::get('name');
            $user->email      = Input::get('email');
            $user->save();

            $user->removeRole(Role::all());
            $user->assignRole(Input::get('role-name'));

            // redirect
            Session::flash('message', 'Successfully updated the User!');
            Session::flash('alert-class', 'alert-success'); 
            return Redirect::to('/users');
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
        $user = User::find($id);
        $user->delete();

        // redirect
        Session::flash('message', 'Successfully deleted the user!');
        Session::flash('alert-class', 'alert-success'); 
        return Redirect::to('/users');
    }
}
