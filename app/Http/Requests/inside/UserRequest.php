<?php

namespace App\Http\Requests\inside;

use App\Http\Requests\Request;
use Route;

class UserRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $currentRoute = Route::current()->getPath();
        
        if (strpos($currentRoute, '/edit/') !== FALSE) {
            return [
                'email' => 'required|email|unique:users,email,' . $this->route()->getParameter('id') . '|max:200',
                'user_group_id' => 'required|integer',
                'image_name' => 'max:50',
                'first_name' => 'required',
                'last_name' => 'required',
                'date_of_birth' => 'required|date_format:d/m/Y|before:today',
                'gender' => 'required|integer',
                'employee_no' => 'required|unique:user_profiles,employee_no,' . $this->route()->getParameter('id') . ',user_id|digits_between:1,9',
                'social_security_no' => 'required|unique:user_profiles,social_security_no,' . $this->route()->getParameter('id') . ',user_id|digits_between:9,12',
                'phone_no' => 'required|unique:user_profiles,phone_no,' . $this->route()->getParameter('id') . ',user_id|digits_between:10,14',
                'address' => 'required|max:255',
            ];
        }
        
        return [
            'password' => 'required',
            'repeat_password' => 'required|same:password',
            'email' => 'required|email|unique:users|max:200',
            'user_group_id' => 'required|integer',
            'image_name' => 'max:50',
            'first_name' => 'required',
            'last_name' => 'required',
            'date_of_birth' => 'required|date_format:d/m/Y|before:today',
            'gender' => 'required|integer',
            'employee_no' => 'required|unique:user_profiles|digits_between:1,9',
            'social_security_no' => 'required|unique:user_profiles|digits_between:9,12',
            'phone_no' => 'required|unique:user_profiles|digits_between:10,14',
            'address' => 'required|max:255',
        ];
    }

    public function all() {
        $input = parent::all();

        $img = explode('public/inside/img/upload/users/', $input['image_name']);
        $input['image_name'] = $img[count($img) - 1];

        return $input;
    }

    public function messages() {
        return [
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'repeat_password.required' => 'Vui lòng nhập vào Nhập lại mật khẩu.',
            'repeat_password.same' => 'Mật khẩu và Nhập lại mật khẩu không khớp.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã được sử dụng.',
            'email.max' => 'Email tối đa chỉ được phép 200 ký tự.',
            'user_group_id.required' => 'Vui lòng chọn nhóm.',
            'user_group_id.integer' => 'Nhóm phải là con số nguyên.',
            'image_name.max' => 'Tên hình đại diện không hợp lệ.',
            'first_name.required' => 'Vui lòng nhập tên.',
            'last_name.required' => 'Vui lòng nhập họ.',
            'date_of_birth.required' => 'Vui lòng nhập ngày sinh.',
            'date_of_birth.date_format' => 'Vui lòng nhập đúng dạng ngày.',
            'date_of_birth.before' => 'Ngày sinh phải nhỏ hơn ngày hiện tại.',
            'gender.required' => 'Vui lòng nhập giới tính.',
            'gender.integer' => 'Giới tính phải là con số nguyên.',
            'employee_no.required' => 'Vui lòng nhập mã nhân viên.',
            'employee_no.digits_between' => 'Mã nhân viên phải là số và chiều dài từ 1 đến 9 số.',
            'employee_no.unique' => 'Mã nhân viên đã được sử dụng.',
            'social_security_no.required' => 'Vui lòng nhập CMND.',
            'social_security_no.digits_between' => 'CMND phải là số và chiều dài từ 9 đến 12 số.',
            'social_security_no.unique' => 'CMND đã được sử dụng.',
            'phone_no.required' => 'Vui lòng nhập số điện thoại.',
            'phone_no.digits_between' => 'Số điện thoại phải là số và chiều dài từ 10 đến 12 số.',
            'phone_no.unique' => 'Số điện thoại đã được sử dụng.',
            'address.required' => 'Vui lòng nhập địa chỉ.',
            'address.max' => 'Địa chỉ tối đa chỉ được 255 ký tự.',
        ];
    }

}
