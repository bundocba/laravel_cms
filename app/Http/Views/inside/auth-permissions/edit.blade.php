@extends('layouts.main')

@section('title', 'Đổi nhóm thành viên - Đổi nhóm quyền')

@section('content')
@parent

<!-- Main content -->
<section class="content-header">
    <h1>
        Đổi nhóm quyền
    </h1>
    <ol class="breadcrumb">
        <li><a href="{!! route('admin.index') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{!! route('admin.auth-permissions.getListPermission') !!}">Đổi nhóm thành viên</a></li>
        <li class="active">Sửa thông tin nhóm</li>
    </ol>
</section>
<section class="content">

    <div class="box box-primary col-xs-12 col-sm-12 col-md-12 no-padding">
        <div class="box-body">
            @if (Session::has('auth-permission-edit-success-message'))
            <div class="alert alert-info alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Thành công!</h4>
                {{ Session::get('auth-permission-edit-success-message') }}
            </div>
            @elseif (Session::has('auth-permission-edit-error-message'))
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> Lỗi!</h4>
                {{ Session::get('auth-permission-edit-error-message') }}
            </div>
            @endif

            <!-- Profile Image -->
            <div class="col-xs-12 col-sm-12 col-md-6 no-padding-left">
                <div class="box box-info inside-box">
                    <div class="box-body box-profile">
                        <?php
                        $img = $user['image_name'];
                        $path = asset("public/inside/img/upload/users/$img");
                        $header_response = get_headers($path, 1);

                        if (strpos($header_response[0], "200") == false || empty($img)) {
                            $path = asset('public/inside/img/system/noname.png');
                        }
                        ?>
                        <img class="profile-user-img img-responsive img-circle" src="{{ $path }}" alt="User profile picture">
                        <h3 class="profile-username text-center">{{ (isset($user['profile']['first_name']) && isset($user['profile']['last_name'])) ? $user['profile']['first_name'] . ' ' . $user['profile']['last_name'] : '' }}</h3>
                        <p class="text-muted text-center">{{ $group['name'] }} </p>

                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>Email</b> <a class="pull-right">{{ $user['email'] }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Trạng thái</b> <a class="pull-right">
                                    @if ($user['is_deleted']) 
                                    <span class="glyphicon glyphicon-minus-sign clr-red" aria-hidden="true"></span>
                                    @else
                                    <span class="glyphicon glyphicon-ok-sign clr-green" aria-hidden="true"></span>
                                    @endif
                                </a>
                            </li>
                            <li class="list-group-item">
                                <b>Đăng nhập gần đây</b> <span class="pull-right">{{ $user['last_login'] }}</span>
                            </li>
                        </ul>
                    </div><!-- /.box-body -->
                </div>
                <div class="box box-info inside-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Thông tin cá nhân</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="col-xs-12 col-sm-12 col-md-12 border-bottom vertical-padding">
                            <div class="col-xs-12 col-sm-6 col-md-6 no-padding">
                                <strong><i class="fa fa-calendar margin-r-5"></i>  Ngày sinh</strong>
                                <p class="text-muted">
                                    {{ $user['profile']['date_of_birth'] }}
                                </p>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6 no-padding">
                                <strong><i class="fa fa-venus-mars margin-r-5"></i> Giới tính</strong>
                                <p class="text-muted">
                                    <?php $gender = $user['profile']['gender']; ?>
                                    {{ $listGenders[$gender] }}
                                </p>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 border-bottom vertical-padding">
                            <div class="col-xs-12 col-sm-6 col-md-6 no-padding">
                                <strong><i class="fa fa-credit-card margin-r-5"></i> Mã nhân viên</strong>
                                <p class="text-muted">
                                    {{ $user['profile']['employee_no'] }}
                                </p>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6 no-padding">
                                <strong><i class="fa fa-credit-card margin-r-5"></i> CMND</strong>
                                <p class="text-muted">
                                    {{ $user['profile']['social_security_no'] }}
                                </p>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 border-bottom vertical-padding">
                            <div class="col-xs-12 col-sm-6 col-md-6 no-padding">
                                <strong><i class="fa fa-mobile margin-r-5"></i> Số điện thoại</strong>
                                <p class="text-muted">
                                    {{ $user['profile']['phone_no'] }}
                                </p>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6 no-padding">
                                <strong><i class="fa fa-home margin-r-5"></i> Địa chỉ</strong>
                                <p class="text-muted">
                                    {{ $user['profile']['address'] }}
                                </p>
                            </div>
                        </div>

                        <strong><i class="fa fa-newspaper-o margin-r-5"></i> Giới thiệu</strong>
                        <p>{!! $user['profile']['story'] !!}</p>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>

            <!-- About Me Box -->
            <div class="col-xs-12 col-sm-12 col-md-6 no-padding-right">
                <div class="box box-info inside-box">
                    {!! Form::model($user, ['route' => ['admin.auth-permissions.postEditPermission', $user['id']], 'method' => 'POST', 'id' => 'auth-permission-edit-form', 'class' => 'form-horizontal']) !!}

                    <div class="box-body">

                        <div class="form-group has-feedback {{ ($errors->has('user_group_id') ? 'has-error' : '') }}">
                            <div class="col-sm-2 control-label">
                                {!! Form::label('user_group_id', 'Nhóm quyền', ['class' => '']) !!} <span class="required">*</span>
                            </div>
                            <div class="col-sm-10">
                                {!! Form::select('user_group_id', $listUserGroups, Input::old('user_group_id'), ['name' => 'user_group_id', 'class' => 'form-control']) !!}
                                <span class="fa fa-info-circle form-control-feedback"></span>
                                @if ($errors->has('user_group_id'))
                                <div class="error-messages">{{ $errors->first('user_group_id') }}</div>
                                @endif
                            </div>
                        </div>

                    </div><!-- /.box-body -->

                    <div class="box-footer">
                        {!! Form::submit('Lưu', ['class' => 'btn btn-info pull-right']) !!}
                        
                        @if (isset($myPermission['admin.auth-permissions.getListPermission']))
                        <a href="{!! route('admin.auth-permissions.getListPermission') !!}" class="btn btn-default">Về danh sách</a>
                        @endif
                    </div><!-- /.box-footer -->

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

</section>

<!-- /.content -->
@stop
