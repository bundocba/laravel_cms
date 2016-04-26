<!DOCTYPE html>
<html lang="en">

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
                    <div class="form-top">
                        <h2 class="login-box-msg"> Đổi mật khẩu thành viên  </h2>
                    </div>
                    <div class="form-bottom">
                        {!! Form::open([
                        'url' => 'admin/password/reset', 
                        'method' => 'POST', 'id' => 'login-form',
                        ]) !!}
                        <input type="hidden" name="token_password" id="token_password" value="{{ $token_password }}">
                        <div class="form-group">
                            {!! Form::password('password', ['placeholder' => 'Mật khẩu mới', 'name' => 'password', 'class' => 'form-control']) !!}
                            @if ($errors->has('password'))
                            <div class="error-messages">{{ $errors->first('password') }}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            {!! Form::password('password_confirmation', ['placeholder' => 'Nhập lại mật khẩu mới', 'name' => 'password_confirmation', 'class' => 'form-control']) !!}
                            @if ($errors->has('password_confirmation'))
                            <div class="error-messages">{{ $errors->first('password_confirmation') }}</div>
                            @endif
                        </div>
                        {!! Form::submit('Đổi mật khẩu', ['class' => 'btn btn-primary btn-block btn-flat']) !!}
                        <div class="form-group" style="margin-top:5px;">
                            <p>Nếu bạn đã có tài khoản vui lòng <a href="{{ URL('admin/login') }}">Đăng nhập</a></p>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </body>

</html>
