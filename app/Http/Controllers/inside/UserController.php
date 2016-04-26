<?php

namespace App\Http\Controllers\Inside;

use App\Http\Requests\inside\UserRequest;
use App\Http\Models\inside\User;
use App\Http\Models\inside\UserProfile;
use App\Http\Models\inside\UserGroup;
use Hash;
use DB;
use Session;
use Route;
use App\Http\Controllers\inside\BaseController;

class UserController extends BaseController {

    public function actionGetList() {

        $currentRoute = Route::current()->getPath();
        $isTrash = (strpos($currentRoute, '/trash') !== FALSE) ? true : false;

        $data = [];

        $data['list'] = User::getList($isTrash);
        $data['listGenders'] = UserProfile::getListGenders();
        $data['listUserGroups'] = UserGroup::getAllAndMap();

        $data['isTrash'] = $isTrash;

        return view('inside.users.list', $data);
    }

    public function actionSetDeleted($id) {
        $user = User::find($id);

        $currentRoute = Route::current()->getPath();
        $isDeleted = (strpos($currentRoute, '/undo') !== FALSE) ? 0 : 1;

        $user->is_deleted = $isDeleted;

        if ($user->save()) {
            $msg = $isDeleted ? 'Thành viên đã bị tạm khóa.' : 'Thành viên đã được kích hoạt lại.';
            Session::flash('user-inactive-success-message', $msg);
        } else {
            $msg = $isDeleted ? 'Khóa thành viên thất bại. Vui lòng thử lại sau.' : 'Kích hoạt thành viên thất bại. Vui lòng thử lại sau.';
            Session::flash('user-inactive-error-message', $msg);
        }

        $route = $isDeleted ? 'admin.users.getList' : 'admin.users.getTrash';
        return redirect()->route($route);
    }

    public function actionGetAdd() {
        
        session_start();
        $_SESSION['uploadFolder'] = 'users/';
        
        $data = [];

        $data['listGenders'] = UserProfile::getListGenders();
        $data['listUserGroups'] = UserGroup::getAllAndMap(true);

        return view('inside.users.add', $data);
    }

    public function actionPostAdd(UserRequest $request) {
        
        $user = new User();
        $user->password = Hash::make($request->password);
        $user->email = $request->email;
        $user->user_group_id = $request->user_group_id;
        $user->image_name = $request->image_name;
        $user->remember_token = $request->_token;

        $profile = new UserProfile();
        $profile->first_name = $request->first_name;
        $profile->last_name = $request->last_name;

        $originalDate = str_replace("/", "-", $request->date_of_birth);
        $dob = strtotime($originalDate);
        $profile->date_of_birth = date('Y-m-d', $dob);

        $profile->gender = $request->gender;
        $profile->employee_no = $request->employee_no;
        $profile->social_security_no = $request->social_security_no;
        $profile->phone_no = $request->phone_no;
        $profile->address = $request->address;
        $profile->story = $request->story;

        DB::transaction(function() use ($user, $profile) {
            try {
                $user->save();
                $profile->user_id = $user->id;
                $profile->save();
                DB::commit();
                Session::flash('user-add-success-message', 'Thêm thông tin thành viên thành công.');
            } catch (Exception $ex) {

                DB::rollback();
                Session::flash('user-add-error-message', 'Thêm thông tin thành viên thất bại. Vui lòng thử lại sau.');
            }
        });

        return redirect()->route('admin.users.getAdd');
    }

    public function actionGetEdit($id) {
        
        session_start();
        $_SESSION['uploadFolder'] = 'users/';
        
        $data = [];

        $user = User::where('id', $id)->first()->toArray();

        $profile = UserProfile::where('user_id', $id)->first()->toArray();

        $profile['date_of_birth'] = date('d/m/Y', strtotime($profile['date_of_birth']));

        $data['listGenders'] = UserProfile::getListGenders();
        $data['listUserGroups'] = UserGroup::getAllAndMap(true);

        return view('inside.users.edit', $data)->with(compact('user', 'profile'));
    }

    public function actionPostEdit($id, UserRequest $request) {

        $user = User::where('id', $id)->first();
        $user->email = $request->email;
        $user->user_group_id = $request->user_group_id;
        $user->image_name = $request->image_name;
        $user->remember_token = $request->_token;

        $profile = UserProfile::where('user_id', $id)->first();
        $profile->first_name = $request->first_name;
        $profile->last_name = $request->last_name;

        $originalDate = str_replace("/", "-", $request->date_of_birth);
        $dob = strtotime($originalDate);
        $profile->date_of_birth = date('Y-m-d', $dob);

        $profile->gender = $request->gender;
        $profile->employee_no = $request->employee_no;
        $profile->social_security_no = $request->social_security_no;
        $profile->phone_no = $request->phone_no;
        $profile->address = $request->address;
        $profile->story = $request->story;

        DB::transaction(function() use ($user, $profile) {
            try {

                $user->save();
                $profile->save();
                DB::commit();
                Session::flash('user-edit-success-message', 'Sửa thông tin thành viên thành công.');
            } catch (Exception $ex) {

                DB::rollback();
                Session::flash('user-edit-error-message', 'Sửa thông tin thành viên thất bại. Vui lòng thử lại sau.');
            }
        });

        return redirect()->route('admin.users.getEdit', $id);
    }
}
