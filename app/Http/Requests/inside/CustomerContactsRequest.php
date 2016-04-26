<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Requests\inside;

use App\Http\Requests\Request;

/**
 * Description of CustomerContactsRequest
 *
 * @author MRBun
 */
class CustomerContactsRequest extends Request {

    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'company_name' => 'required|unique:customer_contacts,company_name,'. $this->route()->getParameter('id') ,
            'full_name' => 'required',
        ];
    }

    public function messages() {
        return [
            'company_name.required' => 'Tên công ty không được để trống ! ',
            'company_name.unique' => 'Tên công ty đả tồn tại ! ',
            'full_name.required' => 'Họ tên không được để trống ! ',
        ];
    }

}
