@extends('layouts.main')

@section('title', 'Thành viên - Sửa thông tin thành viên')

@section('content')
@parent

<!-- Main content -->
<section class="content-header">
    <h1>
        Sửa thông tin thành viên
    </h1>
    <ol class="breadcrumb">
        <li><a href="{!! route('admin.index') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{!! route('admin.users.getList') !!}">Thành viên</a></li>
        <li class="active">Sửa thông tin thành viên</li>
    </ol>
</section>
<section class="content">
    <div class="box box-info">
        
        {!! Form::model($user + $profile, ['route' => ['admin.users.postEdit', $user['id']], 'method' => 'POST', 'id' => 'user-edit-form', 'class' => 'form-horizontal']) !!}
        
        <div class="box-body">

            @if (Session::has('user-edit-success-message'))
                <div class="alert alert-info alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-check"></i> Thành công!</h4>
                    {{ Session::get('user-edit-success-message') }}
                </div>
            @elseif (Session::has('user-edit-error-message'))
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-ban"></i> Lỗi!</h4>
                    {{ Session::get('user-edit-error-message') }}
                </div>
            @endif

            <div class="form-group has-feedback {{ ($errors->has('first_name') ? 'has-error' : '') }}">
                <div class="col-sm-2 control-label">
                    {!! Form::label('first_name', 'Tên', ['class' => '']) !!} <span class="required">*</span>
                </div>
                <div class="col-sm-10">
                    {!! Form::text('first_name', Input::old('first_name'), ['placeholder' => 'Tên', 'name' => 'first_name', 'class' => 'form-control']) !!}
                    <span class="fa fa-info-circle form-control-feedback"></span>
                    @if ($errors->has('first_name'))
                    <div class="error-messages">{{ $errors->first('first_name') }}</div>
                    @endif
                </div>
            </div>
            <div class="form-group has-feedback {{ ($errors->has('last_name') ? 'has-error' : '') }}">
                <div class="col-sm-2 control-label">
                    {!! Form::label('last_name', 'Họ', ['class' => '']) !!} <span class="required">*</span>
                </div>
                <div class="col-sm-10">
                    {!! Form::text('last_name', Input::old('last_name'), ['placeholder' => 'Họ', 'name' => 'last_name', 'class' => 'form-control']) !!}
                    <span class="fa fa-info-circle form-control-feedback"></span>
                    @if ($errors->has('last_name'))
                    <div class="error-messages">{{ $errors->first('last_name') }}</div>
                    @endif
                </div>
            </div>
            <div class="form-group has-feedback {{ ($errors->has('email') ? 'has-error' : '') }}">
                <div class="col-sm-2 control-label">
                    {!! Form::label('email', 'Email', ['class' => '']) !!} <span class="required">*</span>
                </div>
                <div class="col-sm-10">
                    {!! Form::email('email', Input::old('email'), ['placeholder' => 'Email', 'name' => 'email', 'class' => 'form-control']) !!}
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    @if ($errors->has('email'))
                    <div class="error-messages">{{ $errors->first('email') }}</div>
                    @endif
                </div>
            </div>
            <div class="form-group has-feedback {{ ($errors->has('date_of_birth') ? 'has-error' : '') }}">
                <div class="col-sm-2 control-label">
                    {!! Form::label('date_of_birth', 'Ngày sinh', ['class' => '']) !!} <span class="required">*</span>
                </div>
                <div class="col-sm-10">
                    <!-- Date dd/mm/yyyy -->
                    {!! Form::text('date_of_birth', Input::old('date_of_birth'), ['placeholder' => 'Ngày sinh', 'name' => 'date_of_birth', 'class' => 'form-control', 'data-inputmask' => "'alias': 'dd/mm/yyyy'", 'data-mask']) !!}


                    <span class="fa fa-calendar form-control-feedback"></span>
                    @if ($errors->has('date_of_birth'))
                    <div class="error-messages">{{ $errors->first('date_of_birth') }}</div>
                    @endif
                </div>
            </div>
            <div class="form-group has-feedback {{ ($errors->has('gender') ? 'has-error' : '') }}">
                <div class="col-sm-2 control-label">
                    {!! Form::label('gender', 'Giới tính', ['class' => '']) !!} <span class="required">*</span>
                </div>            
                <div class="col-sm-10">
                    {!! Form::select('gender', $listGenders, Input::old('gender'), ['name' => 'gender', 'class' => 'form-control']) !!}
                    <span class="fa fa-venus-mars form-control-feedback"></span>
                    @if ($errors->has('gender'))
                    <div class="error-messages">{{ $errors->first('gender') }}</div>
                    @endif
                </div>
            </div>
            <div class="form-group has-feedback {{ ($errors->has('employee_no') ? 'has-error' : '') }}">
                <div class="col-sm-2 control-label">
                    {!! Form::label('employee_no', 'Mã nhân viên', ['class' => '']) !!} <span class="required">*</span>
                </div>
                <div class="col-sm-10">
                    {!! Form::text('employee_no', Input::old('employee_no'), ['placeholder' => 'Mã nhân viên', 'name' => 'employee_no', 'class' => 'form-control']) !!}
                    <span class="fa fa-credit-card form-control-feedback"></span>
                    @if ($errors->has('employee_no'))
                    <div class="error-messages">{{ $errors->first('employee_no') }}</div>
                    @endif
                </div>
            </div>
            <div class="form-group has-feedback {{ ($errors->has('social_security_no') ? 'has-error' : '') }}">
                <div class="col-sm-2 control-label">
                    {!! Form::label('social_security_no', 'CMND', ['class' => '']) !!} <span class="required">*</span>
                </div>
                <div class="col-sm-10">
                    {!! Form::text('social_security_no', Input::old('social_security_no'), ['placeholder' => 'CMND', 'name' => 'social_security_no', 'class' => 'form-control']) !!}
                    <span class="fa fa-credit-card form-control-feedback"></span>
                    @if ($errors->has('social_security_no'))
                    <div class="error-messages">{{ $errors->first('social_security_no') }}</div>
                    @endif
                </div>
            </div>
            <div class="form-group has-feedback {{ ($errors->has('phone_no') ? 'has-error' : '') }}">
                <div class="col-sm-2 control-label">
                    {!! Form::label('phone_no', 'Số điện thoại', ['class' => '']) !!} <span class="required">*</span>
                </div>
                <div class="col-sm-10">
                    {!! Form::text('phone_no', Input::old('phone_no'), ['placeholder' => 'Số điện thoại', 'name' => 'phone_no', 'class' => 'form-control']) !!}
                    <span class="fa fa-mobile form-control-feedback"></span>
                    @if ($errors->has('phone_no'))
                    <div class="error-messages">{{ $errors->first('phone_no') }}</div>
                    @endif
                </div>
            </div>        
            <div class="form-group has-feedback {{ ($errors->has('address') ? 'has-error' : '') }}">
                <div class="col-sm-2 control-label">
                    {!! Form::label('address', 'Địa chỉ', ['class' => '']) !!} <span class="required">*</span>
                </div>
                <div class="col-sm-10">
                    {!! Form::text('address', Input::old('address'), ['placeholder' => 'Địa chỉ', 'name' => 'address', 'class' => 'form-control']) !!}
                    <span class="fa fa-home form-control-feedback"></span>
                    @if ($errors->has('address'))
                    <div class="error-messages">{{ $errors->first('address') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group has-feedback {{ ($errors->has('user_group_id') ? 'has-error' : '') }}">
                <div class="col-sm-2 control-label">
                    {!! Form::label('user_group_id', 'Nhóm', ['class' => '']) !!} <span class="required">*</span>
                </div>            
                <div class="col-sm-10">
                    {!! Form::select('user_group_id', $listUserGroups, Input::old('user_group_id'), ['name' => 'user_group_id', 'class' => 'form-control']) !!}
                    <span class="fa fa-users form-control-feedback"></span>
                    @if ($errors->has('user_group_id'))
                    <div class="error-messages">{{ $errors->first('user_group_id') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group last p-l-thumbnail {{ ($errors->has('image_name') ? 'has-error' : '') }}">
                <div class="col-sm-2 control-label">
                    {!! Form::label('image_name', 'Hình đại diện', ['class' => '']) !!}
                    {!! Form::hidden('image_name', Input::old('image_name'), ['name' => 'image_name', 'class' => 'form-control']) !!}
                </div>
                <div class="col-md-10">
                    <?php
                    $img = empty(Input::old('image_name')) ? $user['image_name'] : Input::old('image_name');
                    
                    $path = asset("public/inside/img/upload/users/$img");
                    $header_response = get_headers($path, 1);

                    if (strpos($header_response[0], "200") == false || empty($img)) {
                        $path = asset('public/inside/img/system/noname.png');
                    }
                    ?>

                    <img id="thumb_img" src="{{ $path }}" alt="" />
                    <div class="p-l-file">
                        <a href="#" onclick="BrowseServer();" class="iframe-btn" type="button">
                            <i class="fa fa-paperclip"></i>&nbsp;&nbsp;Chọn hình
                        </a>
                    </div>
                    @if ($errors->has('image_name'))
                    <div class="error-messages">{{ $errors->first('image_name') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group has-feedback {{ ($errors->has('story') ? 'has-error' : '') }}">
                <div class="col-sm-2 control-label">
                    {!! Form::label('story', 'Giới thiệu', ['class' => '']) !!}
                </div>
                <div class="col-sm-10">
                    {!! Form::textarea('story', Input::old('story'), ['placeholder' => 'Giới thiệu', 'name' => 'story', 'class' => 'form-control', 'style' => 'resize: none;']) !!}
                    <span class="fa fa-newspaper-o form-control-feedback"></span>
                    @if ($errors->has('story'))
                    <div class="error-messages">{{ $errors->first('story') }}</div>
                    @endif
                </div>
            </div>

        </div><!-- /.box-body -->

        <div class="box-footer">
            {!! Form::submit('Lưu', ['class' => 'btn btn-info pull-right']) !!}
            
            @if (isset($myPermission['admin.users.getList']))
            <a href="{!! route('admin.users.getList') !!}" class="btn btn-default">Về danh sách</a>
            @endif
        </div><!-- /.box-footer -->

        {!! Form::close() !!}

    </div>
</section>


<script src="{{ asset('public/inside/assets/fckeditor/ckfinder/ckfinder.js') }}"></script>

<!-- InputMask -->
<script src="{{ asset('public/inside/assets/input-mask/jquery.inputmask.js') }}"></script>
<script src="{{ asset('public/inside/assets/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
<script src="{{ asset('public/inside/assets/input-mask/jquery.inputmask.extensions.js') }}"></script>

<script type="text/javascript">
    $(function () {
        var curYear = new Date().getFullYear();
        //Datemask dd/mm/yyyy
        $("#date_of_birth").inputmask(
                "dd/mm/yyyy",
                {
                    "placeholder": "dd/mm/yyyy",
                    "yearrange": { "minyear": 1900, "maxyear": parseInt(curYear) - 18 }
                }
        );
    });
    function BrowseServer() {
        var finder = new CKFinder();
        //finder.basePath = '../';
        finder.selectActionFunction = SetFileField;
        finder.popup();
    }
    function SetFileField(fileUrl) {
        document.getElementById('image_name').value = fileUrl;
        document.getElementById('thumb_img').src = fileUrl;
    }
</script>
<!-- /.content -->
@stop
