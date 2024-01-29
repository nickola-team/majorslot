@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', '죽장정산')
@section('page-heading', '죽장정산')

@section('content')

	<section class="content-header">
		@include('backend.Default.partials.messages')
	</section>

	<section class="content">
	<?php  
		$available_roles = Auth::user()->available_roles( true );
		$available_roles_trans = [];
		foreach ($available_roles as $key=>$role)
		{
			$role = \VanguardLTE\Role::find($key)->description;
			$available_roles_trans[$key] = $role;
		}
	?>
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">죽장정산</h3>
				<?php  
					$available_roles = Auth::user()->available_roles( true );
					$available_roles_trans = [];
					foreach ($available_roles as $key=>$role)
					{
						$role = \VanguardLTE\Role::find($key)->description;
						$available_roles_trans[$key] = $role;
					}
				?>
					@if($user != null && $user->id!=auth()->user()->id && !$user->isInoutPartner())
					<a href="{{ route($admurl.'.adjustment_ggr',  'parent='.$user->parent_id) }}" style="color:#72afd2;">
						{{$user->username}}
						[ {{$available_roles_trans[$user->role_id]}} ]
					</a>
					@endif
			</div>
			<div class="box-body">
				<div class="table-responsive">
					<table class="table table-bordered table-striped">
					<thead>
					<tr>
						<th>이름</th>
						<th>시작날짜</th>
						<th>마감날짜</th>
						<th>정산기간</th>
						<th>배팅금</th>
						<th>당첨금</th>
						<th>죽은금액</th>
						<th>죽장%</th>
						<th>죽장수익</th>
						<th>딜비%</th>
						<th>딜비수익</th>
						<th>정산금</th>
						<th>정산</th>
						<th>리셋</th>
					</tr>
					</thead>
					<tbody>
					@if (count($adjustments))
						@foreach ($adjustments as $adjustment)
							@include('backend.Default.adjustment.partials.row_ggr')
						@endforeach
					@else
						<tr><td colspan="14">죽장정산 파트너가 없습니다</td></tr>
					@endif
					<tr>
						<th>이름</th>
						<th>시작날짜</th>
						<th>마감날짜</th>
						<th>정산기간</th>
						<th>배팅금</th>
						<th>당첨금</th>
						<th>죽은금액</th>
						<th>죽장%</th>
						<th>죽장수익</th>
						<th>딜비%</th>
						<th>딜비수익</th>
						<th>합계</th>
						<th>정산</th>
						<th>리셋</th>
					</tr>
					</tbody>
					
					</table>
				</div>
			</div>	
		</div>

	</section>


	<div class="modal fade" id="openOutModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form action="{{ route($admurl.'.adjustment_shift_stat') }}" method="GET" id="outForm">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">정산</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label for="OutSum">환전금액</label>
							<input type="text" class="form-control" id="OutSum" name="summ" placeholder="환전금액"   required autofocus>
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
						</div>
						<div class="form-group">
							<label for="DealSum">딜비전환</label>
							<input type="text" class="form-control" id="DealSum" name="dealsumm" placeholder="전환금액"   required>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default pull-left" data-dismiss="modal">@lang('app.close')</button>
						<button type="button" class="btn btn-primary" onclick="adjustment_shift_stat();">확인</button>
					</div>
				</form>
			</div>
		</div>
	</div>

@stop

@section('scripts')
	<script>
		$('#stat-table').dataTable();
		$(function() {
			$('input[name="dates"]').daterangepicker({
				timePicker: true,
				timePicker24Hour: true,
				startDate: moment().subtract(30, 'day'),
				endDate: moment().add(7, 'day'),

				locale: {
					format: 'YYYY-MM-DD HH:mm'
				}
			});
			$('.btn-box-tool').click(function(event){
				if( $('.pay_stat_show').hasClass('collapsed-box') ){
					$.cookie('pay_stat_show', '1');
				} else {
					$.removeCookie('pay_stat_show');
				}
			});

			if( $.cookie('pay_stat_show') ){
				$('.pay_stat_show').removeClass('collapsed-box');
				$('.pay_stat_show .btn-box-tool i').removeClass('fa-plus').addClass('fa-minus');
			}

			$('.outPayment').click(function(event){
				if( $(event.target).is('.newPayment') ){
					var id = $(event.target).attr('data-id');
					var id1 = $(event.target).attr('data-id1');
				}else{
					var id = $(event.target).parents('.newPayment').attr('data-id');
					var id1 = $(event.target).parents('.newPayment').attr('data-id1');
				}
				$('#OutSum').val(id);
				$('#DealSum').val(id1);
			});
		});

		function withdraw_balance(onsuccess) {
            var money = $('#OutSum').val();
            var _token = $('#_token').val();

            $.ajax({
                type: 'POST',
                url: '/api/outbalance',
                data: { money: money, _token: _token },
                cache: false,
                async: false,
                success: function (data) {
                    if (data.error) {
                        alert(data.msg);
                        return;
                    }
					else if (onsuccess)
					{
						onsuccess();
					}
                    
                },
                error: function (err, xhr) {
                    alert(err.responseText);
                }
            });
        }

		function adjustment_shift_stat() {
            var _token = $('#_token').val();
			var _dealsum = $('#DealSum').val();
            $.ajax({
                type: 'POST',
                url: '/api/convert_deal_balance',
                data: { _token: _token, summ: _dealsum },
                cache: false,
                async: false,
                success: function (data) {
                    if (data.error && data.code != '000') {
                        alert(data.msg);
                        return;
                    }
					else {
						withdraw_balance( function(){
							$('#outForm').submit();
							});
					}
                    
                },
                error: function (err, xhr) {
                    alert(err.responseText);
                }
            });
        }
	</script>
@stop