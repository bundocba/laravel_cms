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
        <div class="login-box">
            <div class="login-logo">
                TRANG <b>QUẢN TRỊ</b>
            </div><!-- /.login-logo -->
            <div class="login-box-body">
                <h2 class="login-box-msg">Đăng nhập</h2>
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

                {!! Form::open(['url' => 'admin/login', 'method' => 'POST', 'id' => 'login-form']) !!}

                <!-- if there are login errors, show them here -->
                @if (Session::get('loginError'))
                <div class="alert alert-danger">{{ Session::get('loginError') }}</div>
                @endif

                @if (count($errors) > 0)
                <ul>
                    @if ($errors->has('email'))
                    <li class="error-messages">{{ $errors->first('email') }}</li>
                    @endif

                    @if ($errors->has('password'))
                    <li class="error-messages">{{ $errors->first('password') }}</li>
                    @endif
                </ul>
                @endif

                <div class="form-group has-feedback">
                    {!! Form::email('email', Input::old('email'), ['placeholder' => 'Email', 'name' => 'email', 'class' => 'form-control']) !!}
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>

                <div class="form-group has-feedback">
                    {!! Form::password('password', ['placeholder' => 'Mật khẩu', 'name' => 'password', 'class' => 'form-control']) !!}
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>

                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox icheck">
                            <label>
                                {!! Form::checkbox('remember_me', '') !!} Nhớ tài khoản
                            </label>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        {!! Form::submit('Đăng nhập', ['class' => 'btn btn-primary btn-block btn-flat']) !!}
                    </div>
                </div>
                {!! Form::close() !!}

                <!--quên mật khẩu-->
                <a href="{{ URL('admin/password/email') }}" class="pull-center forgot"><?php echo 'Quên Mật Khẩu'; ?></a>
            </div><!-- /.login-box-body -->
        </div><!-- /.login-box -->


        <script src="{{ asset('public/inside/assets/jQuery/jQuery-2.1.4.min.js') }}"></script>
        <script src="{{ asset('public/inside/assets/bootstrap/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('public/inside/assets/iCheck/icheck.min.js') }}"></script>

        <script>
$(function () {
    $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
    });
});
        </script>
    </body>
</html>
