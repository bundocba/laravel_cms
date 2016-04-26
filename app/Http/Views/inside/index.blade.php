@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
@parent
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Dashboard
        <small>RAD CMS v.3.0</small>
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <!-- Info boxes -->
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-th"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Tổng thể loại</span>
                    <span class="info-box-number">{{ $totalPostCategories }}</span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div><!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="ion ion-edit"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Tổng bài viết</span>
                    <span class="info-box-number">{{ $totalPosts }}</span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div><!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="ion ion-ios-people-outline"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Tổng nhóm quyền</span>
                    <span class="info-box-number">{{ $totalUserGroups }}</span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div><!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="ion ion-ios-person-outline"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Tổng thành viên</span>
                    <span class="info-box-number">{{ $totalUsers }}</span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div><!-- /.col -->
    </div><!-- /.row -->

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Biểu đồ thống kê bài viết</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
<!--                        <div class="btn-group">
                            <button class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown"><i class="fa fa-wrench"></i></button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#">Action</a></li>
                                <li><a href="#">Another action</a></li>
                                <li><a href="#">Something else here</a></li>
                                <li class="divider"></li>
                                <li><a href="#">Separated link</a></li>
                            </ul>
                        </div>-->
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p class="text-center">
                                <strong>Thống kê {{ $curYear - 1 }} - {{ $curYear }}</strong>
                            </p>
                            <div class="chart">
                                <div id="highcharts-month" style="height: 400px; margin: 0 auto;"></div>
                            </div>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- ./box-body -->
                <div class="box-footer">
<!--                    <div class="row">
                        <div class="col-sm-3 col-xs-6">
                            <div class="description-block border-right">
                                <span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 17%</span>
                                <h5 class="description-header">$35,210.43</h5>
                                <span class="description-text">TOTAL REVENUE</span>
                            </div> /.description-block 
                        </div> /.col 
                        <div class="col-sm-3 col-xs-6">
                            <div class="description-block border-right">
                                <span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> 0%</span>
                                <h5 class="description-header">$10,390.90</h5>
                                <span class="description-text">TOTAL COST</span>
                            </div> /.description-block 
                        </div> /.col 
                        <div class="col-sm-3 col-xs-6">
                            <div class="description-block border-right">
                                <span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 20%</span>
                                <h5 class="description-header">$24,813.53</h5>
                                <span class="description-text">TOTAL PROFIT</span>
                            </div> /.description-block 
                        </div> /.col 
                        <div class="col-sm-3 col-xs-6">
                            <div class="description-block">
                                <span class="description-percentage text-red"><i class="fa fa-caret-down"></i> 18%</span>
                                <h5 class="description-header">1200</h5>
                                <span class="description-text">GOAL COMPLETIONS</span>
                            </div> /.description-block 
                        </div>
                    </div> /.row -->
                </div><!-- /.box-footer -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->

    <!-- Main row -->
    <div class="row">
        <div class="col-md-8">
            <!-- USERS LIST -->
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">Thành viên mới</h3>
                    <div class="box-tools pull-right">
                        <span class="label label-danger">{{ count($listNewestUsers) }} thành viên mới</span>
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                    <ul class="users-list clearfix">
                        @foreach ($listNewestUsers as $index => $user)
                        <li>
                            <?php
                            $img = $user['image_name'];
                            $path = asset("public/inside/img/upload/users/$img");
                            $header_response = get_headers($path, 1);

                            if (strpos($header_response[0], "200") == false || empty($img)) {
                                $path = asset('public/inside/img/system/noname.png');
                            }
                            ?>
                            <img src="{{ $path }}" alt="Hình đại diện" width="128" height="128" />
                            <a href="javascript::;" class="users-list-name">{{ $user['first_name'] . ' ' . $user['last_name'] }}</a>
                            <span class="users-list-date">{{ date('Y-m-d', strtotime($user['created_at'])) }}</span>
                        </li>
                        @endforeach
                    </ul><!-- /.users-list -->
                </div><!-- /.box-body -->
                <div class="box-footer text-center">
                    <a href="{!! route('admin.users.getList') !!}" class="uppercase">Xem danh sách thành viên</a>
                </div><!-- /.box-footer -->
            </div><!--/.box -->
        </div>

        <div class="col-md-4">
            <!-- POST LIST -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Bài viết gần đây</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <ul class="products-list product-list-in-box">
                        @foreach ($listNewestPosts as $index => $post)
                        <li class="item">
                            <div class="product-img">
                                <?php
                                $img = $post['image_name'];
                                $path = asset("public/inside/img/upload/posts/$img");
                                $header_response = get_headers($path, 1);

                                if (strpos($header_response[0], "200") == false || empty($img)) {
                                    $path = asset('public/inside/img/system/no-image.jpg');
                                }
                                ?>
                                <img src="{{ $path }}" alt="Hình đại diện">
                            </div>
                            <div class="product-info">
                                <a href="javascript::;" class="product-title">{!! str_limit(strip_tags($post['name']), 50) !!} <span class="label label-info pull-right">{{ date('Y-m-d', strtotime($post['created_at'])) }}</span></a>
                                <span class="product-description">
                                    {!! str_limit(strip_tags($post['short_description']), 100) !!}
                                </span>
                            </div>
                        </li><!-- /.item -->
                        @endforeach
                    </ul>
                </div><!-- /.box-body -->
                <div class="box-footer text-center">
                    <a href="{!! route('admin.posts.getList') !!}" class="uppercase">Xem danh sách bài viết</a>
                </div><!-- /.box-footer -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->

