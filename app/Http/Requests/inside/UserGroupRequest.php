<?php

namespace App\Http\Requests\inside;

use App\Http\Requests\Request;

class UserGroupRequest extends Request {

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
        return [
            'name' => 'required|string|unique:user_groups,name,' . $this->route()->getParameter('id') . '|max:255',
        ];
    }

    public function messages() {
        return [
            'name.required' => 'Vui lòng nhập tên.',
            'name.string' => 'Tên phải là một chuỗi.',
            'name.unique' => 'Tên đã được sử dụng.',
            'name.max' => 'Tên chỉ được phép tối đa 255 ký tự.',
        ];
    }

}
