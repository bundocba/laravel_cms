<?php

namespace App\Http\Controllers\inside;

use App\Http\Controllers\inside\BaseController;
use App\Http\Requests\inside\UserGroupRequest;
use App\Http\Models\inside\UserGroup;
use DB;
use Session;
use Route;

class UserGroupController extends BaseController {
    
    public function actionGetList() {

        $currentRoute = Route::current()->getPath();
        $isTrash = (strpos($currentRoute, '/trash') !== FALSE) ? true : false;

        $data = [];

        $data['list'] = UserGroup::getList($isTrash);

        $data['isTrash'] = $isTrash;

        return view('inside.user-groups.list', $data);
    }

    public function actionSetDeleted($id) {
        $userGroup = UserGroup::find($id);

        $currentRoute = Route::current()->getPath();
        $isDeleted = (strpos($currentRoute, '/undo') !== FALSE) ? 0 : 1;

        $userGroup->is_deleted = $isDeleted;

        if ($userGroup->save()) {
            $msg = $isDeleted ? 'Nhóm đã bị tạm khóa.' : 'Nhóm đã được kích hoạt lại.';
            Session::flash('user-group-inactive-success-message', $msg);
        } else {
            $msg = $isDeleted ? 'Khóa nhóm thất bại. Vui lòng thử lại sau.' : 'Kích hoạt nhóm thất bại. Vui lòng thử lại sau.';
            Session::flash('user-group-inactive-error-message', $msg);
        }

        $route = $isDeleted ? 'admin.user-groups.getList' : 'admin.user-groups.getTrash';
        return redirect()->route($route);
    }

    public function actionGetAdd() {
        return view('inside.user-groups.add');
    }

    public function actionPostAdd(UserGroupRequest $request) {
        
        $userGroup = new UserGroup();
        $userGroup->name = $request->name;

        DB::transaction(function() use ($userGroup) {
            try {
                $userGroup->save();
                DB::commit();
                Session::flash('user-group-add-success-message', 'Thêm thông tin nhóm thành công.');
            } catch (Exception $ex) {
                DB::rollback();
                Session::flash('user-group-add-error-message', 'Thêm thông tin nhóm thất bại. Vui lòng thử lại sau.');
            }
        });

        return redirect()->route('admin.user-groups.getAdd');
    }

    public function actionGetEdit($id) {
        
        $userGroup = UserGroup::where('id', $id)->first()->toArray();

        return view('inside.user-groups.edit')->with(compact('userGroup'));
    }

    public function actionPostEdit($id, UserGroupRequest $request) {

        $userGroup = UserGroup::where('id', $id)->first();
        $userGroup->name = $request->name;

        DB::transaction(function() use ($userGroup) {
            try {

                $userGroup->save();
                DB::commit();
                Session::flash('user-group-edit-success-message', 'Sửa thông tin nhóm thành công.');
            } catch (Exception $ex) {

                DB::rollback();
                Session::flash('user-group-edit-error-message', 'Sửa thông tin nhóm thất bại. Vui lòng thử lại sau.');
            }
        });

        return redirect()->route('admin.user-groups.getEdit', $id);
    }
        
}
