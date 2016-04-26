<?php

namespace App\Http\Requests\inside;

use App\Http\Requests\Request;

class BannerRequest extends Request {

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
            'name' => 'required|string|unique:banners,name,' . $this->route()->getParameter('id') . '|max:150',
            'image_name' => 'max:255',
            'type' => 'required|in:banner,promotion',
            'created_by' => 'required|integer',
            'modified_by' => 'required|integer',
        ];
    }
    
    public function all() {
        $input = parent::all();

        $img = explode('public/inside/img/upload/banners/', $input['image_name']);
        $input['image_name'] = $img[count($img) - 1];
        
        $input['created_by'] = $input['modified_by'] = \Auth::user()->id;

        return $input;
    }
    
    public function messages() {
        return [            
            'name.required' => 'Vui lòng nhập tên.',
            'name.string' => 'Tên phải là chuỗi.',
            'name.unique' => 'Tên đã được sử dụng.',
            'name.max' => 'Tên tối đa chỉ được phép 150 ký tự.',                        
            'image_name.max' => 'Tên hình không hợp lệ.',            
            'type.required' => 'Vui lòng nhập loại banner.',
            'type.in' => 'Loại banner không hợp lệ.',
            'created_by.required' => 'Vui lòng nhập vào người tạo.',
            'created_by.integer' => 'Người tạo phải là con số nguyên.',
            'modified_by.required' => 'Vui lòng nhập vào người sửa.',
            'modified_by.integer' => 'Người sửa phải là con số nguyên.',
        ];
    }

}
