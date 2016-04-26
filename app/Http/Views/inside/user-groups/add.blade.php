@extends('layouts.main')

@section('title', 'Nhóm quyền - Thêm nhóm')

@section('content')
@parent

<!-- Main content -->
<section class="content-header">
    <h1>
        Thêm nhóm
    </h1>
    <ol class="breadcrumb">
        <li><a href="{!! route('admin.index') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{!! route('admin.user-groups.getList') !!}">Nhóm quyền</a></li>
        <li class="active">Thêm nhóm</li>
    </ol>
</section>
<section class="content">
    <div class="box box-info">

        {!! Form::open(['url' => 'admin/user-groups/add', 'method' => 'POST', 'id' => 'user-group-add-form', 'class' => 'form-horizontal']) !!}

        <div class="box-body">

            @if (Session::has('user-group-add-success-message'))
            <div class="alert alert-info alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Thành công!</h4>
                {{ Session::get('user-group-add-success-message') }}
            </div>
            @elseif (Session::has('user-group-add-error-message'))
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> Lỗi!</h4>
                {{ Session::get('user-group-add-error-message') }}
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

        </div><!-- /.box-body -->

        <div class="box-footer">
            {!! Form::submit('Lưu', ['class' => 'btn btn-info pull-right']) !!}
            
            @if (isset($myPermission['admin.user-groups.getList']))
            <a href="{!! route('admin.user-groups.getList') !!}" class="btn btn-default">Về danh sách</a>
            @endif
        </div><!-- /.box-footer -->

        {!! Form::close() !!}

    </div>
</section>
<!-- /.content -->
@stop
