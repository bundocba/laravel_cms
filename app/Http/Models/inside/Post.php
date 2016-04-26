<?php

namespace App\Http\Models\inside;

use Illuminate\Database\Eloquent\Model;
use App\Models\PostCategory;
use DB;

class Post extends Model {

    protected $table = 'posts';
    protected $fillable = [
        'alias_name',
        'post_category_id',
        'image_name',
        'banner_id',
        'created_by',
        'modified_by',
        'is_deleted',
        'created_at',
        'updated_at',
    ];

    public function languages() {
        return $this->hasMany('App\Models\PostLanguage', 'post_id');
    }

    public static function getList($isDeleted = 0, $languageCode = 'vi', $categoryId = null) {
        $results = self::select(['posts.*', 'post_languages.name', 'post_languages.short_description', 'post_languages.full_description'])
            ->leftJoin('post_languages', 'posts.id', '=', 'post_languages.post_id')
            ->leftJoin('languages', 'languages.id', '=', 'post_languages.language_id')
            ->where('posts.is_deleted', '=', $isDeleted)
            ->where('languages.code', '=', $languageCode);

        if ($categoryId != null) {
            $childrenId = [
                0 => $categoryId,
            ];
            PostCategory::getAllChildren($childrenId, $categoryId);
            $results->whereIn('posts.post_category_id', $childrenId);
        }
        return $results->get()->toArray();
    }

    public static function getAllAndMap($isDeleted = 0, $languageCode = 'vi') {
        $listPosts = self::getList($isDeleted, $languageCode);

        $list = [];

        foreach ($listPosts as $index => $post) {
            $id = $post['id'];
            $name = $post['languages']['name'];

            $list[$id] = $name;
        }

        return $list;
    }

    public static function getById($postId) {
        return self::with('languages')->where(['id' => $postId, 'is_deleted' => 0])->first()->toArray();
    }

    public static function getNewestPosts($languageCode = 'vi') {
        $listPosts = self::select(['posts.*',
                'post_languages.name',
                'post_languages.short_description',
                'post_languages.full_description',
            ])
            ->leftJoin('post_languages', 'post_languages.post_id', '=', 'posts.id')
            ->leftJoin('languages', 'languages.id', '=', 'post_languages.language_id')
            ->where([
                'posts.is_deleted' => 0,
                'languages.code' => $languageCode,
            ])
            ->orderBy('posts.created_at', 'DESC')
            ->take(4)
            ->get()
            ->toArray();

        return $listPosts;
    }
    
    public static function getTotalPosts() {
        $total = self::where([
                'is_deleted' => 0,
            ])
            ->count();

        return $total;
    }
    
    public static function getAmountByMonth($year) {
        $result = self::select(DB::raw('MONTH(created_at) AS month, COUNT(id) AS total'))
            ->whereRaw('YEAR(created_at) = ' . $year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get()
            ->toArray();
        return $result;
    }

    public static function getTotalAmountInYear($year) {
        $result = self::select('*')
            ->whereRaw('YEAR(created_at) = ' . $year)
            ->count();
        return $result;
    }

}
