<?php

namespace App\Http\Requests\inside;

use App\Http\Requests\Request;
use App\Models\Language;

class PostRequest extends Request {

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

        $languages = Language::getAllAndMap(false);

        $rulePost = [
            'alias_name' => 'required|unique:posts,alias_name,' . $this->route()->getParameter('id') . '|max:255',
            'post_category_id' => 'required|integer',
            'image_name' => 'max:255',
            'banner_id' => 'integer',
            'created_by' => 'required|integer',
            'modified_by' => 'required|integer',
            'ordering' => 'integer',
        ];
        $rulePostLanguage = [];
        foreach ($languages as $id => $name) {
            $rulePostLanguage["$id-name"] = 'required|unique:post_languages,name,' . $this->route()->getParameter('id') . ',post_id|string|max:255';
        }
        return array_merge($rulePost, $rulePostLanguage);
    }

    public function all() {
        $input = parent::all();

        $img = explode('public/inside/img/upload/posts/', $input['image_name']);
        $input['image_name'] = $img[count($img) - 1];
        
        $input['created_by'] = $input['modified_by'] = \Auth::user()->id;

        return $input;
    }

    public function messages() {
        $languages = Language::getAllAndMap(false);
        $messagePost = [
            'alias_name.required' => 'Vui lòng nhập bí danh.',
            'alias_name.unique' => 'Bí danh đã được sử dụng.',
            'alias_name.max' => 'Bí danh tối đa chỉ được phép 255 ký tự.',
            'post_category_id.required' => 'Vui lòng nhập thể loại.',
            'post_category_id.integer' => 'Thể loại phải là con số nguyên.',
            'image_name.max' => 'Hình đại diện không hợp lệ.',
            'banner_id.integer' => 'Banner liên quan phải là con số nguyên.',
            'created_by.required' => 'Vui lòng nhập vào người tạo.',
            'created_by.integer' => 'Người tạo phải là con số nguyên.',
            'modified_by.required' => 'Vui lòng nhập vào người sửa.',
            'modified_by.integer' => 'Người sửa phải là con số nguyên.',
            'ordering.integer' => 'Thứ tự phải là con số nguyên.',
        ];
        $messagePostLanugage = [];
        foreach($languages as $id => $name) {
            $messagePostLanugage["$id-name.required"] = 'Vui lòng nhập tên.';
            $messagePostLanugage["$id-name.unique"] = 'Tên đã được sử dụng.';
            $messagePostLanugage["$id-name.string"] = 'Tên phải là chuỗi.';
            $messagePostLanugage["$id-name.max"] = 'Tên tối đa chỉ được phép 255 ký tự.';
        }
        return array_merge($messagePost, $messagePostLanugage);
    }

}
