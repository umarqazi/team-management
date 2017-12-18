<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
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
        $users = User::whereHas('roles', function($r){
            $r->where('name', '<>', 'admin');
        })->get();
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
        $permissions    = Permission::all();
        $view   = View::make('users.create', compact('roles', 'permissions'));
        return $view;
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
            'password'  =>  'required',
            'role-name'  => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('users/create')
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

            if( ! empty( Input::get( 'permissions' ) ) )
            {
                foreach(Input::get('permissions') as $permission)
                {
                    $user->givePermissionTo($permission);
                }
            }

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

        $projects   = $user->projects()->paginate(10);

        foreach ($projects as $project) {
            $teamleads   = array();
            foreach ($project->teamlead as $teamlead) {
                $teamleads[]  = $teamlead->name;
            }
            $project->teamlead  = implode('<br />', $teamleads);

            $developers = array();
            foreach ($project->developers as $developer) {
                $developers[]    = $developer->name;
            }
            $project->developers    = implode('<br />', $developers);
        }

        return view('/users.show' , compact('user', 'projects'));
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
        $permissions    = Permission::all();
        $role_permissions[] = [];
        foreach ($permissions as $permission)
        {
            if (Role::findByName($user->roles->pluck('name'))->hasPermissionTo($permission->name))
            {

                $role_permissions[] = $permission->name;
            }
        }

        // show the edit form and pass the nerd
        return view('/users.edit')->with('user', $user)->with('roles', $roles)->with('permissions', $permissions)->with('role_permissions', $role_permissions);
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

            if (Auth::user()->id == $id)
            {
                $user->name       = Input::get('name');
                $user->email      = Input::get('email');
                $user->save();

                // redirect
                Session::flash('message', 'Successfully updated the User!');
                Session::flash('alert-class', 'alert-success');
                return Redirect::to('/users');
            }

            else{
                if( Auth::user()->hasRole('admin') | Auth::user()->can('edit user') )
                {
                    $user->name       = Input::get('name');
                    $user->email      = Input::get('email');
                    $user->save();

                    $user->removeRole(Role::all());
                    $user->assignRole(Input::get('role-name'));

                    $user->revokePermissionTo(Permission::all());
                    if( ! empty( Input::get('permissions') ) )
                    {
                        foreach(Input::get('permissions') as $permission)
                        {
                            $user->givePermissionTo($permission);
                        }
                    }

                    // redirect
                    Session::flash('message', 'Successfully updated the User!');
                    Session::flash('alert-class', 'alert-success');
                    return Redirect::to('/users');
                }
                else
                {
                    // redirect
                    Session::flash('message', 'Successfully updated the User!');
                    Session::flash('alert-class', 'alert-success');
                    return Redirect::to('/home');
                }
            }
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
