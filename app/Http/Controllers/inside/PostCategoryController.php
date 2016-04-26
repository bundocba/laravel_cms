<?php

namespace App\Http\Controllers\inside;

use Illuminate\Http\Request;
use App\Http\Controllers\inside\BaseController;
use App\Http\Models\inside\Language;
use App\Http\Models\inside\PostCategory;
use App\Http\Models\inside\PostCategoryLanguage;
use App\Http\Requests\inside\PostCategoryRequest;
use Illuminate\Support\Facades\DB;
use Session;
use Route;

class PostCategoryController extends BaseController {

    public function actionGetList($lang = 'vi', $category = null) {
        $currentRoute = Route::current()->getPath();
        $isTrash = (strpos($currentRoute, '/trash') !== FALSE) ? 1 : 0;
        $data = [];
        $list = PostCategory::where(['is_deleted' => $isTrash])->get()->toTree();
        //show cây nhị phân
        $data['list'] = PostCategory::recursiveTree($list, $lang, FALSE, FALSE, FALSE, null);
        $data['listPostCategories'] = PostCategory::recursiveTree($list, $lang, FALSE, FALSE, TRUE, null);
        $data['isTrash'] = $isTrash;
        $data['listLanguages'] = Language::getAllAndMapByCode();
        $data['selectedLanguage'] = $lang;
        $data['selectedCategory'] = $category;
        return view('inside.post-categories.list', $data);
    }

    public function actionGetAdd() {
        $data = [];
        $list = PostCategory::where(['is_deleted' => 0])->get()->toTree();
        $data['list'] = $list;
        $data['listLanguages'] = Language::getAllAndMap();
        $data['listPostCategories'] = PostCategory::recursiveTree($list, 'vi', FALSE, TRUE, TRUE, null);
        return view('inside.post-categories.add', $data);
    }

    public function actionPostAdd(PostCategoryRequest $request) {
        $postCategory = new PostCategory();
        $postCategory->parent_id = $request->parent_id;
        $parent = Postcategory::where(['id' => $request->parent_id, 'is_deleted' => 0])->first();
        $postCategory->created_by = $request->created_by;
        $postCategory->modified_by = $request->modified_by;
        DB::transaction(function() use ($postCategory, $request) {
            try {
                $postCategory->save();
                $languages = Language::getAllAndMap(false);
                foreach ($languages as $id => $name) {
                    $postCategoryLanguage = new PostCategoryLanguage();
                    $postCategoryLanguage->name = $request["$id-name"];
                    $postCategoryLanguage->description = $request["$id-description"];
                    $postCategoryLanguage->post_category_id = $postCategory->id;
                    $postCategoryLanguage->language_id = $id;
                    $postCategoryLanguage->save();
                }
                DB::commit();
                Session::flash('post-category-add-success-message', 'Thêm thông tin thể loại thành công.');
            } catch (Exception $ex) {

                DB::rollback();
                Session::flash('post-category-add-error-message', 'Thêm thông tin thể loại thất bại. Vui lòng thử lại sau.');
            }
        });

        return redirect()->route('admin.post-categories.getAdd');
    }

    public function actionGetEdit($id) {
        $data = [];
        $postCategory = PostCategory::where('id', $id)->first()->toArray();
        $listLanguages = Language::getAllAndMap();
        $postCategoryLanguage = PostCategoryLanguage::getByPostCategoryId($id, $listLanguages);
        $list = PostCategory::where(['is_deleted' => 0])->get()->toTree();
        $data['listLanguages'] = $listLanguages;
        $data['listPostCategories'] = PostCategory::recursiveTree($list, 'vi', FALSE, TRUE, TRUE, $id);
        return view('inside.post-categories.edit', $data)->with(compact('postCategory', 'postCategoryLanguage'));
    }

    public function actionPostEdit($id, PostCategoryRequest $request) {

        $postCategory = PostCategory::where('id', $id)->first();
        $postCategory->parent_id = $request->parent_id;
        $postCategory->created_by = $request->created_by;
        $postCategory->modified_by = $request->modified_by;
        DB::transaction(function() use ($postCategory, $request) {
            try {

                $postCategory->save();

                $languages = Language::getAllAndMap(false);

                foreach ($languages as $id => $name) {
                    $postCategoryLanguage = PostCategoryLanguage::where(['post_category_id' => $postCategory->id, 'language_id' => $id])->first();
                    $postCategoryLanguage->name = $request["$id-name"];
                    $postCategoryLanguage->description = $request["$id-description"];
                    $postCategoryLanguage->save();
                }

                DB::commit();
                Session::flash('post-category-edit-success-message', 'Sửa thông tin bài viết thành công.');
            } catch (Exception $ex) {

                DB::rollback();
                Session::flash('post-category-edit-error-message', 'Sửa thông tin bài viết thất bại. Vui lòng thử lại sau.');
            }
        });

        return redirect()->route('admin.post-categories.getEdit', $id);
    }

    public function actionSetDeleted($id) {
        $model = new PostCategory();
        $currentRoute = Route::current()->getPath();
        $isDeleted = (strpos($currentRoute, '/undo') !== FALSE) ? 0 : 1;
        $postCategory = !$isDeleted ? PostCategory::find($id) : NULL;
        $parent = isset($postCategory->parent_id) ? PostCategory::find($postCategory->parent_id) : NULL;
        if ($parent == NULL || $parent->is_deleted !== $postCategory->is_deleted) {

            $success = PostCategory::setIsDeleted($id, $isDeleted, $model);
            if ($success) {
                $msg = $isDeleted ? 'Thể loại đã bị tạm khóa.' : 'Thể loại đã được kích hoạt lại.';
                Session::flash('post-category-inactive-success-message', $msg);
            } else {
                $msg = $isDeleted ? 'Khóa thể loại thất bại. Vui lòng thử lại sau.' : 'Kích hoạt thể loại thất bại. Vui lòng thử lại sau.';
                Session::flash('post-category-inactive-error-message', $msg);
            }
        } else {
            $msg = 'Kích hoạt thể loại thất bại. Thể loại cha đang bị vô hiệu hóa.';
            Session::flash('post-category-inactive-error-message', $msg);
        }
        $route = $isDeleted ? 'admin.post-categories.getList' : 'admin.post-categories.getTrash';
        return redirect()->route($route);
    }

    public function actionPostDeleted($id) {
        $node = PostCategory::find($id);
        $nodesChild = PostCategory::whereDescendantOf($node)->get();
        DB::beginTransaction();
        try {
            if (PostCategoryLanguage::where('post_category_id', '=', $id)->delete() && $node->delete()) {
                if (!empty($nodesChild)) {
                    foreach ($nodesChild as $value) {
                        $idChild = $value->id;
                        $value->delete();
                        PostCategoryLanguage::where('post_category_id', '=', $idChild)->delete();
                    }
                }
                DB::commit();
                Session::flash('post-category-inactive-success-message', 'Xoá thể loại thành công');
            } else
                Session::flash('post-category-inactive-error-message', 'Xoá thể loại thất bại');
        } catch (Exception $ex) {
            Session::flash('post-category-inactive-error-message', 'Xoá thể loại thất bại');
            DB::rollback();
        }
        return redirect()->route('admin.post-categories.getTrash');
    }

}
