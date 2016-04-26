<?php

namespace App\Http\Controllers\inside;

use App\Http\Controllers\inside\BaseController;
use DB;
use Session;
use App\Http\Models\inside\CustomerContacts;
use App\Http\Requests\inside\CustomerContactsRequest;
use Route;

/**
 * TriHM8
 */
class CustomerContactsController extends BaseController {

    public function actionGetList() {
        $currentRoute = Route::current()->getPath();

        $isTrash = (strpos($currentRoute, '/trash') !== FALSE) ? TRUE : FALSE;

        $data = [];
        $data['list'] = CustomerContacts::getAll($isTrash);
        $data['isTrash'] = $isTrash;
        return view('inside.customer-contacts.list', $data);
    }

    public function actionGetAdd() {
        $data = [];
        return view('inside.customer-contacts.add', $data);
    }

    public function actionPostAdd(CustomerContactsRequest $request) {
        $customerContacts = new CustomerContacts();
        $customerContacts->company_name = $request->company_name;
        $customerContacts->full_name = $request->full_name;
        DB::transaction(function() use ($customerContacts) {
            try {
                $customerContacts->save();
                DB::commit();
                Session::flash('customer-contacts-add-success-message', 'Thêm danh sách liên hệ thành công');
            } catch (Exception $ex) {
                Session::flash('customer-contacts-add-error-message', 'Thêm danh sách liên hệ thất bại');
                DB::rollback();
            }
        });
        return redirect()->route('admin.customer-contacts.getAdd');
    }

    public function actionGetEdit($id) {
        $data = [];
        $data['customer_contacts'] = CustomerContacts::find($id);
        return view('inside.customer-contacts.edit', $data);
    }

    public function actionPostEdit($id, CustomerContactsRequest $request) {
        $modelCustomerContacts = CustomerContacts::findOrFail($id);
        $modelCustomerContacts->company_name = $request->company_name;
        $modelCustomerContacts->full_name = $request->full_name;
        DB::transaction(function() use ($modelCustomerContacts, $request) {
            try {
                $modelCustomerContacts->save();
                DB::commit();
                Session::flash('customer-contacts-edit-success-message', 'Sửa thông tin danh sách liên hệ thành công.');
            } catch (Exception $ex) {
                DB::rollback();
                Session::flash('customer-contacts-edit-error-message', 'Sửa thông tin bài viết thất bại. Vui lòng thử lại sau.');
            }
        });
        $modelCustomerContacts->save();
        return redirect()->route('admin.customer-contacts.getEdit', $id);
    }

    public function actionSetDeleted($id) {
        $modelCustomerContacts = CustomerContacts::findOrFail($id);
        $currentRoute = Route::current()->getPath();
        $isDeleted = (strpos($currentRoute, '/undo') !== FALSE) ? 0 : 1;
        $modelCustomerContacts->is_deleted = $isDeleted;
        if ($modelCustomerContacts->save()) {
            $msg = $isDeleted ? 'Bài viết đã bị tạm khóa.' : 'Bài viết đã được kích hoạt lại.';
            Session::flash('customer-contacts-inactive-success-message', $msg);
        } else {
            $msg = $isDeleted ? 'Khóa bài viết thất bại. Vui lòng thử lại sau.' : 'Kích hoạt bài viết thất bại. Vui lòng thử lại sau.';
            Session::flash('customer-contacts-inactive-error-message', $msg);
        }

        $route = $isDeleted ? 'admin.customer-contacts.getList' : 'admin.customer-contacts.getTrash';
        return redirect()->route($route);
    }

}

?>