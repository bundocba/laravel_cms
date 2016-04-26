<?php

namespace App\Http\Models\inside;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model {
    
    protected $table = 'banners';
    
    protected $fillable = [
        'name',
        'image_name',
        'type',
        'is_deleted',
        'created_at',
        'updated_at',
    ];
    
    public static function getList($isDeleted = 0) {
        return self::where('is_deleted', $isDeleted)
            ->get()
            ->toArray();
    }

    public static function getAllAndMap() {
        $listBanners = self::getList();

        $list = [
            null => '-- Chọn banner --',
        ];

        foreach ($listBanners as $index => $banner) {
            $id = $banner['id'];
            $name = $banner['name'];

            $list[$id] = $name;
        }

        return $list;
    }

    public static function getById($bannerId) {
        return self::where(['id' => $bannerId, 'is_deleted' => 0])->first()->toArray();
    }
    
    public static function getListTypes() {
        $list = [
            NULL => '-- Chọn loại banner --',
            'banner' => 'Banner',
            'promotion' => 'Quảng cáo',
        ];
        
        return $list;
    }

}
