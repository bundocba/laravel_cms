<?php

namespace App\Http\Controllers\Inside\Auth;

use App\Http\Models\inside\User;
use App\Http\Models\inside\UserGroup;
use App\Http\Models\inside\AuthPermission;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\Http\Requests\inside\LoginRequest;
use Auth;

class AuthController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Registration & Login Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles the registration of new users, as well as the
      | authentication of existing users. By default, this controller uses
      | a simple trait to add these behaviors. Why don't you explore it?
      |
     */

use AuthenticatesAndRegistersUsers,
    ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest', ['except' => 'actionGetLogout']);
    }

    public function actionGetLogin() {
        return view('inside.login');
    }

    public function actionPostLogin(LoginRequest $request) {
        $auth = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        $remember = $request->remember_me;

        if (Auth::attempt($auth, $remember)) {
            $user = User::find(\Auth::user()->id);
            $group = UserGroup::getById($user->user_group_id);
            $canLogin = AuthPermission::checkLoginPermission($user->user_group_id);

            if ($group == null || !$canLogin) {
                Auth::logout();
                return redirect('admin/login')
                        ->withInput($request->only('email', 'remember'))
                        ->withErrors([
                            'email' => 'Bạn không có quyền đăng nhập.',
                ]);
            }

            $user->last_login = date('Y-m-d H:i:s');
            $user->save();
            return redirect('admin/');
        }

        return redirect('admin/login')
                ->withInput($request->only('email', 'remember'))
                ->withErrors([
                    'email' => 'Email hoặc mật khẩu không đúng.',
        ]);
    }

    public function actionGetLogout() {
        Auth::logout();
        return redirect()->route('admin.getLogin');
    }

}
