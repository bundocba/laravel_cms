<?php

namespace App\Http\Models\inside;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model {
    
    protected $table = 'user_profiles';
    
    protected $fillable = [
        'id',
        'user_id',
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'employee_no',
        'social_security_no',
        'phone_no',
        'address',
        'story'
    ];

    protected $hidden = ['id', 'user_id', 'remember_token'];
    
    public function profile() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
        
    public static function getListGenders() {
        $list = [
            1 => 'Nam',
            2 => 'Nữ',
        ];
        
        return $list;
    }
    
}
