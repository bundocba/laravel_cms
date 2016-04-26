<?php

namespace App\Http\Requests\inside;

use App\Http\Requests\Request;
use App\Http\Models\inside\Language;

class PostCategoryRequest extends Request {

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
            'parent_id' => 'required|integer',
            'level' => 'required|integer',
            'created_by' => 'required|integer',
            'modified_by' => 'required|integer',
        ];
        
        $rulePostLanguage = [];
        foreach ($languages as $id => $name) {
            $rulePostLanguage["$id-name"] = 'required|unique:post_category_languages,name,' . $this->route()->getParameter('id') . ',post_category_id|string|max:255';
        }
        return array_merge($rulePost, $rulePostLanguage);
    }

    public function all() {
        $input = parent::all();
        
        $input['created_by'] = $input['modified_by'] = \Auth::user()->id;
        $input['level'] = 1;

        return $input;
    }

    public function messages() {
        $languages = Language::getAllAndMap(false);
        $messagePost = [
            'parent_id.required' => 'Vui lòng nhập thể loại cha.',
            'parent_id.integer' => 'Thể loại cha phải là con số nguyên.',
            'level.required' => 'Vui lòng nhập cấp bậc của thể loại.',
            'level.integer' => 'Cấp bậc của thể loại phải là con số nguyên.',
            'created_by.required' => 'Vui lòng nhập vào người tạo.',
            'created_by.integer' => 'Người tạo phải là con số nguyên.',
            'modified_by.required' => 'Vui lòng nhập vào người sửa.',
            'modified_by.integer' => 'Người sửa phải là con số nguyên.',
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
