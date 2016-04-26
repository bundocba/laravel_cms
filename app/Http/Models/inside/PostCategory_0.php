<?php

namespace App\Http\Models\inside;

use Illuminate\Database\Eloquent\Model;
use DB;

class PostCategory_0 extends Model {

    protected $table = 'post_categories';
    protected $fillable = [
        'parent_id',
        'level',
        'created_by',
        'modified_by',
        'is_deleted',
        'created_at',
        'updated_at',
    ];

    public function languages() {
        return $this->hasMany('App\Models\PostCategoryLanguage', 'post_category_id');
    }

    public static function getById($categoryId) {
        return self::with('languages')->where(['id' => $categoryId, 'is_deleted' => 0])->first()->toArray();
    }

    public static function getAll($isDeleted = 0, $languageCode = 'vi', $categoryId = null, $exceptId = null, $useMapping = false) {
        $listPostCategories = self::select(['post_categories.*', 'post_category_languages.name', 'post_category_languages.description'])
                ->leftJoin('post_category_languages', 'post_categories.id', '=', 'post_category_languages.post_category_id')
                ->leftJoin('languages', 'languages.id', '=', 'post_category_languages.language_id')
                ->where('post_categories.is_deleted', '=', $isDeleted)
                ->where('languages.code', '=', $languageCode)
                ->orderBy('post_categories.level', 'ASC')
                ->orderBy('post_categories.parent_id', 'ASC');

        if ($categoryId != null) {
            $childrenId = [
                0 => $categoryId,
            ];
            self::getAllChildren($childrenId, $categoryId);
            $listPostCategories->whereIn('post_categories.id', $childrenId);
        }

        if ($exceptId != null) {
            $childrenId = [
                0 => $exceptId,
            ];
            self::getAllChildren($childrenId, $exceptId);
            $listPostCategories->whereNotIn('post_categories.id', $childrenId);
        }

        $list = $listPostCategories->get()->toArray();

        if ($useMapping) {
            $results = [];
            foreach ($list as $index => $row) {
                $id = $row['id'];
                $name = $row['name'];
                $results[$id] = $name;
            }
            return $results;
        }

        return $list;
    }

    public static function getAllChildren(&$childrenId, $parentId) {
        $children = self::select('id')
                ->where(['parent_id' => $parentId])
                ->get()
                ->toArray();

        foreach ($children as $index => $child) {
            array_push($childrenId, $child['id']);
            self::getAllChildren($childrenId, $child['id']);
        }

        return 1;
    }

    public static function parseTree($array) {
        $i = isset($array[0]['parent_id']) ? $array[0]['parent_id'] : 0;
        foreach ($array as $n) {
            $pid = $n['parent_id'];
            $id = $n['id'];
            $n['name'] = self::getPrefix($n['level']) . $n['name'];

            if (!isset($p[$pid])) {
                $p[$pid] = array('children' => array());
            }

            if (isset($p[$id])) {
                $child = &$p[$id]['children'];
            } else {
                $child = '';
            }

            $p[$id] = $n;

            $p[$id]['children'] = &$child;
            unset($child);

            $p[$pid]['children'][] = &$p[$id];
        }

        if (isset($p[$i]) && !empty($p[$i]['children'])) {
            $array = $p[$i]['children'];
        }
        unset($p);
        return $array;
    }

    public static function getPrefix($level) {
        $i = 0;
        $prefix = '';
        while ($i < $level) {
            $prefix .= '- ';
            $i++;
        }
        return $prefix;
    }

    public static function recursiveTree(&$results, $tree) {
        foreach ($tree as $index => $row) {
            $id = $row['id'];
            $results[$id] = $row['name'];
            if (isset($row['children']) && is_array($row['children'])) {
                self::recursiveTree($results, $row['children']);
            }
        }

        return 1;
    }

    public static function getAllAndMap($isDeleted = 0, $languageCode = 'vi', $categoryId = null, $exceptId = null, $useMapping = false, $useEmptyOption = true, $useRoot = true) {
        $all = self::getAll($isDeleted, $languageCode, $categoryId, $exceptId, $useMapping);
        $tree = self::parseTree($all);

        $results = $useEmptyOption ? [NULL => '-- Chọn một thể loại --'] : [];

        if ($useRoot) {
            $results[0] = 'Root';
        }

        self::recursiveTree($results, $tree);
        return $results;
    }

    public static function updateAllChildren(&$childrenId, $parentId, $params = []) {
        $children = self::select('id')
                ->where(['parent_id' => $parentId])
                ->get()
                ->toArray(); // Get all children

        foreach ($children as $index => $child) {

            $result = DB::table('post_categories')
                    ->where(['id' => $child['id']])
                    ->update($params); // Update child node

            if ($result) {
                $cloneParams = $params;
                if (isset($params['level'])) {
                    $cloneParams['level'] += 1;
                }
                self::updateAllChildren($childrenId, $child['id'], $cloneParams); // Recursive
            }
        }

        return 1;
    }

    public static function setLevel($parentId, $id = null) {
        $row = self::select(['level', 'is_deleted'])
                ->where(['id' => $parentId])
                ->first();

        $level = $rowLevel = isset($row['level']) ? $row['level'] + 1 : 1;

        if ($id != null) {
            $success = DB::transaction(function() use ($id, $row, $level, $rowLevel) {
                        try {

                            $result = DB::table('post_categories')
                                    ->where(['id' => $id])
                                    ->update([
                                'level' => $level,
                                'is_deleted' => $row['is_deleted'],
                                'modified_by' => \Auth::user()->id,
                            ]);

                            if ($result) {

                                $childrenId = array();
                                self::updateAllChildren($childrenId, $id, [
                                    'level' => $level + 1,
                                    'is_deleted' => $row['is_deleted'],
                                    'modified_by' => \Auth::user()->id,
                                ]);
                                DB::commit();
                                return $rowLevel;
                            }
                        } catch (Exception $ex) {
                            DB::rollback();
                            return 0;
                        }
                    });
            return $success;
        }
        return $level;
    }

    public static function setIsDeleted($id, $isDeleted) {
        $success = DB::transaction(function() use ($id, $isDeleted) {
                    try {

                        $result = DB::table('post_categories')
                                ->where(['id' => $id])
                                ->update([
                            'is_deleted' => $isDeleted,
                            'modified_by' => \Auth::user()->id,
                        ]);

                        if ($result) {

                            $childrenId = [];
                            self::updateAllChildren($childrenId, $id, [
                                'is_deleted' => $isDeleted,
                                'modified_by' => \Auth::user()->id,
                            ]);
                            DB::commit();

                            return 1;
                        }
                    } catch (Exception $ex) {
                        DB::rollback();
                        return 0;
                    }
                });

        return $success;
    }

    public static function getAllAndMapByCode($useImage = true) {
        $listLanguages = self::all()->where('is_deleted', '=', 0)->toArray();

        $list = [];

        foreach ($listLanguages as $index => $language) {
            $code = $language['code'];
            $image = '<img src="' . asset("public/inside/img/system/{$language['image_name']}") . '" />';
            $name = $useImage ? $image . ' ' . $language['name'] : $language['name'];

            $list[$code] = $name;
        }

        return $list;
    }

    public static function getTotalPostCategories() {
        $total = self::where([
                    'is_deleted' => 0,
                ])
                ->count();

        return $total;
    }

}
