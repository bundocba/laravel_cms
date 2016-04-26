<?php

namespace App\Http\Controllers\inside;

use App\Http\Controllers\Controller;
use App\Helpers\AuthItemHelper;
use App\Http\Models\inside\AuthPermission;
use App\Http\Models\inside\UserGroup;
use Auth;
use Redirect;
use Route;

class BaseController extends Controller {

    public function __construct() {
        $user = Auth::user();
        view()->share('loggedUser', $user);
        view()->share('loggedUserProfile', $user->profile()->first());
        view()->share('loggedUserGroup', UserGroup::getById($user->user_group_id));
        
        $myPermission = AuthPermission::getAllPermissions($user->user_group_id);
        view()->share('myPermission', $myPermission);
        $currentRoute = Route::current()->getAction();
        view()->share('prefix', $currentRoute['prefix']);
        $as = $currentRoute['as'];

        if (!AuthItemHelper::canDo($myPermission, $as)) {
            return view('inside.commons.auth-error');
        }
    }

}
