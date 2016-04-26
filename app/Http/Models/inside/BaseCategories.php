<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Models\inside;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use DB;

/**
 * Description of BaseCategories
 *
 * @author MRBun
 */
class BaseCategories extends Model {

    use NodeTrait;

    public static function recursiveTree($nodes, $lang = 'vi', $useEmptyOption = true, $useRoot = true, $flagName = true, $exceptId = null) {
        $results = $useEmptyOption ? [NULL => '-- Chọn một thể loại --'] : [];
        if ($useRoot) {
            $results[0] = 'Root';
        }
        $traverse = function ($nodes, $prefix = '-' ) use (&$traverse, $lang, &$results, $exceptId) {
            foreach ($nodes as $key => $category) {
                $category->name = PHP_EOL . $prefix . ' ' . (self::getNameByIDAndByLanguage($lang, $category->id));
                $results[$category->id] = $category->name;
                if ($category->id == $exceptId) {
                    unset($category[$category->children]);
                } else
                    $traverse($category->children, $prefix . '-');
            }
        };
        $traverse($nodes);
        return $flagName == true ? $results : $nodes;
    }

    //hàm set is_deleted = 0 với qui tắc set cha thì con củng phải theo 
    public static function setIsDeleted($id, $isDeleted, $model = null) {
        $success = DB::transaction(function() use ($id, $isDeleted, $model) {
                    try {
                        $node = $model::find($id);
                        $nodesChild = $model::whereDescendantOf($node)->get(); // tìm những children 
                        $node->is_deleted = $isDeleted; // set node cha
                        $result = $node->save();
                        foreach ($nodesChild as $key => $category) {
                            $category->is_deleted = $isDeleted;
                            $result = $category->save();
                        }
                        if ($result) {
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

}
