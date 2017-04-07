<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $developers = User::role('developer')->get();
        echo("<pre>");
        print_r($developers);
        die();


        // $role = Role::create(['name' => 'teamlead']);
        // $user = User::find(3);
        // $user->assignRole('developer');
        
        // return view('home');
    }
}
