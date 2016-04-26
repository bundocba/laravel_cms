<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Trang quản trị - Đăng nhập</title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport" />
        <link rel="stylesheet" href="{{ asset('public/inside/assets/bootstrap/css/bootstrap.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('public/inside/assets/font-awesome/css/font-awesome.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('public/inside/assets/ionicons/css/ionicons.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('public/inside/assets/dist/css/AdminLTE.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('public/inside/assets/iCheck/square/blue.css') }}" />
        <link rel="stylesheet" href="{{ asset('public/inside/css/styles.css') }}" />

        <link rel="shortcut icon" href="{{ asset('public/inside/img/favicon.png') }}" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="hold-transition login-page">
        <div class="login-box ">
            <div class="login-logo">
                TRANG <b>QUẢN TRỊ</b>
            </div><!-- /.login-logo -->
            <div class="login-box-body">

                <div class="row">
                    <div class="form-box">
                        @if (Session::has('change-password-request-success-message'))
                        <div class="alert alert-info alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4><i class="icon fa fa-check"></i> Thành công!</h4>
                            {{ Session::get('change-password-request-success-message') }}
                        </div>
                        @elseif (Session::has('change-password-request-error-message'))
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4><i class="icon fa fa-ban"></i> Lỗi!</h4>
                            {{ Session::get('change-password-request-error-message') }}
                        </div>
                        @endif
                        <div class="form-top">
                            <h2 class="login-box-msg">Khôi phục mật khẩu</h2>
                        </div>
                        <div class="form-bottom">
                            {!! Form::open([
                            'url' => 'admin/password/email', 
                            'method' => 'POST', 'id' => 'login-form',
                            ]) !!}
                            <div class="form-group has-feedback">
                                {!! Form::email('email', Input::old('email'), ['placeholder' => 'Email', 'name' => 'email', 'class' => 'form-control']) !!}
                                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                                @if ($errors->has('email'))
                                <div class="error-messages">{{ $errors->first('email') }}</div>
                                @endif
                            </div>
                            <div class="form-group has-feedback">
                                {!! captcha_img() !!}
                                <input type="text" name="captcha">
                                @if ($errors->has('captcha'))
                                <div class="error-messages">{{ $errors->first('captcha') }}</div>
                                @endif
                            </div>
                            {!! Form::submit('Gửi yêu cầu khôi phục mật khẩu', ['class' => 'btn btn-primary btn-block btn-flat']) !!}
                            <div class="form-group" style="margin-top:5px;">
                                <p>Nếu bạn đã có tài khoản vui lòng <a href="{{ URL('admin/login') }}">Đăng nhập</a></p>
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
