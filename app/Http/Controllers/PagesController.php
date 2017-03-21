<?php
namespace App\Http\Controllers;

use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\Controller;

class PagesController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function showPage()
    {
//        $user   = User::where('email', 'shaban@gems.techverx.com')->first();
//        $user->revokePermissionTo('edit articles');
//        print_r($user->hasPermissionTo('edit articles'));
        return 'hello';
    }
}