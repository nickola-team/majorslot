@extends('backend.argon.layouts.app',[
        'parentSection' => 'dashboard',
        'elementName' => 'dashboard'
    ])
@section('page-title',  '대시보드' )
@section('content-header')
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card ">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <div class="row">
                            <div class="col-6">
                                <h5 class="card-title text-uppercase text-muted mb-0 text-white">오늘 배팅금</h5>
                                <span class="h2 font-weight-bold mb-0 text-white">{{number_format($todaysummary?$todaysummary->totalbet:0)}}</span>
                                <h5 class="card-title text-uppercase text-muted mb-0 text-white">오늘 당첨금</h5>
                                <span class="h2 font-weight-bold mb-0 text-white">{{number_format($todaysummary?$todaysummary->totalwin:0)}}</span>
                            </div>
                            <div class="col-6" style="display: flex;align-items: flex-end;flex-direction: column;/* align-content: flex-start; */justify-content: center;">
                                <h5 class="card-title text-uppercase text-muted mb-0 text-white">오늘 벳윈수익</h5>
                                <span class="h2 font-weight-bold mb-0 text-white">{{number_format($stats['todaybetwin'])}}</span>
                            </div>
                        </div>
                        <div class="progress progress-xs mt-3 mb-0">
                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                        </div>
                    </div>
                </div>
                <p class="mt-3 mb-0 text-sm">
                    <a href="{{argon_route('argon.player.gamehistory')}}" class="text-nowrap text-white font-weight-600">게임내역보기</a>
                </p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <!-- Card body -->
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <div class="row">
                            <div class="col-6">
                                <h5 class="card-title text-uppercase text-muted mb-0 text-white">오늘 충전금</h5>
                                <span class="h2 font-weight-bold mb-0 text-white">{{number_format($stats['todayin'])}}</span>
                                <h5 class="card-title text-uppercase text-muted mb-0 text-white">오늘 환전금</h5>
                                <span class="h2 font-weight-bold mb-0 text-white">{{number_format($stats['todayout'])}}</span>
                            </div>
                            <div class="col-6" style="display: flex;align-items: flex-end;flex-direction: column;/* align-content: flex-start; */justify-content: center;">
                                <h5 class="card-title text-uppercase text-muted mb-0 text-white">오늘 충환수익</h5>
                                <span class="h2 font-weight-bold mb-0 text-white">{{number_format($stats['todaydw'])}}</span>
                            </div>
                        </div>
                        
                        <div class="progress progress-xs mt-3 mb-0">
                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                        </div>
                    </div>
                </div>
                <p class="mt-3 mb-0 text-sm">
                    <a href="{{argon_route('argon.agent.transaction')}}" class="text-nowrap text-white font-weight-600">충환내역보기</a>
                </p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card ">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <div class="row">
                            <div class="col-6">
                                <h5 class="card-title text-uppercase text-muted mb-0 text-white">이달 배팅금</h5>
                                <span class="h2 font-weight-bold mb-0 text-white">{{number_format($stats['monthbet'])}}</span>
                                <h5 class="card-title text-uppercase text-muted mb-0 text-white">이달 당첨금</h5>
                                <span class="h2 font-weight-bold mb-0 text-white">{{number_format($stats['monthwin'])}}</span>
                            </div>
                            <div class="col-6" style="display: flex;align-items: flex-end;flex-direction: column;/* align-content: flex-start; */justify-content: center;">
                                <h5 class="card-title text-uppercase text-muted mb-0 text-white">이달 벳윈수익</h5>
                                <span class="h2 font-weight-bold mb-0 text-white">{{number_format($stats['monthbet'] - $stats['monthwin'])}}</span>
                            </div>
                        </div>
                        
                        <div class="progress progress-xs mt-3 mb-0">
                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                        </div>
                    </div>
                </div>
                <p class="mt-3 mb-0 text-sm">
                    <a href="{{argon_route('argon.report.daily')}}" class="text-nowrap text-white font-weight-600">보고서보기</a>
                    <!-- <a class="text-nowrap text-white font-weight-600">게임RTP&nbsp;&nbsp;&nbsp;{{number_format($stats['monthrtp'],2)}}%</a> -->
                </p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <!-- Card body -->
            <div class="card-body">
                <div class="row">
                    <div class="col">
                    <div class="row">
                        <div class="col-6">
                                <h5 class="card-title text-uppercase text-muted mb-0 text-white">이달 충전금</h5>
                                <span class="h2 font-weight-bold mb-0 text-white">{{number_format($stats['monthin'])}}</span>
                                <h5 class="card-title text-uppercase text-muted mb-0 text-white">이달 환전금</h5>
                                <span class="h2 font-weight-bold mb-0 text-white">{{number_format($stats['monthout'])}}</span>
                            </div>
                            <div class="col-6" style="display: flex;align-items: flex-end;flex-direction: column;/* align-content: flex-start; */justify-content: center;">
                                <h5 class="card-title text-uppercase text-muted mb-0 text-white">이달 충환수익</h5>
                                <span class="h2 font-weight-bold mb-0 text-white">{{number_format($stats['monthin'] - $stats['monthout'])}}</span>
                            </div>
                        </div>
                        
                        <div class="progress progress-xs mt-3 mb-0">
                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                        </div>
                    </div>
                </div>
                <p class="mt-3 mb-0 text-sm">
                <a class="text-nowrap text-white font-weight-600">페이아웃&nbsp;&nbsp;&nbsp;{{number_format($stats['monthpayout'],2)}}%</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h2 class="mb-0">벳윈상황</h2>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Chart -->
                    <div class="chart-sm">
                        <!-- Chart wrapper -->
                        <canvas id="chart-betwin"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h2 class="mb-0">게임사별베팅금</h2>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Chart -->
                    <div class="chart-sm">
                        <!-- Chart wrapper -->
                        <canvas id="chart-category"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card   shadow">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h2 class=" mb-0">충환전상황</h2>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Chart -->
                    <div class="chart-sm">
                        <canvas id="chart-dw"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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

    $cat_labels = [];
    $cat_bet = [];

    foreach($monthcategory AS $cat){
        $label = $cat->title;
        $cat_labels[] = $label;
        $cat_bet[] = $cat->totalbet;
    }

    
