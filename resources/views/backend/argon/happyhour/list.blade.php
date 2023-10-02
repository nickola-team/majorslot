@extends('backend.argon.layouts.app',
    [
        'parentSection' => 'game',
        'elementName' => 'game-happyhour'
    ])
@section('page-title',  '유저 콜')

@section('content-header')
<div class="row">
    <div class="col-xl-3 col-lg-3">
        <div class="card card-stats  mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col ">
                        <h3 class="card-title text-success mb-0 ">총 당첨금</h3>
                        <span class="h2 font-weight-bold mb-0 text-success">{{number_format($total['totalbank'])}}</span>
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
                    <div class="col">
                        <a href="{{argon_route('argon.player.list') . '?join[]='. date('Y-m-d\T00:00') . '&join[]='.date('Y-m-d\TH:i')}}">
                        <h3 class="card-title text-primary mb-0 ">총 남은당첨금</h3>
                        <span class="h2 font-weight-bold mb-0 text-primary">{{number_format($total['currentbank'])}}</span>
                        </a>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
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
                    <div class="col">
                        <a href="{{argon_route('argon.player.list', ['online' => 1])}}">
                        <h3 class="card-title text-danger mb-0 ">총 오버된당첨금</h3>
                        <span class="h2 font-weight-bold mb-0 text-danger">{{number_format($total['overbank'])}}</span>
                        </a>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                            <i class="fas fa-chart-bar"></i>
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
    <!-- Search -->
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header border-0" id="headingOne">
                <div class="row align-items-center box">
                    <div class="col-8">
                        <h3 class="mb-0">검색</h3>
                    </div>
                    <div class="col-4 text-right box-tools">
                        <a class="box-button" data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne"></a>
                    </div>
                </div>
            </div>
            <hr class="my-1">
            <div id="collapseOne" class="collapse show">
                <div class="card-body">
                    <form action="" method="GET" >
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="user" class="col-md-2 col-form-label form-control-label text-center">회원 아이디</label>
                            <div class="col-md-3 d-flex">
                                <div class="col-md-8">
                                    <input class="form-control" type="text" value="{{Request::get('user')}}" id="user" name="user">
                                </div>
                                <div class="custom-control custom-checkbox mt-2">
                                    <input class="custom-control-input" id="includename" name="includename" type="checkbox" {{Request::get('includename')=='on'?'checked':''}}>   <label class="custom-control-label" for="includename">포함된이름</label>
                                </div>
                            </div>
                            <label for="partner" class="col-md-2 col-form-label form-control-label text-center">작성자</label>
                            <div class="col-md-3">
                                <input class="form-control" type="text" value="{{Request::get('partner')}}" id="partner" name="partner">
                            </div>
                            <div class="col-md-1">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="status" class="col-md-2 col-form-label form-control-label text-center">상태</label>
                            <div class="col-md-3">
                                <select class="form-control" id="status" name="status">
                                    <option value="" @if (Request::get('status') == '') selected @endif>모두</option>
                                    <option value="1" @if (Request::get('status') == 1) selected @endif>활성</option>
									<option value="2" @if (Request::get('status') == 2) selected @endif>완료</option>
                                </select>
                            </div>
                            <label for="total_bank" class="col-md-2 col-form-label form-control-label text-center">총당첨금순</label>
                            <div class="col-md-3">
                                <select class="form-control" id="total_bank" name="total_bank">
                                    <option value="" @if (Request::get('total_bank') == '') selected @endif>순서없음</option>
									<option value="1" @if (Request::get('total_bank') == 1) selected @endif> 많은순서</option>
                                    <option value="2" @if (Request::get('total_bank') == 2) selected @endif> 작은순서</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="dates" class="col-md-2 col-form-label form-control-label text-center">작성시간</label>
                            <div class="col-md-2">
                            <input class="form-control" type="datetime-local" value="{{Request::get('dates')[0]??date('Y-m-d\TH:i', strtotime('-24 hours'))}}" id="dates" name="dates[]">
                            </div>
                            <label for="dates" class="col-form-label form-control-label" >~</label>
                            <div class="col-md-2">
                            <input class="form-control" type="datetime-local" value="{{Request::get('dates')[1]??date('Y-m-d\TH:i')}}" id="dates" name="dates[]">
                            </div>
                        </div>
                            
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <button type="submit" class="btn btn-primary col-md-10">검색</button>
                            <div class="col-md-1">
                            </div>
                        </div>
                    </form>
                </div>
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
		<div class="pull-right">
			<a href="{{ argon_route('argon.happyhour.create') }}" class="btn btn-primary btn-sm">새로추가</a>
		</div>
        <h3 class="mb-0">유저 콜 목록</h3>
    </div>
    <div class="table-responsive">
            <table class="table align-items-center table-flush" id="agentlist">
            <thead class="thead-light">
                <tr>
                <th>번호</th>
                <th>작성자</th>
				<th>회원아이디</th>
				<th>총 당첨금</th>
				<th>남은당첨금</th>
				<th>오버된당첨금</th>
				<!-- <th>잭팟기능</th> -->
                <th>작성시간</th>
                <th>완료시간</th>
				<th>상태</th>
				<th></th>
                </tr>
            </thead>
            <tbody class="list">
					@if (count($happyhours))
						@foreach ($happyhours as $happyhour)
							@include('backend.argon.happyhour.partials.row')
						@endforeach
					@else
						<tr><td colspan="6">{{__('No Data')}}</td></tr>
					@endif
            </tbody>
            </table>
    </div>
    <!-- Card footer -->
    <div class="card-footer py-4">
        {{ $happyhours->withQueryString()->links('backend.argon.vendor.pagination.argon') }}
    </div>
    </div>
</div>
</div>
@stop

