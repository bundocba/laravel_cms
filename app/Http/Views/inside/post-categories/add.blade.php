@extends('layouts.main')

@section('title', 'Thể loại - Thêm thể loại')

@section('content')
@parent

<!-- Main content -->
<section class="content-header">
    <h1>
        Thêm thể loại
    </h1>
    <ol class="breadcrumb">
        <li><a href="{!! route('admin.index') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{!! route('admin.post-categories.getList') !!}">Thể loại</a></li>
        <li class="active">Thêm thể loại</li>
    </ol>
</section>
<section class="content">
    <div class="box box-info">

        {!! Form::open(['url' => 'admin/post-categories/add', 'method' => 'POST', 'id' => 'post-category-add-form', 'class' => 'form-horizontal']) !!}

        <div class="box-body">

            @if (Session::has('post-category-add-success-message'))
            <div class="alert alert-info alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Thành công!</h4>
                {{ Session::get('post-category-add-success-message') }}
            </div>
            @elseif (Session::has('post-category-add-error-message'))
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> Lỗi!</h4>
                {{ Session::get('post-category-add-error-message') }}
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
                                <div class="form-group has-feedback {{ ($errors->has("$id-description") ? 'has-error' : '') }}">
                                    <div class="col-sm-2 control-label">
                                        {!! Form::label("$id-description", 'Mô tả', ['class' => '']) !!}
                                    </div>
                                    <div class="col-sm-10">
                                        {!! Form::textarea("$id-description", Input::old("$id-description"), ['placeholder' => 'Mô tả đầy đủ', 'name' => "$id-description", 'class' => 'form-control ckeditor', 'style' => 'resize: none;']) !!}
                                        <span class="fa fa-newspaper-o form-control-feedback"></span>
                                        @if ($errors->has("$id-description"))
                                        <div class="error-messages">{{ $errors->first("$id-description") }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div><!-- /.tab-pane -->
                        @endforeach
                    </div><!-- /.tab-content -->
                </div>
            </div>

            <div class="form-group has-feedback {{ ($errors->has('parent_id') ? 'has-error' : '') }}">
                <div class="col-sm-2 control-label">
                    {!! Form::label('parent_id', 'Thể loại cha', ['class' => '']) !!} <span class="required">*</span>
                </div>            
                <div class="col-sm-10">
                    {!! Form::select('parent_id', $listPostCategories, Input::old('parent_id'), ['name' => 'parent_id', 'class' => 'form-control']) !!}
                    <span class="fa fa-th form-control-feedback"></span>
                    @if ($errors->has('parent_id'))
                    <div class="error-messages">{{ $errors->first('parent_id') }}</div>
                    @endif
                </div>
            </div>
            
        </div><!-- /.box-body -->

        <div class="box-footer">
            {!! Form::submit('Lưu', ['class' => 'btn btn-info pull-right']) !!}
            
            @if (isset($myPermission['admin.post-categories.getList']))
            <a href="{!! route('admin.post-categories.getList') !!}" class="btn btn-default">Về danh sách</a>
            @endif
        </div><!-- /.box-footer -->

        {!! Form::close() !!}

    </div>
</section>

<!-- /.content -->
@stop
