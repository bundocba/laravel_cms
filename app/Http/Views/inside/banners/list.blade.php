@extends('layouts.main')

<?php $title = !$isTrash ? 'Banner - Danh sách banner' : 'Banner - Danh sách thùng rác'; ?>

@section('title', $title)

@section('content')
@parent

<!-- Main content -->
<section class="content-header">
    <h1>
        Danh sách <?php echo!$isTrash ? 'banner' : 'thùng rác'; ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{!! route('admin.index') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{!! route('admin.banners.getList') !!}">Banner</a></li>
        <li class="active">Danh sách <?php echo!$isTrash ? 'banner' : 'thùng rác'; ?></li>
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

            @if (Session::has('banner-inactive-success-message'))
            <div class="alert alert-info alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Thành công!</h4>
                {{ Session::get('banner-inactive-success-message') }}
            </div>
            @elseif (Session::has('banner-inactive-error-message'))
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> Lỗi!</h4>
                {{ Session::get('banner-inactive-error-message') }}
            </div>
            @endif

            <div class="btn-wrapper col-xs-12">
                <div class="btn-group pull-right">
                    @if (isset($myPermission['admin.banners.getAdd']))
                    <a href="{!! route('admin.banners.getAdd') !!}">
                        <button class="btn btn-primary">
                            Thêm mới <i class="fa fa-plus"></i>
                        </button>
                    </a>
                    @endif
                    
                    <?php $route = $isTrash ? 'admin.banners.getList' : 'admin.banners.getTrash'; ?>
                    @if (isset($myPermission[$route]))
                    <a href="{!! route($route) !!}">
                        <button class="btn <?php echo $isTrash ? 'btn-success' : 'btn-danger' ?>"">
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
                            <p>Loại</p>
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
                            <p>Hình</p>
                        </td>
                        <td class="table-td none">
                            <p>Người tạo</p>
                        </td>
                        <td class="table-td none">
                            <p>Người sửa</p>
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
                        @foreach ($list as $index => $row)
                        <tr class="table-tr">
                            <td class = "table-td">
                            </td>
                            <td class="table-td">
                                <p>{{ $index + 1 }}</p>
                            </td>
                            <td class="table-td">
                                <p>{!! str_limit(strip_tags($row['name']), 50) !!}</p>
                            </td>
                            <td class="table-td">
                                <p>
                                    <?php
                                    $type = $row['type'];
                                    echo $listTypes[$type];
                                    ?>
                                </p>
                            </td>
                            <td class="table-td">
                                <p>
                                    @if ($row['is_deleted']) 
                                    <span class="glyphicon glyphicon-minus-sign clr-red" aria-hidden="true"></span>
                                    @else
                                    <span class="glyphicon glyphicon-ok-sign clr-green" aria-hidden="true"></span>
                                    @endif
                                </p>
                            </td>
                            <td class="table-td">
                                <p>
                                    @if ($row['is_deleted'] && isset($myPermission['admin.banners.undo']))
                                        <a href="{!! route('admin.banners.undo', $row['id']) !!}" class="btn btn-primary tooltips btn-xs" data-placement="top" data-original-title="Kích hoạt lại">
                                            <i class="fa fa-undo"></i>
                                        </a>
                                    @else
                                    
                                        @if (isset($myPermission['admin.banners.getEdit']))
                                        <a href="{!! route('admin.banners.getEdit', $row['id']) !!}" class="btn btn-primary tooltips btn-xs" data-placement="top" data-original-title="Sửa">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        @endif
                                    
                                        @if (isset($myPermission['admin.banners.inactive']))
                                        <a href="{!! route('admin.banners.inactive', $row['id']) !!}" class="btn btn-danger tooltips btn-xs" data-placement="top" data-original-title="Khóa">
                                            <i class="fa fa-trash-o"></i>
                                        </a>
                                        @endif
                                    @endif
                                </p>
                            </td>

                            <td class="table-td none">
                                <p>
                                    <?php
                                    $img = $row['image_name'];
                                    $path = asset("public/inside/img/upload/banners/$img");
                                    $header_response = get_headers($path, 1);

                                    if (strpos($header_response[0], "200") == false || empty($img)) {
                                        $path = asset('public/inside/img/system/no-image.jpg');
                                    }
                                    ?>
                                    <img src="{{ $path }}" />
                                </p>
                            </td>
                            <td class="table-td none">
                                <p>
                                    <?php
                                    $userId = $row['created_by'];
                                    echo $listUsers[$userId];
                                    ?>
                                </p>
                            </td>
                            <td class="table-td none">
                                <p>
                                    <?php
                                    $category = $row['modified_by'];
                                    echo $listUsers[$userId];
                                    ?>
                                </p>
                            </td>
                            
                            <td class="table-td none">
                                <p>{{ $row['created_at'] }}</p>
                            </td>
                            <td class="table-td none">
                                <p>{{ $row['updated_at'] }}</p>
                            </td>
                        </tr>
                        @endforeach
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
    $.fn.dataTable.ext.pager.numbers_length = 7;
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
            {"bSearchable": false, "aTargets": [0, 4, 5, 6, 7, 8, 9]},
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
