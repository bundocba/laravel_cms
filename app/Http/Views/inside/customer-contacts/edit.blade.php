@extends('layouts.main')
@section('title', 'Danh Sách Liên Hệ - Thêm Danh Sách Liên Hệ')

@section('content')
@parent
<!-- Main content -->
<section class="content-header">
    <h1>
        Sửa thông tin Danh sách liên hệ
    </h1>
    <ol class="breadcrumb">
        <li><a href="{!! route('admin.index') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{!! route('admin.customer-contacts.getEdit') !!}">Danh sách liên hệ</a></li>
        <li class="active">Sửa thông tin Danh sách liên hệ</li>
    </ol>
</section>
<section class="content">
    <div class="box box-primary col-xs-12 col-sm-12 col-md-12 no-padding">

        {!! Form::model($customer_contacts, ['route' => ['admin.customer-contacts.postEdit',$customer_contacts['id']], 'method' => 'POST', 'id' => 'customer-contacts-edit-form', 'class' => 'form-horizontal']) !!}
        <div class="box-body">
            @if (Session::has('customer-contacts-edit-success-message'))
            <div class="alert alert-info alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Thành công!</h4>
                {{ Session::get('customer-contacts-edit-success-message') }}
            </div>
            @elseif (Session::has('customer-contacts-edit-error-message'))
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> Lỗi!</h4>
                {{ Session::get('customer-contacts-edit-error-message') }}
            </div>
            @endif
            <div class="form-group has-feedback {{ ($errors->has('company_name') ? 'has-error' : '') }}">
                <div class="col-sm-2 control-label">
                    {!! Form::label('company_name', 'Tên công ty', ['class' => '']) !!} <span class="required">*</span>
                </div>
                <div class="col-sm-10">
                    {!! Form::text('company_name', Input::old('company_name'), ['placeholder' => 'Tên công ty', 'name' => 'company_name', 'class' => 'form-control']) !!}
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
                    {!! Form::text('full_name',  Input::old('full_name'), ['placeholder'=>'Họ tên' , 'name' => 'full_name', 'class' => 'form-control']) !!}
                    <span class="fa fa-th form-control-feedback"></span>
                    @if ($errors->has('full_name'))
                    <div class="error-messages">{{ $errors->first('full_name') }}</div>
                    @endif
                </div>
            </div>
            <div class="box-footer">
                {!! Form::submit('Lưu', ['class' => 'btn btn-info pull-right']) !!}

                @if (isset($myPermission['admin.customer-contacts.getList']))
                <a href="{!! route('admin.customer-contacts.getList') !!}" class="btn btn-default">Về danh sách</a>
                @endif
            </div><!-- /.box-footer -->
        </div>
        {!! Form::close() !!}
    </div>
</section>
@stop