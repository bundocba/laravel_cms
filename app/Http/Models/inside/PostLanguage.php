<?php

namespace App\Http\Models\inside;

use Illuminate\Database\Eloquent\Model;

class PostLanguage extends Model {

    protected $table = 'post_languages';
    protected $primaryKey = ['post_id', 'language_id'];
    protected $fillable = [
        'post_id',
        'language_id',
        'name',
        'short_description',
        'full_description'
    ];
    public $timestamps = false;
    public $incrementing = false;

    public static function getByPostId($postId, $listLanguages) {
        $listRecords = self::where('post_id', $postId)->get()->toArray();

        $list = [];

        foreach ($listLanguages as $id => $name) {
            foreach ($listRecords as $index => $row) {
                if ($id === $row['language_id']) {
                    $list["$id-name"] = $row['name'];
                    $list["$id-short_description"] = $row['short_description'];
                    $list["$id-full_description"] = $row['full_description'];
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
