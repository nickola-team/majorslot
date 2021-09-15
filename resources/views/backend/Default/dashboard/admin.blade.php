@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', trans('app.home'))
@section('page-heading', trans('app.home'))

@section('content')

    <section class="content-header">
        @include('backend.Default.partials.messages')
    </section>

    <section class="content">

        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-light-blue">
                    <div class="inner">
                        <h3>{{ number_format($stats['total']) }}</h3>
                        <p>전체사용자</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box  bg-light-blue">
                    <div class="inner">
                        <h3>{{ number_format($stats['online']) }}</h3>
                        <p>온라인 사용자</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-user-plus"></i>
                    </div>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box  bg-light-blue">
                    <div class="inner">
                        <h3>{{ number_format($stats['todaybetwin'], 0) }}</h3>
                        <p>오늘 배팅수익</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-ban"></i>
                    </div>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-light-blue">
                    <div class="inner">
                        <h3>{{ number_format($stats['todayprofit'],0) }}</h3>
                        <p>오늘 충환수익</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-desktop"></i>
                    </div>
                </div>
            </div>
            <!-- ./col -->
        </div>
        <!-- /.row -->

        <script src="/back/js/Chart.min.js"></script>
        <div class="row">
            <div class="col-md-6">
                <div class="box box-success">
                    <div class="box-header with-border">
                    <i class="fa fa-th"></i>

                    <h3 class="box-title">일별배팅현황</h3>

                    </div>
                    <div class="box-body border-radius-none">
                        <div class="chart">
                        <canvas id="betwincanvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-warning">
                    <div class="box-header with-border">
                    <i class="fa fa-th"></i>

                    <h3 class="box-title">일별충환전현황</h3>

                    </div>
                    <div class="box-body border-radius-none">
                        <div class="chart">
                        <canvas id="inoutcanvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-xs-6">
                <div class="box box-success">

                    <div class="box-header with-border">
                        <h3 class="box-title">@lang('app.latest_pay_stats')</h3>
                    </div>

                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>@lang('app.system')</th>
                                    <th>충/환전금액</th>
                                    <th>회원/파트너</th>
                                    <th>@lang('app.date')</th>
                                </tr>
                                </thead>

                                <tbody>

                                @if (count($statistics))
                                    @foreach ($statistics as $stat)
                                        <tr>
                                            <td>
                                                <a href="{{ route($admurl.'.statistics', ['system_str' => $stat->admin ? $stat->admin->username : $stat->system])  }}">
                                                    {{ $stat->admin ? $stat->admin->username : $stat->system }}
                                                </a>
                                                @if( $stat->value ) {{ $stat->value }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($stat->type == 'add')
                                                    <span class="text-green">{{ number_format(abs($stat->summ),0) }}</span>
                                                @else
                                                    <span class="text-red">{{ number_format(abs($stat->summ),0) }}</span>
                                                @endif
                                            </td>
                                            </td>
                                            <td>
                                                <a href="{{ route($admurl.'.statistics', ['user' => $stat->user ? $stat->user->username : ''])  }}">
                                                    {{ $stat->user ? $stat->user->username : '' }}
                                                </a>
                                            </td>
                                            <td>{{ $stat->created_at->format(config('app.date_time_format')) }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr><td colspan="4">@lang('app.no_data')</td></tr>
                                @endif

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-6">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">@lang('app.latest_game_stats')</h3>
                    </div>

                    <div class="box-body">
                        <div class="table-responsive">

                            <table class="table table-striped">

                                <thead>
                                <tr>
                                    <th>@lang('app.game')</th>
                                    <th>@lang('app.user')</th>
                                    <th>@lang('app.balance')</th>
                                    <th>@lang('app.bet')</th>
                                    <th>@lang('app.win')</th>
                                    <th>@lang('app.date')</th>
                                </tr>
                                </thead>

                                <tbody>

                                @if (count($gamestat))
                                    @foreach ($gamestat as $stat)
                                        <tr>
                                            <td>
                                                <a href="{{ route($admurl.'.game_stat', ['game' => $stat->game])  }}">
                                                    {{ $stat->game }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route($admurl.'.game_stat', ['user' => $stat->user ? $stat->user->username : ''])  }}">
                                                    {{ $stat->user ? $stat->user->username : '' }}
                                                </a>
                                            </td>
                                            <td>{{ number_format($stat->balance,0) }}</td>
                                            <td>{{ number_format($stat->bet) }}</td>
                                            <td>{{ number_format($stat->win) }}</td>
                                            <td>{{ date(config('app.date_time_format'), strtotime($stat->date_time)) }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr><td colspan="6">@lang('app.no_data')</td></tr>
                                @endif

                                </tbody>

                            </table>

                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- /Latest Pay Stats / Latest Game Stats -->

        <!-- Latest Shops / Latest Registrations -->
        @if (auth()->user()->hasRole('admin'))
        <div class="row">
            @permission('shops.manage')
            <div class="col-xs-6">
                <div class="box box-success">

                    <div class="box-header with-border">
                        <h3 class="box-title">@lang('app.latest_shops')</h3>
                    </div>

                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>@lang('app.name')</th>
                                    <th>@lang('app.id')</th>
                                    <th>@lang('app.credit')</th>
                                {{--    <th >@lang('app.frontend')</th>
                                    <th>@lang('app.currency')</th>--}}
                                    <th>@lang('app.status')</th>
                                </tr>
                                </thead>

                                <tbody>

                                @if (count($shops))
                                    @foreach ($shops as $shop)
                                        <tr>
                                            <td>
                                                <a href="{{ route($admurl.'.shop.edit', $shop->id)  }}">
                                                    {{ $shop->name }}
                                                </a>
                                            </td>
                                            <td>{{ $shop->id }}</td>
                                            <td>{{ number_format($shop->balance,0) }}</td>
                                            {{--
                                            <td>{{ $shop->frontend }}</td>

                                            <td>{{ $shop->currency }}</td>
                                            --}}
                                            <td>
                                                @if($shop->is_blocked)
                                                    <small><i class="fa fa-circle text-red"></i></small>
                                                @elseif ($shop->pending)
                                                    <small><i class="fa fa-circle text-yellow"></i></small>
                                                @else
                                                    <small><i class="fa fa-circle text-green"></i></small>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr><td colspan="7">@lang('app.no_data')</td></tr>
                                @endif

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endpermission

            @permission('users.manage')
            <div class="col-xs-6">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">@lang('app.latest_registrations')</h3>
                    </div>

                    <div class="box-body">
                        <div class="table-responsive">

                            <table class="table table-striped">

                                <thead>
                                <tr>
                                    <th>@lang('app.username')</th>
                                    @permission('users.balance.manage')
                                    <th>@lang('app.balance')</th>
                                    @endpermission
                                    <th>시간</th>
                                </tr>
                                </thead>

                                <tbody>

                                @if (count($latestRegistrations))
                                    @foreach ($latestRegistrations as $user)
                                        <tr>
                                            <td>
                                                <a href="{{ route($admurl.'.user.edit', $user->id) }}">
                                                    {{ $user->username ?: trans('app.n_a') }}
                                                </a>
                                            </td>
                                            @permission('users.balance.manage')
                                            <td>{{ number_format($user->balance,0) }}</td>
                                            @endpermission
                                            <td>{{ date(config('app.date_time_format'), strtotime($user->created_at)) }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr><td colspan="4">@lang('app.no_data')</td></tr>
                                @endif

                                </tbody>

                            </table>

                        </div>
                    </div>

                </div>
            </div>
            @endpermission

        </div>

        <!-- /Latest Shops / Latest Registrations -->


        <div class="row">

            @permission('stats.bank')
            <div class="col-xs-6">
                <div class="box box-success">

                    <div class="box-header with-border">
                        <h3 class="box-title">@lang('app.latest_bank_stat')</h3>
                    </div>

                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>@lang('app.name')</th>
                                {{--    <th>@lang('app.user')</th> --}}
                                    <th>@lang('app.old')</th>
                                    <th>@lang('app.new')</th>
                                    <th>충/환전금액</th>
                                    <th>@lang('app.date')</th>
                                </tr>
                                </thead>

                                <tbody>

                                @if (count($bank_stat))
                                    @foreach ($bank_stat as $stat)
                                        <tr>
                                            <td>{{ $stat->name }}</td>
                                            {{--<td>{{ $stat->user ? $stat->user->username : '' }}</td> --}}
                                            <td>{{ number_format($stat->old,0) }}</td>
                                            <td>{{ number_format($stat->new,0) }}</td>
                                            <td>
                                                @if ($stat->type == 'add')
                                                    <span class="text-green">{{ number_format(abs($stat->sum),0) }}</span>
                                                @else
                                                    <span class="text-red">{{ number_format(abs($stat->sum),0) }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $stat->created_at->format(config('app.date_time_format')) }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr><td colspan="6">@lang('app.no_data')</td></tr>
                                @endif

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endpermission

            @permission('stats.shop')
            <div class="col-xs-6">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">@lang('app.latest_shop_stats')</h3>
                    </div>

                    <div class="box-body">
                        <div class="table-responsive">

                            <table class="table table-striped">

                                <thead>
                                <tr>
                                    <th>@lang('app.name')</th>
                                    <th>파트너이름</th>
                                    <th>충/환전액</th>
                                    <th>@lang('app.date')</th>
                                </tr>
                                </thead>

                                <tbody>

                                @if (count($shops_stat))
                                    @foreach ($shops_stat as $stat)
                                        <tr>
                                            <td>{{ $stat->shop? $stat->shop->name: '' }}</td>
                                            <td>{{ $stat->user ? $stat->user->username : '' }}</td>
                                            <td>
                                                @if ($stat->type == 'add')
                                                    <span class="text-green">{{ number_format(abs($stat->sum),0) }}	</span>
                                                @else
                                                    <span class="text-red">{{ number_format(abs($stat->sum),0) }}</span>
                                                @endif

                                            </td>
                                            <td>{{ date(config('app.date_time_format'), strtotime($stat->date_time)) }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr><td colspan="4">@lang('app.no_data')</td></tr>
                                @endif

                                </tbody>

                            </table>

                        </div>
                    </div>

                </div>
            </div>
            @endpermission

        </div>
        @endif
    </section>
    <!-- /.content -->

@stop
@php

    $date_start = date("m-d", strtotime('-30 days'));
    $date_end = date("m-d");
    $labels = [];
    $wins = [];
    $bets = [];
    $totalin = [];
    $totalout = [];

    for($i=1; $i<=30; $i++){
        $label = date("m-d", strtotime(-30 + $i . ' days'));
        $labels[] = $label;
        $wins[$label] = 0;
        $bets[$label] = 0;
        $totalin[$label] = 0;
        $totalout[$label] = 0;
    }

    foreach($monthsummary AS $stat){
        $label = date("m-d", strtotime($stat->date));
        if( isset($wins[$label]) ){
            $wins[$label] += $stat->totalwin;
            $totalin[$label] += $stat->totalin;
        }
        if( isset($bets[$label]) ){
            $bets[$label] += $stat->totalbet;
            $totalout[$label] += $stat->totalout;
        }
    }
@endphp
@section('scripts')
    <script>
        
        window.chartColors = {
            red: 'rgb(255, 99, 132)',
            orange: 'rgb(255, 159, 64)',
            yellow: 'rgb(255, 205, 86)',
            green: 'rgb(75, 203, 75)',
            blue: 'rgb(54, 162, 235)',
            purple: 'rgb(153, 102, 255)',
            grey: 'rgb(201, 203, 207)'
        };

        Chart.scaleService.updateScaleDefaults('linear', {
            ticks: {
                min: 0
            }
        });

        var color = Chart.helpers.color;
        var config = {
            type: 'line',
            data: {
                labels: ["{!! implode('","', $labels) !!}"],
                datasets: [{
                    label: '당첨',
                    backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
                    borderColor: window.chartColors.blue,
                    fill: false,
                    data: [@foreach($wins AS $win) {{ $win }}, @endforeach],
                }, {
                    label: '배팅',
                    backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
                    borderColor: window.chartColors.red,
                    fill: false,
                    data: [@foreach($bets AS $bet) {{ $bet }}, @endforeach],
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: false,
                    text: '@lang('app.line_chart')'
                },
                scales: {
                    xAxes: [{
                        type: 'category',
                        display: false,
                        scaleLabel: {
                            display: false,
                            labelString: '날짜'
                        },
                        ticks: {
                            major: {
                                fontStyle: 'bold',
                                fontColor: '#FF0000'
                            }
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: '배팅/당첨'
                        }
                    }]
                }
            }
        };

        var configbar = {
            type: 'bar',
            data: {
                labels: ["{!! implode('","', $labels) !!}"],
                datasets: [{
                    label: '충전',
                    backgroundColor: color(window.chartColors.green).rgbString(),
                    borderColor: window.chartColors.green,
                    fill: false,
                    data: [@foreach($totalin AS $in) {{ $in }}, @endforeach],
                }, {
                    label: '환전',
                    backgroundColor: color(window.chartColors.purple).rgbString(),
                    borderColor: window.chartColors.purple,
                    fill: false,
                    data: [@foreach($totalout AS $out) {{ $out }}, @endforeach],
                }]
            },
            options: {
                scaleShowGridLines      : true,
                scaleGridLineColor      : 'rgba(0,0,0,.05)',
                scaleGridLineWidth      : 1,
                //Boolean - Whether to show horizontal lines (except X axis)
                scaleShowHorizontalLines: true,
                //Boolean - Whether to show vertical lines (except Y axis)
                scaleShowVerticalLines  : true,
                //Boolean - If there is a stroke on each bar
                barShowStroke           : true,
                //Number - Pixel width of the bar stroke
                barStrokeWidth          : 2,
                responsive: true,
                title: {
                    display: false,
                    text: '@lang('app.line_chart')'
                },
                scales: {
                    xAxes: [{
                        type: 'category',
                        display: false,
                        scaleLabel: {
                            display: false,
                            labelString: '날짜'
                        },
                        ticks: {
                            major: {
                                fontStyle: 'bold',
                                fontColor: '#FF0000'
                            }
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: '충/환전'
                        }
                    }]
                }
            }
        };

        window.onload = function() {
            var ctx = document.getElementById('betwincanvas').getContext('2d');
            window.myLine = new Chart(ctx, config);
            var ctx1 = document.getElementById('inoutcanvas').getContext('2d');
            window.myLine1 = new Chart(ctx1, configbar);
        };

    </script>
    {!! HTML::script('/back/dist/js/pages/dashboard.js') !!}
@stop