@extends('layouts.main')

@section('title', 'Nhóm quyền - Phân quyền nhóm')

@section('content')
@parent

<!-- Main content -->
<section class="content-header">
    <h1>
        Phân quyền nhóm
    </h1>
    <ol class="breadcrumb">
        <li><a href="{!! route('admin.index') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{!! route('admin.user-groups.getList') !!}">Nhóm quyền</a></li>
        <li class="active">Phân quyền nhóm</li>
    </ol>
</section>
<section class="content">

    <div class="box box-primary col-xs-12 col-sm-12 col-md-12 no-padding">
        <div class="box-body">
            @if (Session::has('auth-permission-assignment-success-message'))
            <div class="alert alert-info alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Thành công!</h4>
                {{ Session::get('auth-permission-assignment-success-message') }}
            </div>
            @elseif (Session::has('auth-permission-assignment-error-message'))
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> Lỗi!</h4>
                {{ Session::get('auth-permission-assignment-error-message') }}
            </div>
            @endif

            <div class="col-xs-12 col-sm-12 col-md-12 no-padding-left">
                <div class="auto-size box box-success inside-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Nhóm</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        {{ $userGroup['name'] }}
                    </div><!-- /.box-body -->
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-6 no-padding-left">
                <div class="box box-info inside-box certain-height-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Phân quyền Inside</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <ul>
                            @foreach ($routes as $controller => $actionList)
                            <li><span class="toggle-assignment" data-toggle="#{{ $controller }}">{{ Lang::get("messages.$controller") }}</span></li>
                            @endforeach
                        </ul>
                    </div><!-- /.box-body -->

                    <div class="box-header with-border">
                        <h3 class="box-title">Phân quyền Outside</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">

                    </div><!-- /.box-body -->
                </div>

            </div>

            <div class="col-xs-12 col-sm-12 col-md-6 no-padding-right">
                <div class="box box-info inside-box certain-height-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Thông tin quyền</h3>
                        <h3 id="assignment-name" class="box-title pull-right"></h3>
                    </div><!-- /.box-header -->

                    {!! Form::model($userGroup, ['route' => ['admin.auth-permissions.postPermission', $userGroup['id']], 'method' => 'POST', 'id' => 'auth-permission-assignment-form', 'class' => 'form-horizontal']) !!}

                    <div class="box-body">

                        @foreach ($routes as $controller => $actionList)
                        <ul id="{{ $controller }}" class="details-assignment hide">
                            @foreach ($actionList as $as => $action)
                            <li>
                                {!! Form::checkbox("checkbox[$controller][]", $as, in_array($as, $listPermissions) ? true : null, ['class' => '']) !!}
                                {{ Lang::get("messages.$action") }}
                            </li>
                            @endforeach          
                        </ul>
                        @endforeach


                    </div><!-- /.box-body -->

                    <div class="box-footer">
                        {!! Form::submit('Lưu', ['class' => 'btn btn-info pull-right']) !!}
                        <a href="{!! route('admin.user-groups.getList') !!}" class="btn btn-default">Về danh sách</a>
                    </div><!-- /.box-footer -->

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

</section>

<script>
    $(function () {
        $('.toggle-assignment').click(function () {
            $('.details-assignment').removeClass('show');
            var id = $(this).attr('data-toggle');
            var name = $(this).html();
            $(id).addClass('show');
            $('#assignment-name').html(name);
        });
    });
</script>

<!-- /.content -->
@stop
