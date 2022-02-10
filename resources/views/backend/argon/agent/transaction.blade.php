@extends('backend.argon.layouts.app')
@section('page-title',  '에이전트 지급내역')
@section('content')
<div class="container-fluid mt--7">
    <!-- Search -->
<div class="row">
    <div class="col">
        <div class="card shadow">
        <div class="card-header border-0">
            <h3 class="mb-0">{{__('Search')}}</h3>
        </div>
        <hr class="my-1">
        <div class="card-body">
            <form action="" method="GET" >
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="name" class="form-control-label">{{__('DATETIME')}}</label>
                            <input class="form-control" name="dates" value="{{ Request::get('dates') }}" >
                        </div>
                    </div>
                </div>
                <div class="text-left">
                    <button type="submit" class="btn btn-success">{{__('Search')}}</button>
                </div>
            </form>
        </div>
        </div>
    </div>
</div>

<div class="row">
<div class="col">
    <div class="card shadow mt-4">
    <!-- Light table -->
    <!-- Card header -->
    <div class="card-header border-0">
        <h3 class="mb-0">{{__('Transfer History')}}</h3>
    </div>
    <hr class="my-1">
    <div class="table-responsive">
        <div class="row">
            <div class="col-sm-12 col-md-6"><div class="dataTables_length" id="datatable-basic_length"><label>Show <select name="datatable-basic_length" aria-controls="datatable-basic" class="form-control form-control-sm"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select> entries</label></div></div>
            <div class="col-sm-12 col-md-6"><div id="datatable-basic_filter" class="dataTables_filter"><label>Search:<input type="search" class="form-control form-control-sm" placeholder="" aria-controls="datatable-basic"></label></div></div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <table class="table align-items-center table-flush">
                <thead class="thead-light">
                    <tr>
                    <th scope="col" class="sort" data-sort="name">{{__('Name')}}</th>
                    <th scope="col" class="sort" data-sort="name">{{__('Payer')}}</th>
                    <th scope="col" class="sort" data-sort="name">{{__('PayerBalance')}}</th>
                    <th scope="col" class="sort" data-sort="budget">{{__('OldBalance')}}</th>
                    <th scope="col" class="sort" data-sort="status">{{__('NewBalance')}}</th>
                    <th scope="col" class="sort" data-sort="status">{{__('TransferType')}}</th>
                    <th scope="col" class="sort" data-sort="status">{{__('SUM')}}</th>
                    <th scope="col" class="sort" data-sort="status">{{__('DATETIME')}}</th>
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
        </div>
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