@endphp

@push('js')
    <script src="/back/js/Chart.min.js"></script>
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
                    backgroundColor: color(window.chartColors.purple).alpha(0.5).rgbString(),
                    borderColor: window.chartColors.purple,
                    fill: false,
                    data: [@foreach($bets AS $bet) {{ $bet }}, @endforeach],
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
                            display: true,
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
                        gridLines: {
                            display:true,
                            color : 'rgba(0,0,0,1)',
                            lineWidth : 1,
                        },
                        ticks: {
                            beginAtZero: true,
                            callback: function(value, index) {
                                if(value.toString().length > 9 && value != 0) return (Math.floor(value / 100000000)).toLocaleString("ko-KR") + "억";
                                else if(value.toString().length == 9 && value != 0) return (value / 100000000).toFixed(1) + "억";
                                else if(value.toString().length > 6 && value != 0) return (Math.floor(value / 10000)).toLocaleString("ko-KR") + "만";
                                else if(value.toString().length == 6 && value != 0) return (value / 10000).toFixed(1) + "만";
                                else return value.toLocaleString("ko-KR");
                            }
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
                    backgroundColor: color(window.chartColors.red).rgbString(),
                    borderColor: window.chartColors.red,
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
                        gridLines: {
                            display:true,
                            color : 'rgba(0,0,0,1)',
                            lineWidth : 1,
                        },
                        ticks: {
                            beginAtZero: true,
                            callback: function(value, index) {
                                if(value.toString().length > 9 && value != 0) return (Math.floor(value / 100000000)).toLocaleString("ko-KR") + "억";
                                else if(value.toString().length == 9 && value != 0) return (value / 100000000).toFixed(1) + "억";
                                else if(value.toString().length > 6 && value != 0) return (Math.floor(value / 10000)).toLocaleString("ko-KR") + "만";
                                else if(value.toString().length == 6 && value != 0) return (value / 10000).toFixed(1) + "만";
                                else return value.toLocaleString("ko-KR");
                            }
                        }
                    }]
                }
            }
        };

        var dataset = {
            label: "게임사별 베팅",
            backgroundColor: [
                color(window.chartColors.green).alpha(0.5).rgbString(), 
                color(window.chartColors.red).alpha(0.5).rgbString(), 
                color(window.chartColors.yellow).alpha(0.5).rgbString(), 
                color(window.chartColors.blue).alpha(0.5).rgbString(), 
                color(window.chartColors.grey).alpha(0.5).rgbString()],//라벨별 컬러설정
            borderColor: '#119911',
            data: [{{implode(',', $cat_bet)}}]
        }

        var labels=["{!! implode('","', $cat_labels) !!}"]; 
        
        var datasets={ datasets:[dataset], labels:labels }

        var configpie = {
            type: 'pie',
            data: datasets, //데이터 셋 
            options: {
                responsive: true,
                maintainAspectRatio: false, //true 하게 되면 캔버스 width,height에 따라 리사이징된다.
                legend: {
                    position: 'top',
                    fontColor: 'black',
                    align: 'center',
                    display: true,
                    fullWidth: true,
                    labels: {
                        fontColor: 'rgb(0, 0, 0)'
                    }
                },
                plugins: {
                    labels: {//두번째 script태그를 설정하면 각 항목에다가 원하는 데이터 라벨링을 할 수 있다.
                        render: 'value',
                        fontColor: 'black',
                        fontSize: 15,
                        precision: 2
                    }

                }
            }
        }

        window.onload = function() {
            var ctx = document.getElementById('chart-betwin').getContext('2d');
            window.myLine = new Chart(ctx, config);
            var ctx1 = document.getElementById('chart-dw').getContext('2d');
            window.myLine1 = new Chart(ctx1, configbar);

            var ctx2 = document.getElementById('chart-category').getContext('2d');
            window.myLine2 = new Chart(ctx2, configpie);
        };

    </script>
@endpush