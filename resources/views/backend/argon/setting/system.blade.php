@extends('backend.argon.layouts.app',[
        'parentSection' => 'setting',
        'elementName' => 'statistics'
    ])
@section('page-title',  '시스템상황' )
@section('content-header')
<div class="row">
    <div class="col-xl-3 col-lg-3">
        <div class="card card-stats  mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col ">
                        <h3 class="card-title text-success mb-0 ">CPU사용률</h3>
                        <span class="h2 font-weight-bold mb-0 text-success">{{number_format($serverstat['cpu'],2)}}%</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3">
        <div class="card card-stats  mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col ">
                        <h3 class="card-title text-warning mb-0 ">RAM사용률</h3>
                        <span class="h2 font-weight-bold mb-0 text-warning">{{number_format($serverstat['ram'],2)}}%</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                            <i class="fas fa-chart-area"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3">
        <div class="card card-stats  mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col ">
                        <h3 class="card-title text-blue mb-0 ">DISK사용률</h3>
                        <span class="h2 font-weight-bold mb-0 text-blue">{{number_format($serverstat['disk'],2)}}%</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-blue text-white rounded-circle shadow">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-3">
        <div class="card card-stats  mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col ">
                        <h3 class="card-title text-dark mb-0 ">네트워크 접속수</h3>
                        <span class="h2 font-weight-bold mb-0 text-dark">{{$serverstat['connections']}}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-dark text-white rounded-circle shadow">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h2 class="mb-0">게임사 머니상황</h2>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                <div >
                    @foreach ($agents as $agkey => $agvalue)
                        <p><span class="description">🌡️ {{$agkey}} 보유금:</span>&nbsp;<span class="result">{{number_format(floatval($agvalue))}} 원</span></p>
                    @endforeach
                    <!-- <p><a href="{{argon_route('argon.system.xmxwithdraw')}}"><span class="description">🌡️ 게임사 유저보유금 모두 회수</span></a></p> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h2 class="mb-0">서버리소스 상황</h2>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <p><span class="description">🌡️ RAM Total:</span> <span class="result"><?php echo $serverstat['ramtotal']; ?> GB</span></p>
                            <p><span class="description">🌡️ RAM Used:</span> <span class="result"><?php echo $serverstat['ramused']; ?> GB</span></p>
                            <p><span class="description">🌡️ RAM Available:</span> <span class="result"><?php echo $serverstat['ramavailable']; ?> GB</span></p>
                        </div>
                        <div class="col-6">
                            <p><span class="description">💽 Hard Disk Free:</span> <span class="result"><?php echo $serverstat['diskfree']; ?> GB</span></p>
                            <p><span class="description">💽 Hard Disk Used:</span> <span class="result"><?php echo $serverstat['diskused']; ?> GB</span></p>
                            <p><span class="description">💽 Hard Disk Total:</span> <span class="result"><?php echo $serverstat['disktotal']; ?> GB</span></p>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-12">
            <div class="card   shadow">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h2 class=" mb-0">서버로그</h2>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Chart -->
                    <div>
                        <p>파일사이즈 - {{number_format($filesize/1000)}}KB</p>
                        <textarea id="content" name="content" rows="10" cols="150" style="width:100%;">{{$strinternallog}}</textarea>
                        <a href="{{argon_route('argon.system.logreset')}}"><button type="button" class="btn btn-warning">로그파일 리셋</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