<script src="{{ asset('public/inside/assets/dist/js/pages/dashboard2.js') }}"></script>

<script type="text/javascript" src="{{ asset('public/inside/assets/highcharts/highcharts.js') }}"></script>
<script type="text/javascript" src="{{ asset('public/inside/assets/highcharts/modules/exporting.js') }}"></script>
<script type="text/javascript" src="{{ asset('public/inside/assets/highcharts/themes/sand-signika.js') }}"></script>

<script>
    $(function () {
        // Plugin to add support for credits.target in Highcharts.
        Highcharts.wrap(Highcharts.Chart.prototype, 'showCredits', function (proceed, credits) {
            proceed.call(this, credits);

            if (credits.enabled && this.credits) {
                this.credits.element.onclick = function () {
                    var link = document.createElement('a');
                    link.href = credits.href;
                    link.target = credits.target;
                    link.click();
                }
            }
        });
        
        $('#highcharts-month').highcharts({
            chart: {
                type: 'spline'
            },
            title: {
                text: 'Bài viết'
            },
            subtitle: {
                text: 'Tổng 2 năm: {{ $summaryYear }} bài'
            },
            xAxis: {
                categories: [
                    'Tháng 1',
                    'Tháng 2',
                    'Tháng 3',
                    'Tháng 4',
                    'Tháng 5',
                    'Tháng 6',
                    'Tháng 7',
                    'Tháng 8',
                    'Tháng 9',
                    'Tháng 10',
                    'Tháng 11',
                    'Tháng 12'
                ]
            },
            yAxis: {
                title: {
                    text: 'Mức sử dụng'
                },
                labels: {
                    formatter: function () {
                        return this.value + ' bài';
                    }
                },
                min: 0,
                lineColor: '#FF0000',
                lineWidth: 1,
            },
            tooltip: {
                crosshairs: true,
                shared: true,
                //valueSuffix: ' VND',
            },
            credits: {
                enabled: false,
                text: 'CMS',
                href: '#',
                target: '_blank',
            },
            plotOptions: {
                spline: {
                    marker: {
                        radius: 4,
                        lineColor: '#666666',
                        lineWidth: 1
                    }
                }
            },
            series: [{
                    name: {{ $curYear }},
                    marker: {
                        symbol: 'circle'
                    },
                    data: {!! $summaryMonth[0] !!},
                }, {
                    name: {{ ($curYear - 1) }},
                    marker: {
                        symbol: 'diamond'
                    },
                    data: {!! $summaryMonth[1] !!},
                }]
        });

    });
</script>

@stop