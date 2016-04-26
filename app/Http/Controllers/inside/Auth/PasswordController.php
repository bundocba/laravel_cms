<?php

namespace App\Http\Controllers\Inside\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\inside\PasswordResetsRequest;
use App\Http\Requests\inside\EmailSendRequest;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Http\Controllers\inside\BaseController;
use App\Http\Models\inside\PasswordResets;
use App\Http\Models\inside\User;
use Hash;
use Mail;
use Session;
use DB;

class PasswordController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Password Reset Controller
      |--------------------------------------------------------------------------
      |
      | This controller is responsible for handling password reset requests
      | and uses a simple trait to include this behavior. You're free to
      | explore this trait and override any methods you wish to tweak.
      |
     */

//use ResetsPasswords;

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
//    public function __construct() {
//        $this->middleware('admin');
//    }

    public function actionGetEmail() {
        return view('inside.auth.email');
    }

    public function actionPostEmail(EmailSendRequest $request) {
        $params = $request->all();
        //kiểm tra có email đó có tồn tại trong hệ thống ko ?
        if (User::where('email', '=', $params['email'])->exists()) {
            $token = str_random(108); // hoặc lấy token get từ form
            $email = $params['email'];
            $link = URL('admin/password/reset/' . $token); //link này sẻ send cho người dùng active về
            $sendMail = Mail::send('inside.auth.forgot-password', ['link' => $link], function ($m) use ($email) {
                        $m->from($email, 'Fpt Telecom');
                        $m->to($email, $email)->subject('[Fpt Telecom] - Khôi phục mật khẩu');
                    }); // gửi 1 view giao diện tới mail người dùng
            if ($sendMail != NULL) {
//                insert 1 password reset với token và email vừa send
                $modelPasswordResets = new PasswordResets();
                $modelPasswordResets->email = $email;
                $modelPasswordResets->token = $token;
                if ($modelPasswordResets->save()) {
                    Session::flash('change-password-request-success-message', 'Thông tin reset mật khẩu đả gửi vào mail của bạn!!');
                } else {
                    Session::flash('change-password-request-error-message', 'Đã xảy ra sự cố. Vui lòng thử lại lần sau!!');
                }
            } else {
                Session::flash('change-password-request-error-message', 'Đã xảy ra sự cố. Vui lòng thử lại lần sau!!');
            }
        } else {
            Session::flash('change-password-request-error-message', 'Không có email này trong hệ thống , xin kiểm tra lại !!');
        }
        return redirect()->route('admin.getEmail');
    }

    public function actionGetReset($token) {
        if (is_null($token)) {
            return view('inside.errors.404');
        }
        $user_data = PasswordResets::where('token', '=', $token)->first();
        if ($user_data != null) {
            return view('inside.auth.confirm-reset-password', array(
                'token_password' => $token
            ));
        } else {
            return view('inside.errors.404');
//            return redirect('admin/login')->withErrors('Đường dẫn không tồn tại hoặc đã được kích hoạt. Vui lòng thử lại');
        }
    }

    public function actionPostReset(PasswordResetsRequest $request) {
        $params = $request->all();
        $tokenPassword = $params['token_password'];
        $data = PasswordResets::where('token', '=', $tokenPassword)->first();
        $new_password = Hash::make($params['password']);
        $data_change['password'] = $new_password;
        if (!empty($data)) {
            $user = User::where('email', '=', $data->email)->first();
            $user->password = $new_password;
            $user->remember_token=$tokenPassword;
            DB::beginTransaction();
            try {
                $user->save();
                PasswordResets::where('token', '=', $tokenPassword)->delete();
//            nếu đổi pass thành công thì xoá record trong password_reset đi
                DB::commit();
                Session::flash('change-password-request-success-message', 'Bạn vừa đổi mật khẩu thành công!!');
                return view('inside.login');
            } catch (Exception $ex) {
                DB::rollback();
                Session::flash('change-passwo rd-request-error-message', 'Có lỗi xãy ra , đổi mật khẩu thất bại ');
            }
        }
    }

}
