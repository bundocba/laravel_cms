@extends('layouts.main')

@section('title', 'Banner - Sửa thông tin banner')

@section('content')
@parent

<!-- Main content -->
<section class="content-header">
    <h1>
        Sửa thông tin banner
    </h1>
    <ol class="breadcrumb">
        <li><a href="{!! route('admin.index') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{!! route('admin.banners.getList') !!}">Banner</a></li>
        <li class="active">Sửa thông tin banner</li>
    </ol>
</section>
<section class="content">
    <div class="box box-info">
        
        {!! Form::model($banner, ['route' => ['admin.banners.postEdit', $banner['id']], 'method' => 'POST', 'id' => 'banner-edit-form', 'class' => 'form-horizontal']) !!}
        
        <div class="box-body">

            @if (Session::has('banner-edit-success-message'))
                <div class="alert alert-info alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-check"></i> Thành công!</h4>
                    {{ Session::get('banner-edit-success-message') }}
                </div>
            @elseif (Session::has('banner-edit-error-message'))
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-ban"></i> Lỗi!</h4>
                    {{ Session::get('banner-edit-error-message') }}
                </div>
            @endif

            <div class="form-group has-feedback {{ ($errors->has('name') ? 'has-error' : '') }}">
                <div class="col-sm-2 control-label">
                    {!! Form::label('name', 'Tên', ['class' => '']) !!} <span class="required">*</span>
                </div>
                <div class="col-sm-10">
                    {!! Form::text('name', Input::old('name'), ['placeholder' => 'Tên', 'name' => 'name', 'class' => 'form-control']) !!}
                    <span class="fa fa-info-circle form-control-feedback"></span>
                    @if ($errors->has('name'))
                    <div class="error-messages">{{ $errors->first('name') }}</div>
                    @endif
                </div>
            </div>
            
            <div class="form-group has-feedback {{ ($errors->has('type') ? 'has-error' : '') }}">
                <div class="col-sm-2 control-label">
                    {!! Form::label('type', 'Loại', ['class' => '']) !!} <span class="required">*</span>
                </div>            
                <div class="col-sm-10">
                    {!! Form::select('type', $listTypes, Input::old('type'), ['name' => 'type', 'class' => 'form-control']) !!}
                    <span class="fa fa-th form-control-feedback"></span>
                    @if ($errors->has('type'))
                    <div class="error-messages">{{ $errors->first('type') }}</div>
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
                    $img = empty(Input::old('image_name')) ? $banner['image_name'] : Input::old('image_name');
                    
                    $path = asset("public/inside/img/upload/banners/$img");
                    $header_response = get_headers($path, 1);

                    if (strpos($header_response[0], "200") == false || empty($img)) {
                        $path = asset('public/inside/img/system/no-image.jpg');
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

        </div><!-- /.box-body -->

        <div class="box-footer">
            {!! Form::submit('Lưu', ['class' => 'btn btn-info pull-right']) !!}
            
            @if (isset($myPermission['admin.banners.getList']))
            <a href="{!! route('admin.banners.getList') !!}" class="btn btn-default">Về danh sách</a>
            @endif
        </div><!-- /.box-footer -->

        {!! Form::close() !!}

    </div>
</section>


<script src="{{ asset('public/inside/assets/fckeditor/ckfinder/ckfinder.js') }}"></script>

<script type="text/javascript">
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
