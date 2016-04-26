<!DOCTYPE html>
<html>
    @include('layouts._head')
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            @include('layouts._header')

            <!-- Left side column. contains the logo and sidebar -->
            @include('layouts._sidebar')

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                @section('content')
                @show
            </div>
            <!-- /.content-wrapper -->

            @include('layouts._footer')

            <!-- Control Sidebar -->
            @include('layouts._controlbar')
            <!-- /.control-sidebar -->

            <!-- Add the sidebar's background. This div must be placed
                 immediately after the control sidebar -->
            <div class="control-sidebar-bg"></div>

        </div><!-- ./wrapper -->


        <script src="{{ asset('public/inside/assets/fastclick/fastclick.min.js') }}"></script>
        <script src="{{ asset('public/inside/assets/dist/js/app.min.js') }}"></script>
        <script src="{{ asset('public/inside/assets/sparkline/jquery.sparkline.min.js') }}"></script>
        <script src="{{ asset('public/inside/assets/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
        <script src="{{ asset('public/inside/assets/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
        <script src="{{ asset('public/inside/assets/slimScroll/jquery.slimscroll.min.js') }}"></script>
        <script src="{{ asset('public/inside/assets/chartjs/Chart.min.js') }}"></script>
        <script src="{{ asset('public/inside/assets/dist/js/demo.js') }}"></script>

        <link href="{{ asset('public/inside/css/sweetalert2.css') }}" rel="stylesheet">
        <script type="text/javascript" src="{{ asset('public/inside/js/sweetalert2.min.js') }}"></script>


    <script>
    $(function () {
        $('.tooltips').tooltip();
        var urlAction = window.location.href;
        $('ul.sidebar-menu li.sidebar-menuLi>a').click(function () {
            $('ul.sidebar-menu li.sidebar-menuLi').removeClass('active');
            var $else = $(this);
            var $elseRoot = $else.parent().children().find('ul.treeview-menu li');
            $elseRoot.each(function () {
                var $else_children = $(this);
                var elseHref = $else_children.find('a.hb-item').attr('href');
                if (elseHref == urlAction) {
                    $else_children.parent().addClass('menu-open').css('display', 'block');
                    $else_children.addClass('menu-open').css('display', 'block');
                    $else_children.addClass('active');
                    $else_children.parent().parent().addClass('active');
                }
            });
        });
    });
    function sweetID(id) {
        swal({
            title: 'Bạn có chắc chắn xoá ?',
            text: 'Bạn sẻ không thể khôi phục dữ liệu !',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Vâng , tôi muốn xoá nó!',
            closeOnConfirm: false
        },
        function (isConfirm) {
            if (isConfirm) {
                var url = '{!! url("/$prefix/deleted") !!}' + '/' + id;
                window.location.replace(url);
            }
        });
    }
    </script>
    </body>
</html>
