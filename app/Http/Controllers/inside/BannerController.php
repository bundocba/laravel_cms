<?php

namespace App\Http\Controllers\inside;

use App\Http\Controllers\inside\BaseController;
use App\Http\Requests\inside\BannerRequest;
use App\Http\Models\inside\Banner;
use App\Http\Models\inside\User;
use DB;
use Session;
use Route;

class BannerController extends BaseController {

    public function actionGetList() {
        
        $currentRoute = Route::current()->getPath();
        $isTrash = (strpos($currentRoute, '/trash') !== FALSE) ? true : false;

        $data = [];

        $data['list'] = Banner::getList($isTrash);
        $data['listTypes'] = Banner::getListTypes();
        $data['listUsers'] = User::getAllAndMap();

        $data['isTrash'] = $isTrash;

        return view('inside.banners.list', $data);
    }

    public function actionSetDeleted($id) {
        $banner = Banner::find($id);

        $currentRoute = Route::current()->getPath();
        $isDeleted = (strpos($currentRoute, '/undo') !== FALSE) ? 0 : 1;

        $banner->is_deleted = $isDeleted;
        $banner->modified_by = \Auth::user()->id;

        if ($banner->save()) {
            $msg = $isDeleted ? 'Banner đã bị tạm khóa.' : 'Banner đã được kích hoạt lại.';
            Session::flash('banner-inactive-success-message', $msg);
        } else {
            $msg = $isDeleted ? 'Khóa banner thất bại. Vui lòng thử lại sau.' : 'Kích hoạt banner thất bại. Vui lòng thử lại sau.';
            Session::flash('banner-inactive-error-message', $msg);
        }

        $route = $isDeleted ? 'admin.banners.getList' : 'admin.banners.getTrash';
        return redirect()->route($route);
    }

    public function actionGetAdd() {
        
        session_start();
        $_SESSION['uploadFolder'] = 'banners/';
        
        $data = [];
        
        $data['listTypes'] = Banner::getListTypes();

        return view('inside.banners.add', $data);
    }

    public function actionPostAdd(BannerRequest $request) {
        
        $banner = new Banner();
        $banner->name = $request->name;        
        $banner->image_name = $request->image_name;
        $banner->type = $request->type;
        $banner->created_by = $request->created_by;
        $banner->modified_by = $request->modified_by;

        DB::transaction(function() use ($banner) {
            try {
                $banner->save();
                DB::commit();
                Session::flash('banner-add-success-message', 'Thêm thông tin banner thành công.');
            } catch (Exception $ex) {
                DB::rollback();
                Session::flash('banner-add-error-message', 'Thêm thông tin banner thất bại. Vui lòng thử lại sau.');
            }
        });

        return redirect()->route('admin.banners.getAdd');
    }

    public function actionGetEdit($id) {
        
        session_start();
        $_SESSION['uploadFolder'] = 'banners/';
        
        $data = [];

        $banner = Banner::where('id', $id)->first()->toArray();

        $data['listTypes'] = Banner::getListTypes();

        return view('inside.banners.edit', $data)->with(compact('banner'));
    }

    public function actionPostEdit($id, BannerRequest $request) {

        $banner = Banner::where('id', $id)->first();
        $banner->name = $request->name;        
        $banner->image_name = $request->image_name;
        $banner->type = $request->type;

        DB::transaction(function() use ($banner) {
            try {

                $banner->save();
                DB::commit();
                Session::flash('banner-edit-success-message', 'Sửa thông tin banner thành công.');
            } catch (Exception $ex) {

                DB::rollback();
                Session::flash('banner-edit-error-message', 'Sửa thông tin banner thất bại. Vui lòng thử lại sau.');
            }
        });

        return redirect()->route('admin.banners.getEdit', $id);
    }

}
