@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', '충환전관리')
@section('page-heading', '충환전관리')

@section('content')

	<section class="content-header">
		@include('backend.Default.partials.messages')
	</section>

	<section class="content">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">{{$type=='add'?'충전':'환전'}}관리( {{count($in_out_request)}}건)</h3>
				@if (auth()->user()->hasRole(['comaster', 'master']))
				<div class="pull-right box-tools">
					<input type="checkbox" id="ratingOn" {{auth()->user()->rating>0?'checked':''}}>
						알림음 ON/OFF
				</div>
				@endif
			</div>
			@if (auth()->user()->isInoutPartner() && $type=='add')
			<div class="box-body">
				<div class="row">
					<div class="col-md-3">
						<b>은행이름:</b> 
							@php
								$banks = array_combine(\VanguardLTE\User::$values['banks'], \VanguardLTE\User::$values['banks']);
							@endphp
							{!! Form::select('bank_name', $banks, auth()->user()->bank_name ? auth()->user()->bank_name : '', ['class' => 'form-control', 'id' => 'bank_name']) !!}		
					</div>
					<div class="col-md-3">
						<b>계좌번호:</b> 
						<input type="text" class="form-control" id="account_no" name="account_no" value="{{ auth()->user()->account_no ? auth()->user()->account_no : '' }}">
					</div>
					<div class="col-md-3">
						<b>예금주:</b> 
						<input type="text" class="form-control" id="recommender" name="recommender" value="{{ auth()->user()->recommender ? auth()->user()->recommender : '' }}">
					</div>
				</div>
			</div>
			<div class="box-footer">
				<button class="btn btn-primary" id="change-bank-account-btn" onclick="change_bank_account_info();">
					계좌정보변경
				</button>
			</div>
			@endif
			
			<div class="box-body">
				<div class="table-responsive">
					<table class="table table-bordered table-striped">
					<thead>
					<tr>
						@if(auth()->user()->hasRole('distributor'))
						<th>매장이름</th>
						@else
						<th>파트너이름</th>
						@endif
						<th>신청금액</th>
						<th>계좌번호</th>
						<th>예금주</th>
						<th>시간</th>
						<th>상태</th>
						<th>승인</th>
						<th>대기</th>
						<th>취소</th>
					</tr>
					</thead>
					<tbody>
					@if (count($in_out_request))
						@foreach ($in_out_request as $in_out_log)
							@include('backend.Default.adjustment.partials.row_in_out')
						@endforeach
					@else
						<tr><td colspan="9">@lang('app.no_data')</td></tr>
					@endif
					</tbody>
					<thead>
					<tr>
						@if(auth()->user()->hasRole('distributor'))
						<th>매장이름</th>
						@else
						<th>파트너이름</th>
						@endif
						<th>신청금액</th>
						<th>계좌번호</th>
						<th>예금주</th>
						<th>시간</th>
						<th>상태</th>
						<th>승인</th>
						<th>대기</th>
						<th>최소</th>
					</tr>
					</thead>
                    </table>
                    </div>
						{{ $in_out_request->appends(Request::except('page'))->links() }}
			</div>
		</div>

	</section>

	<section class="content">

		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">{{$type=='add'?'충전':'환전'}}대기( {{count($in_out_wait)}}건)</h3>
			</div>
			
			<div class="box-body">
				<div class="table-responsive">
					<table class="table table-bordered table-striped">
					<thead>
					<tr>
						@if(auth()->user()->hasRole('distributor'))
						<th>매장이름</th>
						@else
						<th>파트너이름</th>
						@endif
						<th>신청금액</th>
						<th>계좌번호</th>
						<th>예금주</th>
						<th>시간</th>
						<th>상태</th>
						<th>승인</th>
						<th>최소</th>
					</tr>
					</thead>
					<tbody>
					@if (count($in_out_wait))
						@foreach ($in_out_wait as $in_out_log)
							@include('backend.Default.adjustment.partials.row_in_out')
						@endforeach
					@else
						<tr><td colspan="9">@lang('app.no_data')</td></tr>
					@endif
					</tbody>
					<thead>
					<tr>
						@if(auth()->user()->hasRole('distributor'))
						<th>매장이름</th>
						@else
						<th>파트너이름</th>
						@endif
						<th>신청금액</th>
						<th>계좌번호</th>
						<th>예금주</th>
						<th>시간</th>
						<th>상태</th>
						<th>승인</th>
						<th>최소</th>
					</tr>
					</thead>
                    </table>
                    </div>
						{{ $in_out_request->appends(Request::except('page'))->links() }}
			</div>
		</div>

	</section>

	<section class="content">

		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">최근 {{$type=='add'?'충전':'환전'}}내역</h3>
			</div>
			
			<div class="box-body">
				<div class="table-responsive">
					<table class="table table-bordered table-striped">
					<thead>
					<tr>
						<th>파트너이름</th>
						@if (auth()->user()->isInoutPartner())
						<?php
							$level = auth()->user()->level();
							echo "<th>매장(관리자)</th>";
							for($l=4;$l<$level;$l++)
							{
								echo "<th>".\VanguardLTE\Role::where('level',$l)->first()->description."</th>";
							}
						?>
						@endif
						<th>신청금액</th>
						<th>변동전금액</th>
						<th>변동후금액</th>
						<th>계좌번호</th>
						<th>예금주</th>
						<th>신청시간</th>
						<th>처리시간</th>
					</tr>
					</thead>
					<tbody>
					@if (count($in_out_logs))
						@foreach ($in_out_logs as $in_out_log)
							@include('backend.Default.adjustment.partials.row_in_out_log')
						@endforeach
					@else
						<tr><td colspan="9">@lang('app.no_data')</td></tr>
					@endif
					</tbody>
					<thead>
					<tr>
						<th>파트너이름</th>
						@if (auth()->user()->isInoutPartner())
						<?php
							$level = auth()->user()->level();
							echo "<th>매장(관리자)</th>";
							for($l=4;$l<$level;$l++)
							{
								echo "<th>".\VanguardLTE\Role::where('level',$l)->first()->description."</th>";
							}
						?>
						@endif
						<th>신청금액</th>
						<th>변동전금액</th>
						<th>변동후금액</th>
						<th>계좌번호</th>
						<th>예금주</th>
						<th>신청시간</th>
						<th>처리시간</th>
					</tr>
					</thead>
                    </table>
                    </div>
						{{ $in_out_request->appends(Request::except('page'))->links() }}
			</div>
		</div>

	</section>

	<div class="modal fade" id="openAllowModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form action="{{ route('frontend.api.allow_in_out') }}" method="POST" id="addForm">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">심사</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label for="OutSum">승인하시겠습니까</label>
							<input type="hidden" id="in_out_id" name="in_out_id">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default pull-left" data-dismiss="modal">취소</button>
						<button type="submit" class="btn btn-primary" id="btnAddSum">확인</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="openRejectModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form action="{{ route('frontend.api.reject_in_out') }}" method="POST" id="outForm">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">심사</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label for="OutSum">취소하시겠습니까</label>
							<input type="hidden" id="out_id" name="out_id">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default pull-left" data-dismiss="modal">취소</button>
						<button type="submit" class="btn btn-primary" id="btnOutSum">확인</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="infoModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">파트너정보</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-3">
							<div class="form-group">
								파트너구조
							</div>		
						</div>
						<div class="col-xs-6">
							<div class="form-group">
								<span id='hierarchytxt'></span>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">확인</button>
				</div>
			</div>
		</div>
	</div>

