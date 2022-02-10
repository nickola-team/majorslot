@extends('backend.argon.layouts.app')
@section('page-title',  '에이전트 지급내역')
@section('content')
<div class="container-fluid mt--8">
    <!-- Search -->
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header border-0" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                <h3 class="mb-0">검색</h3>
            </div>
            <hr class="my-1">
            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                <div class="card-body">
                    <form action="" method="GET" >
                        <div class="form-group row">
                            <label for="example-text-input" class="col-md-2 col-form-label form-control-label">에이전트</label>
                            <div class="col-md-4">
                                <input class="form-control" type="text" value="John Snow" id="example-text-input">
                            </div>
                            <label for="example-text-input" class="col-md-2 col-form-label form-control-label">지급자</label>
                            <div class="col-md-4">
                                <input class="form-control" type="text" value="John Snow" id="example-text-input">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-text-input" class="col-md-2 col-form-label form-control-label">타입</label>
                            <div class="col-md-4">
                                <input class="form-control" type="text" value="John Snow" id="example-text-input">
                            </div>
                            <label for="example-text-input" class="col-md-2 col-form-label form-control-label">방식</label>
                            <div class="col-md-4">
                                <input class="form-control" type="text" value="John Snow" id="example-text-input">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-text-input" class="col-md-2 col-form-label form-control-label">처리시간</label>
                            <div class="col-md-4">
                                <input class="form-control" type="text" value="John Snow" id="example-text-input">
                            </div>
                        </div>
                            
                        <div class="form-group row">
                            <button type="submit" class="btn btn-success col-md-12">검색</button>
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
        <h3 class="mb-0">에이전트 지급내역</h3>
    </div>
    <div class="table-responsive">
            <table class="table align-items-center table-flush">
            <thead class="thead-light">
                <tr>
                <th scope="col">번호</th>
                <th scope="col">에이전트</th>
                <th scope="col">지급자</th>
                <th scope="col">지급자보유금</th>
                <th scope="col">변동전 금액</th>
                <th scope="col">변동후 금액</th>
                <th scope="col">금액</th>
                <th scope="col">타입</th>
                <th scope="col">방식</th>
                <th scope="col">처리 시간</th>
                </tr>
            </thead>
            <tbody class="list">
                @if (count($statistics) > 0)
                    @foreach ($statistics as $stat)
                        <tr>
                            @include('backend.argon.agent.partials.row_transaction')
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="7">{{__('No Data')}}</td></tr>
                @endif
            </tbody>
            </table>
    </div>
    <!-- Card footer -->
    <div class="card-footer py-4">
        {{ $statistics->withQueryString()->links('backend.argon.vendor.pagination.argon') }}
    </div>
    </div>
</div>
</div>
@stop

@push('js')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
$(function() {
    $('input[name="dates"]').daterangepicker({
        timePicker: false,
        timePicker24Hour: false,
        startDate: moment().startOf('month'),
        endDate: moment(),
        locale: {
            format: 'YYYY-MM-DD'
        }
    });
});
</script>        
@endpush