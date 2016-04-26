<?php
$actionArray = Route::current()->getAction();
$controllerArray = explode('.', $actionArray['as']);
$countArray = count($controllerArray);

$actionName = $controllerArray[$countArray - 1];
$controllerName = $controllerArray[$countArray - 2];
?>

<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <?php
                $img = $loggedUser['image_name'];
                $path = asset("public/inside/img/upload/users/$img");

                $header_response = get_headers($path, 1);

                if (strpos($header_response[0], "200") == false || empty($img)) {
                    $path = asset('public/inside/img/system/noname.png');
                }
                ?>
                <img src="{{ $path }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ (isset($loggedUserProfile['first_name']) && isset($loggedUserProfile['last_name'])) ? $loggedUserProfile['first_name'] . ' ' . $loggedUserProfile['last_name'] : '' }}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Tìm kiếm...">
                <span class="input-group-btn">
                    <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </form>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header" >CHỨC NĂNG CHÍNH</li>
            @if (isset($myPermission['admin.index']))
            <li class="active treeview">
                <a href="{!! route('admin.index') !!}">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
            @endif

            @if (isset($myPermission['admin.auth-permissions.getListPermission']) 
            || isset($myPermission['admin.user-groups.getAdd'])
            || isset($myPermission['admin.user-groups.getList'])
            )
            <li class="sidebar-menuLi {{ (($controllerName == 'user-groups' || $controllerName == 'auth-permissions') ? 'active' : '') }}">
                <a href="">
                    <i class="fa fa-shield"></i> <span>Phân quyền</span>
                    <i class="fa fa-angle-left pull-right"></i>
                    <ul class="treeview-menu">
                        @if (isset($myPermission['admin.auth-permissions.getListPermission']))
                        <li class=" {{ (($controllerName == 'auth-permissions' && $actionName != 'getPermission') ? 'active' : '') }}">
                            <a class="hb-item" href="{!! route('admin.auth-permissions.getListPermission') !!}" ><i class="fa "></i> Đổi nhóm thành viên</a>
                        </li>
                        @endif

                        @if (isset($myPermission['admin.user-groups.getAdd'])
                        || isset($myPermission['admin.user-groups.getList'])
                        )
                        <li class="{{ (($controllerName == 'user-groups' || ($controllerName == 'auth-permissions' && $actionName == 'getPermission') ) ? 'active' : '') }}">
                            <a class="hb-item" href="#"><i class="fa "></i> Nhóm quyền <i class="fa fa-angle-left pull-right"></i></a>
                            <ul class="treeview-menu">
                                @if (isset($myPermission['admin.user-groups.getAdd']))
                                <li class="{{ (($controllerName == 'user-groups' && $actionName == 'getAdd') ? 'active' : '') }}">
                                    <a class="hb-item" href="{!! route('admin.user-groups.getAdd') !!}"><i class="fa "></i> Thêm mới</a>
                                </li>
                                @endif

                                @if (isset($myPermission['admin.user-groups.getList']))
                                <li class="{{ (($controllerName == 'user-groups' && $actionName == 'getList') ? 'active' : '') }}">
                                    <a class="hb-item" href="{!! route('admin.user-groups.getList') !!}"><i class="fa "></i> Danh sách</a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif
                    </ul>
                </a>
            </li>
            @endif

            @if (isset($myPermission['admin.users.getAdd']) 
            || isset($myPermission['admin.users.getList'])
            )
            <li class="sidebar-menuLi {{ ($controllerName == 'users' ? 'active' : '') }}">
                <a href="">
                    <i class="fa fa-user"></i> <span>Thành viên</span>
                    <i class="fa fa-angle-left pull-right"></i>
                    <ul class="treeview-menu">
                        @if (isset($myPermission['admin.users.getAdd']))
                        <li class="{{ (($controllerName == 'users' && $actionName == 'getAdd') ? 'active' : '') }}">
                            <a class="hb-item" href="{!! route('admin.users.getAdd') !!}" ><i class="fa "></i> Thêm mới</a>
                        </li>
                        @endif

                        @if (isset($myPermission['admin.users.getList']))
                        <li class="{{ (($controllerName == 'users' && $actionName == 'getList') ? 'active' : '') }}">
                            <a class="hb-item" href="{!! route('admin.users.getList') !!}"><i class="fa "></i> Danh sách</a>
                        </li>
                        @endif
                    </ul>
                </a>
            </li>
            @endif

            @if (isset($myPermission['admin.post-categories.getAdd']) 
            || isset($myPermission['admin.post-categories.getList'])
            || isset($myPermission['admin.posts.getAdd'])
            || isset($myPermission['admin.posts.getList'])
            )
            <li class="sidebar-menuLi {{ (($controllerName == 'posts' || $controllerName == 'post-categories') ? 'active' : '') }}">
                <a href="">
                    <i class="fa fa-edit"></i> <span>Quản lý bài viết</span>
                    <i class="fa fa-angle-left pull-right"></i>
                    <ul class="treeview-menu">
                        @if (isset($myPermission['admin.post-categories.getAdd']) 
                        || isset($myPermission['admin.post-categories.getList'])
                        )
                        <li class="{{ ($controllerName == 'post-categories' ? 'active' : '') }}">
                            <a class="hb-item" href="#"><i class="fa "></i> Thể loại <i class="fa fa-angle-left pull-right"></i></a>
                            <ul class="treeview-menu">
                                @if (isset($myPermission['admin.post-categories.getAdd']))
                                <li class="{{ (($controllerName == 'post-categories' && $actionName == 'getAdd') ? 'active' : '') }}">
                                    <a class="hb-item" href="{!! route('admin.post-categories.getAdd') !!}"><i class="fa "></i> Thêm mới</a>
                                </li>
                                @endif

                                @if (isset($myPermission['admin.post-categories.getList']))
                                <li class="{{ (($controllerName == 'post-categories' && $actionName == 'getList') ? 'active' : '') }}">
                                    <a class="hb-item" href="{!! route('admin.post-categories.getList') !!}"><i class="fa "></i> Danh sách</a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif

                        @if (isset($myPermission['admin.posts.getAdd'])
                        || isset($myPermission['admin.posts.getList'])
                        )
                        <li class="{{ ($controllerName == 'posts' ? 'active' : '') }}">
                            <a class="hb-item" href="#"><i class="fa "></i> Bài viết <i class="fa fa-angle-left pull-right"></i></a>
                            <ul class="treeview-menu">
                                @if (isset($myPermission['admin.posts.getAdd']))
                                <li class="{{ (($controllerName == 'posts' && $actionName == 'getAdd') ? 'active' : '') }}">
                                    <a class="hb-item" href="{!! route('admin.posts.getAdd') !!}"><i class="fa "></i> Thêm mới</a>
                                </li>
                                @endif

                                @if (isset($myPermission['admin.posts.getList']))
                                <li class="{{ (($controllerName == 'posts' && $actionName == 'getList') ? 'active' : '') }}">
                                    <a class="hb-item" href="{!! route('admin.posts.getList') !!}"><i class="fa "></i> Danh sách</a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif
                    </ul>
                </a>
            </li>
            @endif

            @if (isset($myPermission['admin.banners.getAdd']) 
            || isset($myPermission['admin.banners.getList'])
            )
            <li class="sidebar-menuLi {{ ($controllerName == 'banners' ? 'active' : '') }}">
                <a href="">
                    <i class="fa fa-film"></i> <span>Banners</span>
                    <i class="fa fa-angle-left pull-right"></i>
                    <ul class="treeview-menu">
                        @if (isset($myPermission['admin.banners.getAdd']))
                        <li class="{{ (($controllerName == 'banners' && $actionName == 'getAdd') ? 'active' : '') }}">
                            <a class="hb-item" href="{!! route('admin.banners.getAdd') !!}" ><i class="fa "></i> Thêm mới</a>
                        </li>
                        @endif

                        @if (isset($myPermission['admin.banners.getList']))
                        <li class="{{ (($controllerName == 'banners' && $actionName == 'getList') ? 'active' : '') }}">
                            <a class="hb-item" href="{!! route('admin.banners.getList') !!}"><i class="fa "></i> Danh sách</a>
                        </li>
                        @endif
                    </ul>
                </a>
            </li>
            @endif
            @if(isset($myPermission['admin.customer-contacts.getList'])
            || isset($myPermission['admin.customer-contacts.getAdd'])
            )
            <li class="sidebar-menuLi {{ ($controllerName == 'customer-contacts' ? 'active' : '') }}">
                <a href="">
                    <i class="fa fa-film"></i> <span>Danh Sách Liên Hệ</span>
                    <i class="fa fa-angle-left pull-right"></i>
                    <ul class="treeview-menu">
                        @if (isset($myPermission['admin.customer-contacts.getAdd']))
                        <li class="{{ (($controllerName == 'customer-contacts' && $actionName == 'getAdd') ? 'active' : '') }}">
                            <a class="hb-item" href="{!! route('admin.customer-contacts.getAdd') !!}" ><i class="fa "></i> Thêm mới</a>
                        </li>
                        @endif

                        @if (isset($myPermission['admin.customer-contacts.getList']))
                        <li class="{{ (($controllerName == 'customer-contacts' && $actionName == 'getList') ? 'active' : '') }}">
                            <a class="hb-item" href="{!! route('admin.customer-contacts.getList') !!}"><i class="fa "></i> Danh sách</a>
                        </li>
                        @endif
                    </ul>
                </a>
            </li>
            @endif
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>