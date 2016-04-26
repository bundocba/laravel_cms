<?php

namespace App\Http\Controllers\inside;

use App\Http\Controllers\inside\BaseController;
use App\Http\Models\inside\UserGroup;
use App\Http\Models\inside\User;
use App\Http\Models\inside\UserProfile;
use App\Http\Models\inside\AuthPermission;
use App\Helpers\AuthItemHelper;
use Validator;
use Input;
use DB;
use Session;

class AuthPermissionController extends BaseController {

    public function actionGetList() {
        $data = [];

        $data['list'] = User::getList(0);
        $data['listUserGroups'] = UserGroup::getAllAndMap();

        return view('inside.auth-permissions.list', $data);
    }

    public function actionGetEdit($id) {
        $data = [];

        $user = User::find($id);
        $data['user'] = $user;
        $data['group'] = UserGroup::getById($user['user_group_id']);
        $data['listUserGroups'] = UserGroup::getAllAndMap(true);
        $data['listGenders'] = UserProfile::getListGenders();

        return view('inside.auth-permissions.edit', $data);
    }

    public function actionPostEdit($id) {
        $validator = Validator::make(Input::all(), [
                'user_group_id' => 'required|integer'
                ], [
                'user_group_id.required' => 'Vui lòng nhập nhóm.',
                'user_group_id.integer' => 'Nhóm phải là con số nguyên',
                ]
        );

        if ($validator->fails()) {
            return redirect()->route('admin.auth-permissions.getEditPermission', $id)
                    ->withErrors($validator)
                    ->withInput();
        }

        $user = User::find($id);
        $user->user_group_id = Input::get('user_group_id');

        DB::transaction(function() use ($user) {
            try {
                $user->save();
                DB::commit();
                Session::flash('auth-permission-edit-success-message', 'Đổi nhóm quyền thành công.');
            } catch (Exception $ex) {

                DB::rollback();
                Session::flash('auth-permission-edit-error-message', 'Đổi nhóm quyền thất bại. Vui lòng thử lại sau.');
            }
        });

        return redirect()->route('admin.auth-permissions.getEditPermission', $id);
    }

    public function actionGetPermission($id) {

        $data = [];

        $data['userGroup'] = UserGroup::getById($id);
        $data['listPermissions'] = AuthPermission::getList($id);

        $data['routes'] = AuthItemHelper::getRoutes();

        return view('inside.auth-permissions.assignment', $data);
    }

    public function actionPostPermission($id) {

        $inputs = \Input::get('checkbox');

        if (!empty($inputs)) {
            DB::transaction(function() use ($inputs, $id) {
                try {
                    $listItems = [];
                    foreach ($inputs as $controller => $list) {
                        foreach ($list as $index => $action) {
                            $permission = AuthPermission::where(['group_id' => $id, 'item' => $action])->first();
                            if ($permission == null) {
                                $permission = new AuthPermission();
                                $permission->group_id = $id;
                                $permission->item = $action;
                            }
                            $listItems[] = $permission->item;
                            $permission->is_deleted = 0;
                            $permission->save();
                        }
                    }
                    DB::table('auth_permissions')
                        ->where(['group_id' => $id])
                        ->whereNotIn('item', $listItems)
                        ->update([
                            'is_deleted' => 1,
                    ]);

                    DB::commit();
                    Session::flash('auth-permission-assignment-success-message', 'Lưu quyền thành công.');
                } catch (Exception $ex) {

                    DB::rollback();
                    Session::flash('auth-permission-assignment-error-message', 'Lưu quyền thất bại. Vui lòng thử lại sau.');
                }
            });
        } else {
            Session::flash('auth-permission-assignment-error-message', 'Vui lòng chọn ít nhật 1 quyền trước khi lưu.');
        }

        return redirect()->route('admin.auth-permissions.getPermission', $id);
    }

}
