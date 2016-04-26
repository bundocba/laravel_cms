@extends('layouts.main')
@section('title', 'Danh Sách Liên Hệ - Thêm Danh Sách Liên Hệ')

@section('content')
@parent
<!-- Main content -->
<section class="content-header">
    <h1>
        Thêm danh sách liên hệ
    </h1>
    <ol class="breadcrumb">
        <li><a href="{!! route('admin.index') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{!! route('admin.customer-contacts.getList') !!}">Danh Sách Liên Hệ</a></li>
        <li class="active">Thêm Danh Sách Liên Hệ</li>
    </ol>
</section>
<section class="content">
    <div class="box box-info">
        {!! Form::open([
        'id'=>'customer-contacts',
        'class'=>'form-horizontal'
        ]) !!}
        <div class="box-body">
            @if (Session::has('customer-contacts-add-success-message'))
            <div class="alert alert-info alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Thành công!</h4>
                {{ Session::get('customer-contacts-add-success-message') }}
            </div>
            @elseif (Session::has('customer-contacts-add-error-message'))
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> Lỗi!</h4>
                {{ Session::get('customer-contacts-add-error-message') }}
            </div>
            @endif

            <div class="form-group has-feedback {{ ($errors->has('company_name') ? 'has-error' : '') }}">
                <div class="col-sm-2 control-label">
                    {!! Form::label('company_name', 'Tên Công Ty', ['class' => '']) !!} <span class="required">*</span>
                </div>
                <div class="col-sm-10">
                    {!! Form::text('company_name', Input::old('company_name'), ['placeholder' => 'Tên Công Ty', 'company_name' => 'company_name', 'class' => 'form-control']) !!}
                    <span class="fa fa-info-circle form-control-feedback"></span>
                    @if ($errors->has('company_name'))
                    <div class="error-messages">{{ $errors->first('company_name') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group has-feedback {{ ($errors->has('full_name') ? 'has-error' : '') }}">
                <div class="col-sm-2 control-label">
                    {!! Form::label('full_name', 'Họ Tên', ['class' => '']) !!} <span class="required">*</span>
                </div>
                <div class="col-sm-10">
                    {!! Form::text('full_name', Input::old('full_name'), ['placeholder' => 'Họ Tên', 'full_name' => 'full_name', 'class' => 'form-control']) !!}
                    <span class="fa fa-info-circle form-control-feedback"></span>
                    @if ($errors->has('full_name'))
                    <div class="error-messages">{{ $errors->first('full_name') }}</div>
                    @endif
                </div>
            </div>

            <!-- /.box-body -->

            <div class="box-footer">
                {!! Form::submit('Lưu', ['class' => 'btn btn-info pull-right']) !!}

                @if (isset($myPermission['admin.customer-contacts.getList']))
                <a href="{!! route('admin.customer-contacts.getList') !!}" class="btn btn-default">Về danh sách</a>
                @endif
            </div><!-- /.box-footer -->
        </div>
        {!! Form::close() !!}
    </div>
</div>
@stop