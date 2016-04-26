@extends('layouts.main')

@section('title', 'Truy cập bị từ chối')

@section('content')
@parent

<!-- Main content -->
<section class="content">

    <div class="error-page">
        <h2 class="headline text-red">550</h2>
        <div class="error-content">
            <h3><i class="fa fa-warning text-red"></i> Truy cập bị từ chối.</h3>
            <p>
                Bạn không có quyền truy cập tính năng này.
            </p>
            <p>
                <a href="{!! route('admin.index') !!}">Trở về Dashboard</a>
            </p>
            <p>
                Hoặc liên hệ với ban quản trị để xin cấp quyền tương ứng.
            </p>
        </div>
    </div><!-- /.error-page -->

</section>

<!-- /.content -->
@stop
