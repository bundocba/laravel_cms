<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Requests\inside;

use App\Http\Requests\Request;

/**
 * Description of PasswordResetsRequest
 *
 * @author MRBun
 */
class PasswordResetsRequest extends Request {

    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'password' => 'required|min:6',
            'password_confirmation' => 'required|min:6|same:password',
        ];
    }

    public function messages() {
        return [
            'password_confirmation.required'=>'Vui lòng nhập lại mật khẩu xác nhận !!',
            'password.required'=>'Vui lòng nhập mật khẩu mới !!',
            'password_confirmation.min'=>'Mật khẩu phải tối thiểu 6 ký tự !!',
            'password.min'=>'Mật khẩu phải tối thiểu 6 ký tự !!',
            'password_confirmation.same'=>'Mật khẩu nhập lại phải giống mật khẩu mới !!',
        ];
    }

}
