<?php

namespace App\Http\Models\inside;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract {

    use Authenticatable,
        Authorizable,
        CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'username',
        'password',
        'repeat_password',
        'email',
        'user_group_id',
        'image_name',
        'last_login',
        'is_deleted'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'repeat_password', 'remember_token'];
    public $timestamps = false;

    public function profile() {
        return $this->hasOne('App\Http\Models\inside\UserProfile', 'user_id');
    }

    public static function getList($isDeleted = 0) {
        return self::with('profile')
                ->where('is_deleted', $isDeleted)
                ->get()
                ->toArray();
    }

    public static function getAllAndMap() {
        $listUsers = self::all()->where('is_deleted', '=', 0)->toArray();

        $list = [];

        foreach ($listUsers as $index => $user) {
            $id = $user['id'];
            $email = $user['email'];

            $list[$id] = $email;
        }

        return $list;
    }

    public static function getNewestUsers() {
        $listUsers = self::select(['users.*',
                'user_profiles.first_name',
                'user_profiles.last_name',
                'user_profiles.date_of_birth',
                'user_profiles.gender',
                'user_profiles.employee_no',
                'user_profiles.social_security_no',
                'user_profiles.phone_no',
                'user_profiles.address',
                'user_profiles.story',
                'user_profiles.created_at',
                'user_profiles.updated_at',
            ])
            ->leftJoin('user_profiles', 'user_profiles.user_id', '=', 'users.id')
            ->where([
                'users.is_deleted' => 0,
            ])
            ->orderBy('user_profiles.created_at', 'DESC')
            ->take(8)
            ->get()
            ->toArray();

        return $listUsers;
    }

    public static function getTotalUsers() {
        $total = self::where([
                'is_deleted' => 0,
            ])
            ->count();

        return $total;
    }

}
