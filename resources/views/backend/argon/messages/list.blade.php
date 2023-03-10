@extends('backend.argon.layouts.app',[
        'parentSection' => 'customer',
        'elementName' => 'message-list'
    ])

@section('page-title',  '쪽지')
@push('css')
<link type="text/css" href="{{ asset('back/argon') }}/css/jquery.treetable.css" rel="stylesheet">
<link type="text/css" href="{{ asset('back/argon') }}/css/jquery.treetable.theme.default.css" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid">
	@if (auth()->user()->hasRole('admin'))
	<!-- Search -->
    <div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header border-0" id="headingOne">
                <div class="row align-items-center box">
                    <div class="col-8">
                        <h3 class="mb-0">알림 설정</h3>
                    </div>
                    <div class="col-4 text-right box-tools">
                        <a class="box-button" data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne"></a>
                    </div>
                </div>
            </div>
            <hr class="my-1">
            <div id="collapseOne" class="collapse show">
                <div class="card-body">
                    <form action="{{argon_route('argon.msg.monitor')}}" method="POST" >
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <label for="max_odd" class="col-md-2 col-form-label form-control-label text-center">고배당 당첨 알림</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="max_odd" value="{{$data['MaxOdd']}}" placeholder="">
                            </div>
                            <label for="max_win" class="col-md-2 col-form-label form-control-label text-center">고액 당첨 알림</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="max_win" value="{{$data['MaxWin']}}" placeholder="">
                            </div>
                            <div class="col-md-1">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-1">
                            </div>
                            <button type="submit" class="btn btn-primary col-md-10">설정</button>
                            <div class="col-md-1">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
	@endif
	<div class="row">
        <div class="col">
            <div class="card mt-4">
                <div class="card-header border-0">
                    <div class="pull-right">
					<a href="{{ argon_route('argon.msg.deleteall', ['type'=>\Request::get('type')]) }}"
						data-method="DELETE"
						data-confirm-title="확인"
						data-confirm-text="모든 쪽지를 삭제하시겠습니까?"
						data-confirm-delete="확인"
						data-confirm-cancel="취소">
						<button type="button" class="btn btn-warning btn-sm">모두삭제</button>
					</a>
                    @if (\Request::get('type') == 0)
						<a href="{{ argon_route('argon.msg.create', ['type'=>\Request::get('type')]) }}" class="btn btn-primary btn-sm">보내기</a>
                    @endif
					</div>
                    <h3 class="mb-0">쪽지</h3>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush" id="msglist">
						<thead class="thead-light">
						<tr>
							<th scope="col">발신자</th>
                            <th scope="col">수신자</th>
                            <th scope="col">타입</th>
							<th scope="col">제목</th>
							<th scope="col">작성시간</th>
							<th scope="col">읽은시간</th>
                            <th scope="col">읽은회원수</th>
                            <th scope="col">상태</th>
							<th scope="col"></th>
						</tr>
						</thead>
						<tbody>
                            @include('backend.argon.messages.partials.childs')
						</tbody>
					</table>
				</div>
                <!-- Card footer -->
				<div class="card-footer py-4">
					{{ $msgs->withQueryString()->links('backend.argon.vendor.pagination.argon') }}
				</div>
			</div>
		</div>
	</div>
</div>

@stop


@push('js')
<script src="{{ asset('back/argon') }}/js/jquery.treetable.js"></script>
<script>
    var table = $("#msglist");

    $("#msglist").treetable({ 
        expandable: true
        });

    $("#msglist").treetable("expandAll");
</script>
@endpush