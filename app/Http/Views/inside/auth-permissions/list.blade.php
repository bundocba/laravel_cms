@extends('layouts.main')

<?php $title = 'Đổi nhóm quyền - Danh sách thành viên'; ?>

@section('title', $title)

@section('content')
@parent

<!-- Main content -->
<section class="content-header">
    <h1>
        Danh sách thành viên
    </h1>
    <ol class="breadcrumb">
        <li><a href="{!! route('admin.index') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{!! route('admin.auth-permissions.getListPermission') !!}">Đổi nhóm quyền</a></li>
        <li class="active">Danh sách thành viên</li>
    </ol>
</section>
<section class="content">
    <div class="box box-primary">
        <div class="box-body" id="u-l-list">
            <div class="overlay">
                <div class="o-message">
                    <p class="message">
                        Đang tải dữ liệu, vui lòng chờ trong giây lát...
                        <img src="{{ asset('public/inside/img/icons/radio.gif') }}"  />
                    </p>
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
                            <p>Email</p>
                        </td>
                        <td class="table-td">
                            <p>Tên</p>
                        </td>
                        <td class="table-td">
                            <p>Họ</p>
                        </td>
                        <td class="table-td">
                            <p>Nhóm</p>
                        </td>
                        <td class="table-td">
                            <p>Đổi nhóm quyền</p>
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
                                    <p>{{ $row['email'] }}</p>
                                </td>
                                <td class="table-td">
                                    <p>{{ $row['profile']['first_name'] }}</p>
                                </td>
                                <td class="table-td">
                                    <p>{{ $row['profile']['last_name'] }}</p>
                                </td>
                                <td class="table-td">
                                    <p>
                                        <?php $userGroupId = $row['user_group_id']; ?>
                                        {{ $listUserGroups[$userGroupId] }}
                                    </p>
                                </td>
                                <td class="table-td">
                                    <p>
                                        <?php //if (isset($assignment['delete']) && !$item['is_deleted']): ?>
                                        @if (isset($myPermission['admin.auth-permissions.getEditPermission']) && Auth::user()->id != $row['id'])
                                        <a href="{!! route('admin.auth-permissions.getEditPermission', $row['id']) !!}" class="btn btn-primary tooltips btn-xs" data-placement="top" data-original-title="Đổi nhóm quyền">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        @endif
                                        <?php //endif ?>
                                    </p>
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
            {"bSortable": false, "aTargets": [0, 6]},
            {"bSearchable": false, "aTargets": [0, 6]},
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
