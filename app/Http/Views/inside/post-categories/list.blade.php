@extends('layouts.main')

<?php $title = !$isTrash ? 'Thể Loại - Danh sách liên hệ' : 'Danh sách Thể Loại - Danh sách thùng rác'; ?>

@section('title', $title)

@section('content')
@parent

<!-- Main content -->
<section class="content-header">
    <h1>
        Danh sách <?php echo!$isTrash ? 'Danh sách' : 'thùng rác'; ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{!! route('admin.index') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{!! route('admin.post-categories.getList') !!}">Danh sách Thể Loại</a></li>
        <li class="active">Danh sách <?php echo!$isTrash ? 'Danh sách' : 'thùng rác'; ?></li>
    </ol>
</section>
<section class="content">
    <div class="box box-primary">
        <div class="box-body" id="b-l-list">
            <div class="overlay">
                <div class="o-message">
                    <p class="message">
                        Đang tải dữ liệu, vui lòng chờ trong giây lát...
                        <img src="{{ asset('public/inside/img/icons/radio.gif') }}"  />
                    </p>
                </div>
            </div>

            @if (Session::has('post-category-inactive-success-message'))
            <div class="alert alert-info alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Thành công!</h4>
                {{ Session::get('post-category-inactive-success-message') }}
            </div>
            @elseif (Session::has('post-category-inactive-error-message'))
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> Lỗi!</h4>
                {{ Session::get('post-category-inactive-error-message') }}
            </div>
            @endif

            <div class="btn-wrapper col-xs-12">
                <div class="btn-group func-group pull-left">
                    <div class="pull-left">
                        <div class="btn-group">

                            <ul class="dropdown-menu" role="menu">
                                <?php $route = !$isTrash ? 'admin.post-categories.getList' : 'admin.post-categories.getTrash'; ?>
                                @foreach ($listLanguages as $code => $language)
                                <?php
                                if ($selectedLanguage == $code) {
                                    $currentLanguage = $language;
                                }
                                ?>
                                <li class="{{ ($selectedLanguage == $code) ? 'active' : '' }}">
                                    <a href="{!! route($route, ['lang' => $code, 'category' => $selectedCategory]) !!}">
                                        {!! $language !!}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                            <i class="fa fa-fw fa-globe"></i>
                            <button type="button" class="btn btn-default btn-flat">{!! $currentLanguage !!}</button>
                            <button type="button" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                        </div>
                    </div>
                    <div class="margin-left pull-left">
                        <div class="btn-group">
                            <ul class="dropdown-menu" role="menu">
                                <li class="">
                                    <a href="{!! route($route, ['lang' => $selectedLanguage, 'category' => NULL]) !!}">
                                        {{ 'Tất cả' }}
                                    </a>
                                </li>
                                @if(!empty($listPostCategories)) 
                                @foreach ($listPostCategories as $id => $name)
                                <?php
                                if ($selectedCategory == $id) {
                                    $currentCategory = $name;
                                }
                                ?>
                                <li class="{{ ($selectedCategory == $id) ? 'active' : '' }}">
                                    <a href="{!! route($route, ['lang' => $selectedLanguage, 'category' => $id]) !!}">
                                        {!! strip_tags($name) !!}
                                    </a>
                                </li>
                                @endforeach
                                @endif
                            </ul>
                            <i class="fa fa-fw fa-filter"></i>
                            <button type="button" class="btn btn-default btn-flat">{!! isset($currentCategory) ? strip_tags($currentCategory) : 'Tất cả' !!}</button>
                            <button type="button" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="btn-group pull-right">
                    @if (isset($myPermission['admin.post-categories.getAdd']))
                    <a href="{!! route('admin.post-categories.getAdd') !!}">
                        <button class="btn btn-primary">
                            Thêm mới <i class="fa fa-plus"></i>
                        </button>
                    </a>
                    @endif

                    <?php $route = $isTrash ? 'admin.post-categories.getList' : 'admin.post-categories.getTrash'; ?>
                    @if (isset($myPermission[$route]))
                    <a href="{!! route($route) !!}">
                        <button class="btn <?php echo $isTrash ? 'btn-success' : 'btn-danger' ?>">
                            <?php echo $isTrash ? 'Danh sách' : 'Thùng rác'; ?> <i class="fa <?php echo $isTrash ? 'fa-list' : 'fa-trash'; ?>"></i>
                        </button>
                    </a>
                    @endif
                </div>
            </div>

            <table class="table table-striped table-hover table-bordered" id="edt" lang="vi" >
                <thead class="table-header">
                    <tr class="table-tr">
                        <td class="table-td" style="width: 45px;">
                            <p>&nbsp;</p>
                        </td>
                        <td class="table-td">
                            <p>STT</p>
                        </td>
                        <td class="table-td">
                            <p>Tên</p>
                        </td>
                        <td class="table-td">
                            <p>Trái</p>
                        </td>
                        <td class="table-td">
                            <p>Phải</p>
                        </td>
                        <td class="table-td">
                            <p>Cha</p>
                        </td>
                        <td class="table-td">
                            <p>Trạng thái</p>
                        </td>
                        <td class="table-td">
                            <p>
                                @if (!$isTrash)
                                Sửa - Tạm khóa
                                @else
                                Kích hoạt lại
                                @endif
                            </p>
                        </td>
                        <td class="table-td none">
                            <p>Ngày tạo</p>
                        </td>
                        <td class="table-td none">
                            <p>Ngày sửa</p>
                        </td>
                    </tr>
                </thead>
                <tbody class="table-content">
                    @if (!empty($list))
                    @include('inside.post-categories._tree', [
                    'list' => $list,
                    ])
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</section>
<!-- End Main content -->

<link href="{{ asset('public/inside/assets/data-tables/dataTables.responsive.css') }}" rel="stylesheet">


<script type="text/javascript" src="{{ asset('public/inside/assets/data-tables/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('public/inside/assets/data-tables/dataTables.bootstrap.js') }}"></script>
<script type="text/javascript" src="{{ asset('public/inside/assets/data-tables/dataTables.responsive.min.js') }}"></script>

<script>
$(function () {
    

    $.fn.dataTable.ext.pager.numbers_length = 8;
    var language = $('#edt').attr('lang');

    var oTable = $('#edt').DataTable({
        "responsive": true,
        "aLengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "Tất cả"] // change per page values here
        ],
        // set the initial value
        "iDisplayLength": 5,
        "sDom": "<'row'<'col-sm-6'<'btn-left-none'l>><'col-sm-6'<'btn-right-none'f>>r>t<'row'<'col-sm-12'i><'col-sm-12'p>>",
        "sPaginationType": "simple_numbers",
        "aoColumnDefs": [
            {"bSortable": false, "aTargets": [0, 4, 5]},
            {"bSearchable": false, "aTargets": [8, 8]},
        ],
        "aaSorting": [[0, 'asc']],
        "language": {"sProcessing": (language == 'vi') ? "Đang xử lý..." : "Processing...", "sLengthMenu": (language == 'vi') ? "_MENU_ dòng trên mỗi trang" : "_MENU_ records per page",
            "sZeroRecords": (language == 'vi') ? "Không tìm thấy dữ liệu" : "No matching records found",
            "sInfo": (language == 'vi') ? "Đang xem _START_ đến _END_ trong tổng số _TOTAL_ mục" : "Showing _START_ to _END_ of _TOTAL_ entries",
            "sInfoEmpty": (language == 'vi') ? "Đang xem 0 đến 0 trong tổng số 0 mục" : "Showing 0 to 0 of 0 entries",
            "sInfoFiltered": (language == 'vi') ? "(được lọc từ _MAX_ mục)" : "(filtered from _MAX_ total entries)",
            "sInfoPostFix": "",
            "sSearch": (language == 'vi') ? "Tìm:&nbsp;" : "Search:&nbsp;",
            "sUrl": "",
            "oPaginate": {
                "sFirst": (language == 'vi') ? "Đầu" : "First",
                "sPrevious": (language == 'vi') ? "Trước" : "Previous",
                "sNext": (language == 'vi') ? "Tiếp" : "Next",
                "sLast": (language == 'vi') ? "Cuối" : "Last"
            }},
        "fnDrawCallback": function () {
            setTimeout(function () {
                $('.overlay').hide();
            }, 1000);
        },
    });
});
</script>

@stop
