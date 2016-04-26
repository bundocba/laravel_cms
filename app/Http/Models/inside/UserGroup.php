<?php

namespace App\Http\Models\inside;

use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model {
    
    protected $table = 'user_groups';
    
    protected $fillable = [
        'name',
        'is_deleted',
    ];
    
    public static function getList($isDeleted = 0) {
        return self::where('is_deleted', $isDeleted)
            ->get()
            ->toArray();
    }
    
    public static function getAllAndMap($useEmptyOption = FALSE) {
        $listGroups = self::all()->where('is_deleted', '=', 0)->toArray();
        
        $list = $useEmptyOption ? [null => '-- Chọn nhóm --'] : [];
        
        foreach ($listGroups as $index => $group) {
            $id = $group['id'];
            $name = $group['name'];
            
            $list[$id] = $name;
        }
        
        return $list;
    }
    
    public static function getById($groupId) {
        $user = self::where(['id' => $groupId, 'is_deleted' => 0])->first();
        
        if ($user != null) {
            return $user->toArray();
        }
        return $user;
    }
    
    public static function getTotalUserGroups() {
        $total = self::where([
                'is_deleted' => 0,
            ])
            ->count();

        return $total;
    }
    
}
