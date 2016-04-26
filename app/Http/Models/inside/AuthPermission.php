<?php

namespace App\Http\Models\inside;

use Illuminate\Database\Eloquent\Model;

class AuthPermission extends Model {

    protected $table = 'auth_permissions';
    protected $fillable = [
        'group_id',
        'item',
        'is_deleted',
        'created_at',
        'updated_at',
    ];

    public static function getList($id, $isDeleted = 0) {
        $list = self::where([
                'is_deleted' => $isDeleted,
                'group_id' => $id
            ])
            ->get()
            ->toArray();

        $results = [];

        foreach ($list as $index => $item) {
            $results[] = $item['item'];
        }

        return $results;
    }

    public static function getById($id) {
        $permission = self::where(['id' => $id, 'is_deleted' => 0])->first();

        if ($permission != null) {
            return $permission->toArray();
        }
        return $permission;
    }

    public static function getAllPermissions($groupId) {
        $listPermissions = self::where([
                'is_deleted' => 0,
                'group_id' => $groupId,
            ])
            ->get();

        if ($listPermissions != null) {
            $list = [];
            foreach ($listPermissions->toArray() as $index => $permission) {
                $item = $permission['item'];
                $list[$item] = true;
            }
            return $list;
        }

        return $listPermissions;
    }
    
    public static function checkLoginPermission($groupId) {
        $permission = self::where([
                'is_deleted' => 0,
                'group_id' => $groupId,
                'item' => 'admin.getLogin',
            ])
            ->first();

        if ($permission != null) {
            return true;
        }

        return false;
    }

}
