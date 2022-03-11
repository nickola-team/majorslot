@extends('backend.argon.layouts.app',[
        'parentSection' => 'dw',
        'elementName' => 'dw-outmanage'
    ])
@section('page-title',  '환전관리')
@section('content-header')
<div class="row">
    <div class="col-xl-3 col-lg-3">
        <div class="card card-stats  mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col ">
                        <h3 class="card-title text-success mb-0 ">신청합계</h3>
                        <span class="h2 font-weight-bold mb-0 text-success">{{number_format($total['add'])}}</span>
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
                        <h3 class="card-title text-warning mb-0 ">대기합계</h3>
                        <span class="h2 font-weight-bold mb-0 text-warning">{{number_format($total['out'])}}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                            <i class="fas fa-chart-pie"></i>
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
<div class="col">
    <div class="card mt-4">
    <!-- Light table -->
    <!-- Card header -->
    <div class="card-header border-0">
        <h3 class="mb-0">신청내역</h3>
    </div>
    <div class="table-responsive">
            <table class="table align-items-center table-flush">
            <thead class="thead-light">
                <tr>
                <th scope="col">번호</th>
                <th scope="col">신청자</th>
                <th scope="col">신청자 보유금</th>
                <th scope="col">신청금액</th>
                <th scope="col">거래계좌</th>
                <th scope="col">신청 시간</th>
                <th scope="col"></th>
                </tr>
            </thead>
            <tbody class="list">
                @if (count($in_out_request) > 0)
                    @foreach ($in_out_request as $stat)
                        <tr>
                            @include('backend.argon.dw.partials.row_manage')
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="6">{{__('No Data')}}</td></tr>
                @endif
            </tbody>
            </table>
    </div>
    <!-- Card footer -->
    <div class="card-footer py-4">
        {{ $in_out_request->withQueryString()->links('backend.argon.vendor.pagination.argon') }}
    </div>
    </div>
    </div>
</div>
<div class="row">
<div class="col">
    <div class="card mt-4">
    <!-- Light table -->
    <!-- Card header -->
    <div class="card-header border-0">
        <h3 class="mb-0">대기내역</h3>
    </div>
    <div class="table-responsive">
            <table class="table align-items-center table-flush">
            <thead class="thead-light">
                <tr>
                <th scope="col">번호</th>
                <th scope="col">신청자</th>
                <th scope="col">신청자 보유금</th>
                <th scope="col">신청금액</th>
                <th scope="col">거래계좌</th>
                <th scope="col">신청 시간</th>
                <th scope="col"></th>
                </tr>
            </thead>
            <tbody class="list">
                @if (count($in_out_wait) > 0)
                    @foreach ($in_out_wait as $stat)
                        <tr>
                            @include('backend.argon.dw.partials.row_manage')
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="6">{{__('No Data')}}</td></tr>
                @endif
            </tbody>
            </table>
    </div>
    <!-- Card footer -->
    <div class="card-footer py-4">
        {{ $in_out_request->withQueryString()->links('backend.argon.vendor.pagination.argon') }}
    </div>
    </div>
    </div>
</div>

<div class="row">
<div class="col">
    <div class="card mt-4">
    <!-- Light table -->
    <!-- Card header -->
    <div class="card-header border-0">
        <h3 class="mb-0">최근처리내역</h3>
    </div>
    <div class="table-responsive">
            <table class="table align-items-center table-flush">
            <thead class="thead-light">
                <tr>
                <th scope="col">번호</th>
                <th scope="col">신청자</th>
                <th scope="col">변동전 금액</th>
                <th scope="col">변동후 금액</th>
                <th scope="col">신청금액</th>
                <th scope="col">타입</th>
                <th scope="col">거래계좌</th>
                <th scope="col">신청 시간</th>
                <th scope="col">처리 시간</th>
                <th scope="col">상태</th>
                </tr>
            </thead>
            <tbody class="list">
                @if (count($in_out_logs) > 0)
                    @foreach ($in_out_logs as $stat)
                        <tr>
                            @include('backend.argon.dw.partials.row_history')
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="6">{{__('No Data')}}</td></tr>
                @endif
            </tbody>
            </table>
    </div>
    <!-- Card footer -->
    <div class="card-footer py-4">
        {{ $in_out_request->withQueryString()->links('backend.argon.vendor.pagination.argon') }}
    </div>
    </div>
    </div>
</div>
</div>

@stop

@push('js')
<script>
</script>
@endpush