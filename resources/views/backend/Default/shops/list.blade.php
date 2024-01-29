@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', '매장관리')
@section('page-heading', '매장관리')
<?php  
			$available_roles = Auth::user()->available_roles( true );
			$available_roles_trans = [];
			foreach ($available_roles as $key=>$role)
			{
				$role = \VanguardLTE\Role::find($key)->description;
				$available_roles_trans[$key] = $role;
			}
?>
@section('content')

	<section class="content-header">
		@include('backend.Default.partials.messages')
	</section>

	<section class="content">

		<div class="row">
			<div class="col-lg-6 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-light-blue">
					<div class="inner">
						<h3>{{ $stats['shops'] }}</h3>
						<p>@lang('app.total_shops')</p>
					</div>
					<div class="icon">
						<i class="fa fa-refresh"></i>
					</div>
				</div>
			</div>
			
			<div class="col-lg-6 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-red">
					<div class="inner">
						<h3>{{ number_format( $stats['credit'], 0 ) }}</h3>
						<p>@lang('app.total_credit')</p>
					</div>
					<div class="icon">
						<i class="fa fa-line-chart"></i>
					</div>
				</div>
			</div>
			<!-- ./col -->

		</div>



		<form action="" method="GET">
			<div class="box box-danger collapsed-box shops_show">
				<div class="box-header with-border">
					<h3 class="box-title">@lang('app.filter')</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
					</div>
				</div>
				<div class="box-body">
						<div class="col-md-6">
							<div class="form-group">
								<label>이름</label>
								<input type="text" class="form-control" name="name" value="{{ Request::get('name') }}">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>@lang('app.status')</label>
								{!! Form::select('status', ['' => __('app.all'), '1' => __('app.active'), '0' => __('app.disabled')], Request::get('status'), ['id' => 'type', 'class' => 'form-control']) !!}
							</div>
						</div>

						
						<div class="col-md-6">
							<div class="form-group">
								<label>최소보유금액</label>
								<input type="text" class="form-control" name="credit_from" value="{{ Request::get('credit_from') }}">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>최대보유금액</label>
								<input type="text" class="form-control" name="credit_to" value="{{ Request::get('credit_to') }}">
							</div>
						</div>
						
				</div>
				<div class="box-footer">
					<button type="submit" class="btn btn-primary">
						@lang('app.filter')
					</button>

				</div>
			</div>
		</form>

		<div class="box box-primary">
			<div class="box-header with-border">
				{{-- <h3 class="box-title">@lang('app.shops')</h3> --}}
				<h3 class="box-title">매장목록</h3>
				@if(auth()->user()->hasRole('distributor'))
					<div class="pull-right box-tools">
						<a href="{{ route($admurl.'.shop.create') }}" class="btn btn-block btn-primary btn-sm">@lang('app.add')</a>
					</div>
				@endif
			</div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
					<thead>
					<tr>
						<th>@lang('app.name')</th>
						<?php
							for ($r=3;$r<auth()->user()->role_id;$r++)
							{
								echo '<th>'.$available_roles_trans[$r].'</th>';
							}
						?>
						<th>@lang('app.credit')</th>
						@if(auth()->user()->hasRole('admin'))
						<th>@lang('app.percent')%</th>
						@endif
						<th>딜비수익</th>
						@if (auth()->user()->hasRole('admin')  || auth()->user()->ggr_percent > 0 || (auth()->user()->hasRole('manager') && auth()->user()->shop->ggr_percent > 0))
						<th>죽장수익</th>
						@endif
						<th>딜비</th>
						<th>라이브딜비</th>
						@if (auth()->user()->hasRole('admin')  || auth()->user()->ggr_percent > 0 || (auth()->user()->hasRole('manager') && auth()->user()->shop->ggr_percent > 0))
						<th>죽장퍼센트</th>
						<th>정산기간</th>
						<th>다음정산시간</th>
						@endif
						<th>@lang('app.status')</th>
						<th>@lang('app.pay_in')</th>
						<th>@lang('app.pay_out')</th>
					</tr>
					</thead>
					<tbody>
					@if (count($shops))
						@foreach ($shops as $shop)
							@include('backend.Default.shops.partials.row')
						@endforeach
					@else
						<tr><td colspan="14">@lang('app.no_data')</td></tr>
					@endif
					</tbody>
					<thead>
					<tr>
						<th>@lang('app.name')</th>
						<?php
							for ($r=3;$r<auth()->user()->role_id;$r++)
							{
								echo '<th>'.$available_roles_trans[$r].'</th>';
							}
						?>
						{{-- <th>@lang('app.id')</th> --}}
						<th>@lang('app.credit')</th>
						@if(auth()->user()->hasRole('admin'))
						<th>@lang('app.percent')%</th>
						@endif
						<th>딜비수익</th>
						@if (auth()->user()->hasRole('admin')  || auth()->user()->ggr_percent > 0 || (auth()->user()->hasRole('manager') && auth()->user()->shop->ggr_percent > 0))
						<th>죽장수익</th>
						@endif
						<th>딜비</th>
						<th>라이브딜비</th>
						@if (auth()->user()->hasRole('admin')  || auth()->user()->ggr_percent > 0 || (auth()->user()->hasRole('manager') && auth()->user()->shop->ggr_percent > 0))
						<th>죽장퍼센트</th>
						<th>정산기간</th>
						<th>다음정산시간</th>
						@endif
						<th>@lang('app.status')</th>
						<th>@lang('app.pay_in')</th>
						<th>@lang('app.pay_out')</th>
					</tr>
					</thead>
                            </table>

							{{ $shops->links() }}
                        </div>
                    </div>
		</div>

	</section>

	<!-- Modal -->
	<div class="modal fade" id="openAddModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form action="{{ route($admurl.'.shop.balance') }}" method="POST"  id="addForm">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">@lang('app.balance') @lang('app.pay_in')</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label for="AddSum">@lang('app.sum')</label>
							<input type="text" class="form-control" id="AddSum" name="summ" placeholder="@lang('app.sum')" required>
							<input type="hidden" name="type" value="add">
							<input type="hidden" id="AddId" name="shop_id">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<br>
							<button type="button" class="btn btn-default changeAddSum" data-value="10000">10000</button>
							<button type="button" class="btn btn-default changeAddSum" data-value="20000">20000</button>
							<button type="button" class="btn btn-default changeAddSum" data-value="30000">30000</button>
							<button type="button" class="btn btn-default changeAddSum" data-value="50000">50000</button>
							<button type="button" class="btn btn-default changeAddSum" data-value="100000">100000</button>
							<button type="button" class="btn btn-default changeAddSum" data-value="200000">200000</button>
							<button type="button" class="btn btn-default changeAddSum" data-value="300000">300000</button>
							<button type="button" class="btn btn-primary changeAddSum" data-value="0">초기화</button>
							<p></p>
							<label for="OutSum">충전사유</label>
							<input type="text" class="form-control" id="reason" name="reason" placeholder="충전사유를 입력해주세요">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('app.close')</button>
						<button type="submit" class="btn btn-primary" id="btnAddSum">@lang('app.pay_in')</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="openOutModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form action="{{ route($admurl.'.shop.balance') }}" method="POST" id="outForm">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">@lang('app.balance') @lang('app.pay_out')</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label for="OutSum">@lang('app.sum')</label>
							<input type="text" class="form-control" id="OutSum" name="summ" placeholder="@lang('app.sum')" required>
							<input type="hidden" id="outAll" name="all" value="0">
							<input type="hidden" name="type" value="out">
							<input type="hidden" id="OutId" name="shop_id">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<br>
							<button type="button" class="btn btn-default changeOutSum" data-value="10000">10000</button>
							<button type="button" class="btn btn-default changeOutSum" data-value="20000">20000</button>
							<button type="button" class="btn btn-default changeOutSum" data-value="30000">30000</button>
							<button type="button" class="btn btn-default changeOutSum" data-value="50000">50000</button>
							<button type="button" class="btn btn-default changeOutSum" data-value="100000">100000</button>
							<button type="button" class="btn btn-default changeOutSum" data-value="200000">200000</button>
							<button type="button" class="btn btn-default changeOutSum" data-value="300000">300000</button>
							<button type="button" class="btn btn-primary changeOutSum" data-value="0">초기화</button>
							<p></p>
							<label for="OutSum">환전사유</label>
							<input type="text" class="form-control" id="reason" name="reason" placeholder="환전사유를 입력해주세요">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('app.close')</button>
						<button type="button" class="btn btn-danger" id="doOutAll"> @lang('app.all') @lang('app.pay_out')</button>
						<button type="submit" class="btn btn-primary" id="btnOutSum">@lang('app.pay_out')</button>
					</div>
				</form>
			</div>
		</div>
	</div>

