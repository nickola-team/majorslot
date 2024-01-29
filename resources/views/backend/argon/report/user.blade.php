@extends('backend.argon.layouts.app',[
        'parentSection' => 'report',
        'elementName' => 'report-game'
    ])
@section('page-title',  '유저별벳윈')

@push('css')
<link type="text/css" href="{{ asset('back/argon') }}/css/jquery.treetable.css" rel="stylesheet">
<link type="text/css" href="{{ asset('back/argon') }}/css/jquery.treetable.theme.default.css" rel="stylesheet">
@if (auth()->user()->hasRole('admin'))
<style>
    .bw-btn {
        display: none;
      }
    .bw-title:hover .bw-btn {
        display: inline-block;
      }
</style>
@endif
@endpush

@section('content-header')

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
                                <label for="player" class="col-md-2 col-form-label form-control-label text-center">유저아이디</label>
                                <div class="col-md-2">
                                    <input class="form-control" type="text" value="{{Request::get('user')}}" id="user"  name="user">
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control" id="fieldtype" name="fieldtype">
                                        <option value="bet" @if (Request::get('fieldtype') == 'bet' || Request::get('fieldtype') == '') selected @endif>배팅금</option>
                                        <option value="win" @if (Request::get('fieldtype') == 'win') selected @endif>당첨금</option>
                                        <option value="betwin" @if (Request::get('fieldtype') == 'betwin') selected @endif>벳윈</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control" id="fieldsort" name="fieldsort">
                                        <option value="desc" @if (Request::get('fieldsort') == 'desc' || Request::get('fieldsort') == '') selected @endif>오름순</option>
                                        <option value="asc" @if (Request::get('fieldsort') == 'asc') selected @endif>내림순</option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-1">
                                </div>
                                <label for="dates" class="col-md-2 col-form-label form-control-label text-center">기간선택</label>
                                <div class="col-md-2">
                                <input class="form-control" type="date" value="{{Request::get('dates')[0]??date('Y-m-d')}}" id="dates" name="dates[]">
                                </div>
                                <label for="dates" class="col-form-label form-control-label" >~</label>
                                <div class="col-md-2">
                                <input class="form-control" type="date" value="{{Request::get('dates')[1]??date('Y-m-d')}}" id="dates" name="dates[]">
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
        <div class="col-xl-3 col-lg-3">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="mb-0">카지노</h3>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th>유저아이디</th>
                                <th>배팅금</th>                                
                                <th>당첨금</th>
                                <th>벳윈</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @if (count($tablestatics) > 0)
                                @foreach ($tablestatics as $stat)
                                    @include('backend.argon.report.partials.row_user')
                                @endforeach
                            @else
                                <tr><td colspan="4">{{__('No Data')}}</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="mb-0">슬롯</h3>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th>유저아이디</th>
                                <th>배팅금</th>                                
                                <th>당첨금</th>
                                <th>벳윈</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @if (count($slotstatics) > 0)
                                @foreach ($slotstatics as $stat)
                                    @include('backend.argon.report.partials.row_user')
                                @endforeach
                            @else
                                <tr><td colspan="4">{{__('No Data')}}</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="mb-0">파워볼</h3>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th>유저아이디</th>
                                <th>배팅금</th>                                
                                <th>당첨금</th>
                                <th>벳윈</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @if (count($pballstatics) > 0)
                                @foreach ($pballstatics as $stat)
                                    @include('backend.argon.report.partials.row_user')
                                @endforeach
                            @else
                                <tr><td colspan="4">{{__('No Data')}}</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="mb-0">합계</h3>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th>유저아이디</th>
                                <th>배팅금</th>                                
                                <th>당첨금</th>
                                <th>벳윈</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @if (count($totalstatics) > 0)
                                @foreach ($totalstatics as $stat)
                                    @include('backend.argon.report.partials.row_user')
                                @endforeach
                            @else
                                <tr><td colspan="4">{{__('No Data')}}</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer py-4">
        {{ $totalstatics->withQueryString()->links('backend.argon.vendor.pagination.argon') }}
    </div>
</div>
@stop

