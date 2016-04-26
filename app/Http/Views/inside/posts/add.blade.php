@extends('layouts.main')

@section('title', 'Bài viết - Thêm bài viết')

@section('content')
@parent

<!-- Main content -->
<section class="content-header">
    <h1>
        Thêm bài viết
    </h1>
    <ol class="breadcrumb">
        <li><a href="{!! route('admin.index') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{!! route('admin.posts.getList') !!}">Bài viết</a></li>
        <li class="active">Thêm bài viết</li>
    </ol>
</section>
<section class="content">
    <div class="box box-info">

        {!! Form::open(['url' => 'admin/posts/add', 'method' => 'POST', 'id' => 'post-add-form', 'class' => 'form-horizontal']) !!}

        <div class="box-body">

            @if (Session::has('post-add-success-message'))
            <div class="alert alert-info alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Thành công!</h4>
                {{ Session::get('post-add-success-message') }}
            </div>
            @elseif (Session::has('post-add-error-message'))
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> Lỗi!</h4>
                {{ Session::get('post-add-error-message') }}
            </div>
            @endif

            <div class="has-feedback">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        @foreach ($listLanguages as $id => $name)
                            <li class="{{ $id == 1 ? 'active' : '' }}">
                                <?php $errorIcon = '&nbsp;&nbsp;<img class="error-alert-icon" src="' . asset('public/inside/img/system/error_alert_icon.png') . '" />'; ; ?>
                                <a href="#{{ $id }}" data-toggle="tab">{!! $name !!} {!! ($errors->has("$id-name") ? $errorIcon : '') !!}</a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="tab-content">
                        @foreach ($listLanguages as $id => $name)
                            <div class="{{ $id == 1 ? 'active' : '' }} tab-pane" id="{{ $id }}">
                                <div class="form-group has-feedback {{ ($errors->has("$id-name") ? 'has-error' : '') }}">
                                    <div class="col-sm-2 control-label">
                                        {!! Form::label("$id-name", 'Tên', ['class' => '']) !!} <span class="required">*</span>
                                    </div>            
                                    <div class="col-sm-10">
                                        <?php $nameId = ($name === reset($listLanguages)) ? 'target-alias-name' : "$id-name"; ?>
                                        {!! Form::text("$id-name", Input::old("$id-name"), ['placeholder' => 'Tên', 'name' => "$id-name", 'class' => 'form-control', 'id' => $nameId]) !!}
                                        <span class="fa fa-info-circle form-control-feedback"></span>
                                        @if ($errors->has("$id-name"))
                                        <div class="error-messages">{{ $errors->first("$id-name") }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group has-feedback {{ ($errors->has("$id-short_description") ? 'has-error' : '') }}">
                                    <div class="col-sm-2 control-label">
                                        {!! Form::label("$id-short_description", 'Tóm lược', ['class' => '']) !!}
                                    </div>
                                    <div class="col-sm-10">
                                        {!! Form::textarea("$id-short_description", Input::old("$id-short_description"), ['placeholder' => 'Tóm lược', 'name' => "$id-short_description", 'class' => 'form-control ckeditor', 'style' => 'resize: none;']) !!}
                                        <span class="fa fa-newspaper-o form-control-feedback"></span>
                                        @if ($errors->has("$id-short_description"))
                                        <div class="error-messages">{{ $errors->first("$id-short_description") }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group has-feedback {{ ($errors->has("$id-full_description") ? 'has-error' : '') }}">
                                    <div class="col-sm-2 control-label">
                                        {!! Form::label("$id-full_description", 'Mô tả đầy đủ', ['class' => '']) !!}
                                    </div>
                                    <div class="col-sm-10">
                                        {!! Form::textarea("$id-full_description", Input::old("$id-full_description"), ['placeholder' => 'Mô tả đầy đủ', 'name' => "$id-full_description", 'class' => 'form-control ckeditor', 'style' => 'resize: none;']) !!}
                                        <span class="fa fa-newspaper-o form-control-feedback"></span>
                                        @if ($errors->has("$id-full_description"))
                                        <div class="error-messages">{{ $errors->first("$id-full_description") }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div><!-- /.tab-pane -->
                        @endforeach
                    </div><!-- /.tab-content -->
                </div>
            </div>

            <div class="form-group has-feedback {{ ($errors->has('alias_name') ? 'has-error' : '') }}">
                <div class="col-sm-2 control-label">
                    {!! Form::label('alias_name', 'Bí danh', ['class' => '']) !!} <span class="required">*</span>
                </div>
                <div class="col-sm-10">
                    {!! Form::text('alias_name', Input::old('alias_name'), ['placeholder' => 'Bí danh', 'name' => 'alias_name', 'class' => 'form-control']) !!}
                    <span class="fa fa-info-circle form-control-feedback"></span>
                    @if ($errors->has('alias_name'))
                    <div class="error-messages">{{ $errors->first('alias_name') }}</div>
                    @endif
                </div>
            </div>
            <div class="form-group has-feedback {{ ($errors->has('post_category_id') ? 'has-error' : '') }}">
                <div class="col-sm-2 control-label">
                    {!! Form::label('post_category_id', 'Thể loại', ['class' => '']) !!} <span class="required">*</span>
                </div>            
                <div class="col-sm-10">
                    {!! Form::select('post_category_id', $listPostCategories, Input::old('post_category_id'), ['name' => 'post_category_id', 'class' => 'form-control']) !!}
                    <span class="fa fa-th form-control-feedback"></span>
                    @if ($errors->has('post_category_id'))
                    <div class="error-messages">{{ $errors->first('post_category_id') }}</div>
                    @endif
                </div>
            </div>
            <div class="form-group has-feedback {{ ($errors->has('banner_id') ? 'has-error' : '') }}">
                <div class="col-sm-2 control-label">
                    {!! Form::label('banner_id', 'Banner liên quan', ['class' => '']) !!}
                </div>            
                <div class="col-sm-10">
                    {!! Form::select('banner_id', $listBanners, Input::old('banner_id'), ['name' => 'banner_id', 'class' => 'form-control']) !!}
                    <span class="fa fa-film form-control-feedback"></span>
                    @if ($errors->has('banner_id'))
                    <div class="error-messages">{{ $errors->first('banner_id') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group last p-l-thumbnail {{ ($errors->has('image_name') ? 'has-error' : '') }}">
                <div class="col-sm-2 control-label">
                    {!! Form::label('image_name', 'Hình đại diện', ['class' => '']) !!}
                    {!! Form::hidden('image_name', '', ['name' => 'image_name', 'class' => 'form-control']) !!}
                </div>
                <div class="col-md-10">
                    <?php
                    $img = Input::old('image_name');
                    $path = asset("public/inside/img/upload/posts/$img");
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
            
            <div class="form-group has-feedback {{ ($errors->has('ordering') ? 'has-error' : '') }}">
                <div class="col-sm-2 control-label">
                    {!! Form::label('ordering', 'Thứ tự', ['class' => '']) !!}
                </div>
                <div class="col-sm-10">
                    {!! Form::text('ordering', Input::old('ordering'), ['placeholder' => 'Thứ tự', 'name' => 'ordering', 'class' => 'form-control']) !!}
                    <span class="fa fa-list-ol form-control-feedback"></span>
                    @if ($errors->has('ordering'))
                    <div class="error-messages">{{ $errors->first('ordering') }}</div>
                    @endif
                </div>
            </div>

        </div><!-- /.box-body -->

        <div class="box-footer">
            {!! Form::submit('Lưu', ['class' => 'btn btn-info pull-right']) !!}
            
            @if (isset($myPermission['admin.posts.getList']))
            <a href="{!! route('admin.posts.getList') !!}" class="btn btn-default">Về danh sách</a>
            @endif
        </div><!-- /.box-footer -->

        {!! Form::close() !!}

    </div>
</section>

<script src="{{ asset('public/inside/assets/fckeditor/ckfinder/ckfinder.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('public/inside/assets/fckeditor/ckeditor/ckeditor.js') }}" ></script>

<script type="text/javascript">
    $(function() {
        $('#target-alias-name').blur(function () {
            var value = $(this).val();
            var toASCII = value.normalize('NFKD').replace(/[\u0300-\u036F]/g, '');
            var finalStr = toASCII.replace(/đ/g, 'd').replace(/Đ/g, 'D').replace(/\s/g, '-');
            $('#alias_name').val(finalStr.toLowerCase());
        });
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