@stop

@section('scripts')
	<script>
		$('#shops-table').dataTable();
		$("#view").change(function () {
			$("#shops-form").submit();
		});
		$('.addPayment').click(function(event){
			console.log($(event.target));
			var item = $(event.target).hasClass('addPayment') ? $(event.target) : $(event.target).parent();
			var id = item.attr('data-id');
			$('#AddId').val(id);
		});
		$('.changeAddSum').click(function(event){
			$v = Number($('#AddSum').val());
			if ($(event.target).data('value') == 0)
			{
				$('#AddSum').val(0);
			}
			else
			{
				$('#AddSum').val($v + $(event.target).data('value'));
			}
		});

		$('.changeOutSum').click(function(event){
			$v = Number($('#OutSum').val());
			if ($(event.target).data('value') == 0)
			{
				$('#OutSum').val(0);
			}
			else
			{
				$('#OutSum').val($v + $(event.target).data('value'));
			}
		});

		$('.outPayment').click(function(event){
			console.log($(event.target));
			var item = $(event.target).hasClass('outPayment') ? $(event.target) : $(event.target).parent();
			var id = item.attr('data-id');
			$('#OutId').val(id);
			$('#outAll').val('0');
		});


		$('#btnAddSum').click(function() {
			$(this).attr('disabled', 'disabled');
			$('form#addForm').submit();
		});
		$('#btnOutSum').click(function() {
			$(this).attr('disabled', 'disabled');
			$('form#outForm').submit();
		});
		$('#doOutAll').click(function () {
			$(this).attr('disabled', 'disabled');
			$('#outAll').val('1');
			$('form#outForm').submit();
		});

		$('.btn-box-tool').click(function(event){
			if( $('.shops_show').hasClass('collapsed-box') ){
				$.cookie('shops_show', '1');
			} else {
				$.removeCookie('shops_show');
			}
		});

		if( $.cookie('shops_show') ){
			$('.shops_show').removeClass('collapsed-box');
			$('.shops_show .btn-box-tool i').removeClass('fa-plus').addClass('fa-minus');
		}
	</script>
@stop