@stop

@section('scripts')
	<script>
		$('.allowPayment').click(function(event){
			if( $(event.target).is('.newPayment') ){
				var id = $(event.target).attr('data-id');
			}else{
				var id = $(event.target).parents('.newPayment').attr('data-id');
			}
			$('#in_out_id').val(id);

		});

		$('.rejectPayment').click(function(event){
			if( $(event.target).is('.newPayment') ){
				var id = $(event.target).attr('data-id');
			}else{
				var id = $(event.target).parents('.newPayment').attr('data-id');
			}
			$('#out_id').val(id);
		});


		$('.partnerInfo').click(function(event){
			var info = $(event.target).attr('data-id');
			$('#hierarchytxt').text(info);
		});
		

		$('#btnAddSum').click(function() {
			$(this).attr('disabled', 'disabled');
			$('form#addForm').submit();
		});
		$('#btnOutSum').click(function() {
			$(this).attr('disabled', 'disabled');
			$('form#outForm').submit();
		});

		$('#ratingOn').click(function (event) {
			var rating = 0;
			if($(this).is(":checked")){
				rating = 1;
			}
			$.ajax({
					url: "/api/inoutlist.json",
					type: "GET",
					data: {'rating': rating },
					dataType: 'json',
					success: function (data) {
                    }
				});
		});
		function change_bank_account_info() {
            var bank_name = $('#bank_name').val();
			var account_no = $('#account_no').val();
			var recommender = $('#recommender').val();
            var _token = $('#_token').val();

            $.ajax({
                type: 'POST',
                url: '/api/change_bank_account',
                data: { bank_name: bank_name, account_no: account_no, recommender: recommender, _token: _token },
                cache: false,
                async: false,
                success: function (data) {
                    if (data.error) {
                        alert(data.msg);
                        return;
                    }
                    alert('입금 계좌가 변경되었습니다.');
                    location.reload(true);
                },
                error: function (err, xhr) {
                    alert(err.responseText);
                }
            });
        }
	</script>
@stop