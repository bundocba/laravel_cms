<?php

namespace App\Http\Models\inside;

use Illuminate\Database\Eloquent\Model;

class PostCategoryLanguage extends Model {
    
    protected $table = 'post_category_languages';
    
    protected $primaryKey = ['post_category_id', 'language_id'];
    
    protected $fillable = [
        'post_category_id', 
        'language_id',
        'name',
        'description',
    ];
    
    public $timestamps = false;
    public $incrementing = false;
    
    public static function getByPostCategoryId($postCategoryId, $listLanguages) {
        $listRecords = self::where('post_category_id', $postCategoryId)->get()->toArray();

        $list = [];

        foreach ($listLanguages as $id => $name) {
            foreach ($listRecords as $index => $row) {
                if ($id === $row['language_id']) {
                    $list["$id-name"] = $row['name'];
                    $list["$id-description"] = $row['description'];
                }
            }
        }

        return $list;
    }
    
    protected function setKeysForSaveQuery(\Illuminate\Database\Eloquent\Builder $query) {
        if (is_array($this->primaryKey)) {
            foreach ($this->primaryKey as $pk) {
                $query->where($pk, '=', $this->original[$pk]);
            }
            return $query;
        } else {
            return parent::setKeysForSaveQuery($query);
        }
    }
    
}
