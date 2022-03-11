@extends('backend.argon.layouts.app')
@section('page-title',  '게임별벳윈')

@push('css')
<link type="text/css" href="{{ asset('back/argon') }}/css/jquery.treetable.css" rel="stylesheet">
<link type="text/css" href="{{ asset('back/argon') }}/css/jquery.treetable.theme.default.css" rel="stylesheet">
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
                                <label for="player" class="col-md-2 col-form-label form-control-label text-center">파트너이름</label>
                                <div class="col-md-3">
                                    <input class="form-control" type="text" value="{{Request::get('partner')}}" id="partner"  name="partner">
                                </div>
                                <div class="col-md-1">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-1">
                                </div>
                                <label for="dates" class="col-md-2 col-form-label form-control-label text-center">기간선택</label>
                                <div class="col-md-2">
                                <input class="form-control" type="datetime-local" value="{{Request::get('dates')[0]??date('Y-m-d\TH:i', strtotime('-1 hours'))}}" id="dates" name="dates[]">
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
                <div class="card-header border-0">
                    <h3 class="mb-0">게임별벳윈</h3>
                    <div style="float:right" class="box">
                    마지막갱신시간 {{$updated_at}}
                    </div>
                </div>
                <div class="table-responsive">
                        <table class="table align-items-center table-flush" id="gamelist">
                        <thead class="thead-light">
                            <tr>
                                <th>날짜</th>
                                <th>게임이름</th>
								<th>베팅금</th>
								<th>당첨금</th>
								<th>죽은금액</th>
								<th>베팅횟수</th>
								<th>딜비수익</th>
								<th>하위수익</th>
								<th>최종수익</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @if (isset($categories))
                                @foreach ($categories as $adjustment)
                                <tr>
                                <td rowspan="{{count($adjustment['cat'])}}"> {{ $adjustment['date'] }}</td>
                                    @include('backend.argon.report.partials.row_game', ['total' => false])
                                </tr>

                                @endforeach
                            @endif
                            <tr>
                            <td > {{ $totalcategory['date'] }}</td>
                            @include('backend.argon.report.partials.row_game', ['adjustment' => ['cat' => [$totalcategory]], 'total' => true])
                            </tr>
                        </tbody>
                        </table>
                </div>
                <div id="waitAjax" class="loading" style="margin-left: 0px; display:none;">
                    <img src="{{asset('back/argon')}}/img/theme/loading.gif">
                </div>
                <div class="card-footer py-4">
                    {{-- $categories->withQueryString()->links('backend.argon.vendor.pagination.argon') --}}
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@push('js')
<script src="{{ asset('back/argon') }}/js/jquery.treetable.js"></script>
<script>
    var table = $("#gamelist");
    $("#gamelist").treetable({ 
        expandable: true ,
        onNodeCollapse: function() {
            var node = this;
            table.treetable("unloadBranch", node);
        },
        onNodeExpand: function() {
            var node = this;
            table.treetable("unloadBranch", node);
            $('#waitAjax').show();
            $.ajax({
                async: true,
                url: "{{argon_route('argon.report.childdaily.dw', 'dw')}}?id="+node.id
                }).done(function(html) {
                    var rows = $(html).filter("tr");
                    table.treetable("loadBranch", node, rows);
                    $('#waitAjax').hide();
            });
        }
    });
</script>
@endpush