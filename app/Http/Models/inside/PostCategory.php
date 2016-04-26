<?php

namespace App\Http\Models\inside;

use App\Http\Models\inside\Language;
use App\Http\Models\inside\PostCategoryLanguage;
use Illuminate\Database\Eloquent\Model;
use DB;

class PostCategory extends BaseCategories {

    protected $table = 'post_categories';
    protected $fillable = [
        'parent_id',
    ];

    // Hàm lấy name theo code language và id của sản phẩm
    protected static function getNameByIDAndByLanguage($lang = 'vi', $id) {
        $name = "";
        $idLanguage = Language::select('id')->where(['code' => $lang])->first()->toArray();
        if (!empty($idLanguage)) {
            $rowPostCategories = PostCategoryLanguage::select('name')->where(['post_category_id' => $id, 'language_id' => $idLanguage['id']])->first();
            if (!empty($rowPostCategories)) {
                $name = $rowPostCategories->name;
            }
        }
        return $name;
    }

    public static function getTotalPostCategories() {
        $total = self::where([
                    'is_deleted' => 0,
                ])
                ->count();
        return $total;
    }

}
