<?php

namespace App\Http\Models\inside;

use Illuminate\Database\Eloquent\Model;

class Language extends Model {
    
    protected $table = 'languages';
    
    protected $fillable = [
        'code',
        'name',
        'image_name',
        'ordering',
        'is_default',
        'is_deleted',
        'created_at',
        'updated_at',
    ];
    
    public static function getAllAndMap($useImage = true) {
        $listLanguages = self::all()->where('is_deleted', '=', 0)->toArray();
        
        $list = [];
        
        foreach ($listLanguages as $index => $language) {
            $id = $language['id'];
            $image = '<img src="' . asset("public/inside/img/system/{$language['image_name']}") . '" />';
            $name = $useImage ? $image . ' ' . $language['name'] : $language['name'];
            
            $list[$id] = $name;
        }
        
        return $list;
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
    
    public static function getById($groupId) {
        return self::where(['id' => $groupId, 'is_deleted' => 0])->first()->toArray();
    }
}
