<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Requests\inside;
use App\Http\Requests\Request;
/**
 * Description of EmailSendRequest
 *
 * @author MRBun
 */
class EmailSendRequest extends Request{

    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'email' => 'required',
            'captcha' => 'required|captcha',
        ];
    }

    public function messages() {
        return [
            'email.required' => 'Vui lòng nhập Email.',
            'captcha.required' => 'Vui lòng nhập Captcha',
            'captcha.captcha' => 'Vui lòng nhập đúng Captcha',
        ];
    }

}
