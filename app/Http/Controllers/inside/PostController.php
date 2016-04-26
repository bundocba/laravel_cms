<?php

namespace App\Http\Controllers\inside;

use App\Http\Controllers\inside\BaseController;
use App\Http\Requests\inside\PostRequest;
use App\Http\Models\inside\User;
use App\Http\Models\inside\PostCategory;
use App\Http\Models\inside\Post;
use App\Http\Models\inside\PostLanguage;
use App\Http\Models\inside\Banner;
use App\Http\Models\inside\Language;
use DB;
use Session;
use Route;


class PostController extends BaseController {

    public function actionGetList($lang = 'vi', $category = null) {

        $currentRoute = Route::current()->getPath();
        $isTrash = (strpos($currentRoute, '/trash') !== FALSE) ? true : false;

        $data = [];
        $data['list'] = Post::getList($isTrash, $lang, $category);
        $list = PostCategory::where(['is_deleted' => 0])->get()->toTree();
        $data['listPostCategories'] = PostCategory::recursiveTree($list, 'vi', FALSE, FALSE, TRUE, null);
        $data['listUsers'] = User::getAllAndMap();
        $data['listLanguages'] = Language::getAllAndMapByCode();
        $data['selectedLanguage'] = $lang;
        $data['selectedCategory'] = $category;

        $data['isTrash'] = $isTrash;
        
        return view('inside.posts.list', $data);
    }

    public function actionSetDeleted($id) {
        $post = Post::find($id);

        $currentRoute = Route::current()->getPath();
        $isDeleted = (strpos($currentRoute, '/undo') !== FALSE) ? 0 : 1;

        $post->is_deleted = $isDeleted;
        $post->modified_by = \Auth::user()->id;

        if ($post->save()) {
            $msg = $isDeleted ? 'Bài viết đã bị tạm khóa.' : 'Bài viết đã được kích hoạt lại.';
            Session::flash('post-inactive-success-message', $msg);
        } else {
            $msg = $isDeleted ? 'Khóa bài viết thất bại. Vui lòng thử lại sau.' : 'Kích hoạt bài viết thất bại. Vui lòng thử lại sau.';
            Session::flash('post-inactive-error-message', $msg);
        }

        $route = $isDeleted ? 'admin.posts.getList' : 'admin.posts.getTrash';
        return redirect()->route($route);
    }

    public function actionGetAdd() {

        session_start();
        $_SESSION['uploadFolder'] = 'posts/';

        $data = [];

        $data['listLanguages'] = Language::getAllAndMap();
        $data['listPostCategories'] = PostCategory::getAllAndMap(0, 'vi', NULL, NULL, FALSE, TRUE, FALSE);
        $data['listBanners'] = Banner::getAllAndMap();


        return view('inside.posts.add', $data);
    }

    public function actionPostAdd(PostRequest $request) {
        $post = new Post();
        $post->alias_name = $request->alias_name;
        $post->post_category_id = $request->post_category_id;
        $post->banner_id = $request->banner_id;
        $post->image_name = $request->image_name;
        $post->ordering = empty($request->ordering) ? NULL : $request->ordering;
        $post->created_by = $request->created_by;
        $post->modified_by = $request->modified_by;
        
        DB::transaction(function() use ($post, $request) {
            try {

                $post->save();

                $languages = Language::getAllAndMap(false);
                
                foreach ($languages as $id => $name) {
                    $postLanguage = new PostLanguage();
                    $postLanguage->name = $request["$id-name"];
                    $postLanguage->short_description = $request["$id-short_description"];
                    $postLanguage->full_description = $request["$id-full_description"];

                    $postLanguage->post_id = $post->id;
                    $postLanguage->language_id = $id;
                    
                    $postLanguage->save();
                }
                DB::commit();
                Session::flash('post-add-success-message', 'Thêm thông tin bài viết thành công.');
            } catch (Exception $ex) {

                DB::rollback();
                Session::flash('post-add-error-message', 'Thêm thông tin bài viết thất bại. Vui lòng thử lại sau.');
            }
        });

        return redirect()->route('admin.posts.getAdd');
    }

    public function actionGetEdit($id) {

        session_start();
        $_SESSION['uploadFolder'] = 'posts/';

        $data = [];

        $post = Post::where('id', $id)->first()->toArray();

        $listLanguages = Language::getAllAndMap();
        $postLanguage = PostLanguage::getByPostId($id, $listLanguages);

        $data['listLanguages'] = $listLanguages;
        $data['listPostCategories'] = PostCategory::getAllAndMap(0, 'vi', NULL, NULL, FALSE, TRUE, FALSE);
        $data['listBanners'] = Banner::getAllAndMap();

        return view('inside.posts.edit', $data)->with(compact('post', 'postLanguage'));
    }

    public function actionPostEdit($id, PostRequest $request) {

        $post = Post::where('id', $id)->first();
        $post->alias_name = $request->alias_name;
        $post->post_category_id = $request->post_category_id;
        $post->banner_id = $request->banner_id;
        $post->image_name = $request->image_name;
        $post->ordering = empty($request->ordering) ? NULL : $request->ordering;
        $post->modified_by = $request->modified_by;

        DB::transaction(function() use ($post, $request) {
            try {

                $post->save();

                $languages = Language::getAllAndMap(false);
                
                foreach ($languages as $id => $name) {
                    $postLanguage = PostLanguage::where(['post_id' => $post->id, 'language_id' => $id])->first();
                    $postLanguage->name = $request["$id-name"];
                    $postLanguage->short_description = $request["$id-short_description"];
                    $postLanguage->full_description = $request["$id-full_description"];
                    
                    $postLanguage->save();
                }
                
                DB::commit();
                Session::flash('post-edit-success-message', 'Sửa thông tin bài viết thành công.');
            } catch (Exception $ex) {

                DB::rollback();
                Session::flash('post-edit-error-message', 'Sửa thông tin bài viết thất bại. Vui lòng thử lại sau.');
            }
        });

        return redirect()->route('admin.posts.getEdit', $id);
    }

}
