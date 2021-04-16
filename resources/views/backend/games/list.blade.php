@extends('backend.layouts.app')

@section('page-title', trans('app.games'))
@section('page-heading', trans('app.games'))

@section('content')

	<section class="content-header">
		@include('backend.partials.messages')
	</section>

	<section class="content">
		<div class="box box-primary">
			<div class="box-header with-border">
				<div class="col-md-6">
					<form id="change-shop-form"  name = "set_shop" action="{{ route('backend.profile.setshop') }}" method="POST">
					<div class="col-md-6">
						<div class="form-group">
							<label>매장</label>
							{!! Form::select('shop_id',
								Auth::user()->shops_array(), Auth::user()->shop_id, ['class' => 'form-control', 'style' => 'width: 100%;', 'id' => 'shop_id']) !!}
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
						</div>
					</div>
					</form>	
				</div>
				
			</div>
		</div>
		@permission('games.info')
		<div class="row">
			<!-- ./col -->
			<div class="col-lg-3 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-green">
					<div class="inner">
						<h3>{{ number_format($stats['in'],2) }}</h3>
						<p>총베팅금</p>
					</div>
					<div class="icon">
						<i class="fa fa-level-up"></i>
					</div>
				</div>
			</div>
			<!-- ./col -->
			<div class="col-lg-3 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-yellow">
					<div class="inner">
						<h3>{{ number_format($stats['out'],2) }}</h3>
						<p>총당첨금</p>
					</div>
					<div class="icon">
						<i class="fa fa-level-down"></i>
					</div>
				</div>
			</div>
			<!-- ./col -->
			<div class="col-lg-3 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-red">
					<div class="inner">

						<h3>{{ number_format($stats['games']) }}</h3>

						<p>방문횟수</p>
					</div>
					<div class="icon">
						<i class="fa fa-line-chart"></i>
					</div>
				</div>
			</div>
			<!-- ./col -->
			{{-- <div class="col-lg-3 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-red">
					<div class="inner">

						<h3>{{ number_format($stats['disabled']) }}</h3>

						<p>Disabled Games</p>
					</div>
					<div class="icon">
						<i class="fa fa-line-chart"></i>
					</div>
				</div>
			</div> --}}
			<!-- ./col -->
			<div class="col-lg-3 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-red">
					<div class="inner">

						<h3>{{ auth()->user()->shop?auth()->user()->shop->percent:'0' }}</h3>

						<p>환수율%</p>
					</div>
					<div class="icon">
						<i class="fa fa-line-chart"></i>
					</div>
				</div>
			</div>
			
		</div>
		<div class="row">
			
			<!-- ./col -->
			<div class="col-lg-3 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-red">
					<div class="inner">
						<h3>{{ number_format( $stats['rtp'], 2 ) }}</h3>
						<p>@lang('app.average_RTP')%</p>
					</div>
					<div class="icon">
						<i class="fa fa-line-chart"></i>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-red">
					<div class="inner">
						<h3>{{ number_format( $stats['bonus'], 2 ) }}</h3>
						<p>보너스환수금</p>
					</div>
					<div class="icon">
						<i class="fa fa-line-chart"></i>
					</div>
					{{-- @if( auth()->user()->hasRole('distributor') ) --}}
						<a href="javascript:;" class="small-box-footer openAdd" data-toggle="modal" data-target="#openAddModal" data-type="bonus">환수금조절 <i class="fa fa-arrow-circle-right"></i></a>
					{{-- @endif --}}
				</div>
			</div>
			<!-- ./col -->
			<div class="col-lg-3 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-light-blue">
					<div class="inner">
						<h3>{{ number_format($stats['slots'], 2) }}</h3>
						<p>일반환수금</p>
					</div>
					<div class="icon">
						<i class="fa fa-refresh"></i>
					</div>
					{{-- @if( auth()->user()->hasRole('distributor') ) --}}
					<a href="javascript:;" class="small-box-footer openAdd" data-toggle="modal" data-target="#openAddModal" data-type="slots">환수금조절 <i class="fa fa-arrow-circle-right"></i></a>
					{{-- @endif --}}
				</div>
			</div>
			<div class="col-lg-3 col-xs-6">
				<!-- small box -->
				<div class="small-box  bg-green">
					<div class="inner">
						<h3>{{ number_format($stats['bank'],2) }}</h3>
						<p>총 환수금</p>
					</div>
					<div class="icon">
						<i class="fa fa-refresh"></i>
					</div>
				</div>
			</div>
			<!-- ./col -->
			{{-- <div class="col-lg-3 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-green" >
					<div class="inner">
						<h3>{{ number_format($stats['little'], 2) }}</h3>
						<p>@lang('app.little')</p>
					</div>
					<div class="icon">
						<i class="fa fa-level-up"></i>
					</div>
					@if( auth()->user()->hasRole('distributor') )
					<a href="javascript:;" class="small-box-footer openAdd" data-toggle="modal" data-target="#openAddModal" data-type="little">@lang('app.gamebank') @lang('app.pay_in') <i class="fa fa-arrow-circle-right"></i></a>
					@endif
				</div>
			</div> --}}
		</div>
		<div class="row">
			<!-- ./col -->
			{{-- <div class="col-lg-3 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-yellow">
					<div class="inner">
						<h3>{{ number_format($stats['table_bank'], 2) }}</h3>
						<p>@lang('app.table_bank')</p>
					</div>
					<div class="icon">
						<i class="fa fa-level-down"></i>
					</div>
					@if( auth()->user()->hasRole('distributor') )
					<a href="javascript:;" class="small-box-footer openAdd" data-toggle="modal" data-target="#openAddModal" data-type="table_bank">@lang('app.gamebank') @lang('app.pay_in') <i class="fa fa-arrow-circle-right"></i></a>
					@endif
				</div>
			</div> --}}
			<!-- ./col -->
			{{-- <div class="col-lg-3 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-red">
					<div class="inner">
						<h3>{{ number_format( $stats['fish'], 2 ) }}</h3>
						<p>@lang('app.fish')</p>
					</div>
					<div class="icon">
						<i class="fa fa-line-chart"></i>
					</div>
					@if( auth()->user()->hasRole('distributor') )
					<a href="javascript:;" class="small-box-footer openAdd" data-toggle="modal" data-target="#openAddModal" data-type="fish">@lang('app.gamebank') @lang('app.pay_in') <i class="fa fa-arrow-circle-right"></i></a>
					@endif
				</div>
			</div> --}}
			<!-- ./col -->
			{{-- <div class="col-lg-3 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-red">
					<div class="inner">
						<h3>{{ number_format( $stats['bonus'], 2 ) }}</h3>
						<p>@lang('app.bonus')</p>
					</div>
					<div class="icon">
						<i class="fa fa-line-chart"></i>
					</div>
					@if( auth()->user()->hasRole('distributor') )
						<a href="javascript:;" class="small-box-footer openAdd" data-toggle="modal" data-target="#openAddModal" data-type="bonus">@lang('app.gamebank') @lang('app.pay_in') <i class="fa fa-arrow-circle-right"></i></a>
					@endif
				</div>
			</div> --}}
			<!-- ./col -->
			{{-- <div class="col-lg-3 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-light-blue">
					<div class="inner">
						<h3>{{ $stats['bank'] }}</h3>
						<p>@lang('app.total_banks')</p>
					</div>
					<div class="icon">
						<i class="fa fa-refresh"></i>
					</div>
				</div>
			</div> --}}
			<!-- ./col -->
		</div>
		@endpermission


		@if( Auth::user()->shop && Auth::user()->shop->pending )
			<div class="alert alert-warning">
				<h4>@lang('app.shop_is_creating')</h4>
				<p>@lang('app.games_will_be_added_in_few_minutes')</p>
			</div>
		@endif

		@if( !Auth::user()->shop || (Auth::user()->shop && !Auth::user()->shop->pending) )
		<form action="" id="games-form" method="GET">
			<div class="box box-danger collapsed-box games_show">
				<div class="box-header with-border">
					<h3 class="box-title">@lang('app.filter')</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
					</div>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>이름</label>
								<input type="text" class="form-control" name="search" value="{{ Request::get('search') }}" placeholder="게임이름">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>@lang('app.status')</label>
								{!! Form::select('view', $views, Request::get('view'), ['id' => 'view', 'class' => 'form-control']) !!}
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>@lang('app.device')</label>
								{!! Form::select('device', $devices, Request::get('device'), ['id' => 'device', 'class' => 'form-control']) !!}
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>@lang('app.category')</label>
								<select class="form-control select2" name="category[]" id="category" multiple="multiple" style="width: 100%;" data-placeholder="">
									<option value=""></option>
									@foreach ($categories as $key=>$category)
										<option value="{{ $category->id }}" {{ (count($savedCategory) && in_array($category->id, $savedCategory))? 'selected="selected"' : '' }}>{{ $category->title }}</option>
										@foreach ($category->inner as $inner)
											<option value="{{ $inner->id }}" {{ (count($savedCategory) && in_array($inner->id, $savedCategory))? 'selected="selected"' : '' }}>&nbsp;&nbsp;&nbsp;{{ $inner->title }}</option>
										@endforeach
									@endforeach
								</select>
							</div>
						</div>
					</div>

					{{-- <div class="row">

						<div class="col-md-4">
							<div class="form-group">
								<label>@lang('app.gamebank')</label>
								{!! Form::select('gamebank', ['' => '---'] + $emptyGame->gamebankNames, Request::get('gamebank'), ['id' => 'gamebank', 'class' => 'form-control']) !!}
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label>@lang('app.labels')</label>
								{!! Form::select('label', ['' => '---'] + $emptyGame->labels, Request::get('label'), ['id' => 'label', 'class' => 'form-control']) !!}
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label>@lang('app.jpg')</label>
								{!! Form::select('jpg', ['' => '---'] + $jpgs, Request::get('jpg'), ['id' => 'jpg', 'class' => 'form-control']) !!}
							</div>
						</div>
					</div> --}}
				</div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">
                        @lang('app.filter')
                    </button>
					<a href="?clear" class="btn btn-default">
						@lang('app.clear')
					</a>
                </div>
			</div>
		</form>

		<form action="{{ route('backend.game.mass') }}" method="POST" class="pb-2 mb-3 border-bottom-light">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">@lang('app.games')</h3>
					{{-- @permission('games.edit')
					<div class="pull-right box-tools">
						<input type="hidden" value="<?= csrf_token() ?>" name="_token">
						<button class="btn btn-block btn-primary btn-sm" type="submit">@lang('app.change')</button>
					</div>
					@endpermission --}}
				</div>
                    <div class="box-body">


                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
						<thead>
						<tr>
							<th>@lang('app.game')</th>
							@permission('games.in_out')
							<th>@lang('app.in')</th>
							<th>@lang('app.out')</th>
							<th>@lang('app.total')</th>
							@endpermission
							<th>베팅횟수</th>
							{{-- <th>@lang('app.denomination')</th> --}}
							{{-- <th>
								<label class="checkbox-container">
									<input type="checkbox" class="checkAll">
									<span class="checkmark"></span>
								</label>
							</th> --}}
						</tr>
						</thead>
						<tbody>
						@if (count($games))
							@foreach ($games as $game)
								@include('backend.games.partials.row')
							@endforeach
						@else
							<tr><td colspan="9">@lang('app.no_data')</td></tr>
						@endif
						</tbody>
						<thead>
						<tr>
							<th>@lang('app.game')</th>
							@permission('games.in_out')
							<th>@lang('app.in')</th>
							<th>@lang('app.out')</th>
							<th>@lang('app.total')</th>
							@endpermission
							<th>베팅횟수</th>
							{{-- <th>@lang('app.denomination')</th> --}}
							{{-- <th>
								<label class="checkbox-container">
									<input type="checkbox" class="checkAll">
									<span class="checkmark"></span>
								</label>
							</th> --}}
						</tr>
						</thead>
                            </table>
						</div>
						{{ $games->appends(Request::except('page'))->links() }}
                    </div>
			</div>
		</form>
		@endif


		<div class="modal fade" id="openAddModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<form action="" method="POST" id="gamebank_add">

						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title">환수금조절</h4>
						</div>

						<div class="modal-body">
							<div class="form-group">
								<input type="text" class="form-control" id="AddSum" name="summ" placeholder="@lang('app.sum')" required>
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<br>
									<button type="button" class="btn btn-default changeAddSum" data-value="10000">10000</button>
									<button type="button" class="btn btn-default changeAddSum" data-value="20000">20000</button>
									<button type="button" class="btn btn-default changeAddSum" data-value="30000">30000</button>
									<button type="button" class="btn btn-default changeAddSum" data-value="50000">50000</button>
									<button type="button" class="btn btn-default changeAddSum" data-value="100000">100000</button>
									<button type="button" class="btn btn-default changeAddSum" data-value="200000">200000</button>
									<button type="button" class="btn btn-default changeAddSum" data-value="300000">300000</button>
									<button type="button" class="btn btn-default changeAddSum" data-value="500000">500000</button>
							</div>
						</div>
						<div class="modal-footer" style="text-align: left;">
							<a href="" class="btn btn-warning openAddClear">환수금초기화</a>
							<button type="submit" class="btn btn-submit">금액추가</a>
						</div>
					</form>
				</div>
			</div>
		</div>

	</section>

@stop

@section('scripts')
	<script>
		$('.openAdd').click(function(event){
			var type = $(event.target).data('type');
			$('.openAddClear').attr('href', '{{ route('backend.game.gamebanks_clear') }}?type=' + type);
			$('#gamebank_add').attr('action', '{{ route('backend.game.gamebanks_add') }}?type=' + type)
		});
		$('.changeAddSum').click(function(event){
			$v = Number($('#AddSum').val());
			$('#AddSum').val($v + $(event.target).data('value'));
		});
	</script>
	<script>
		var table = $('#games-table').DataTable({
			orderCellsTop: true,
			dom: '<"toolbar">frtip',

		});

		$("#filter").detach().appendTo("div.toolbar");

		//$('#games-table').dataTable();
		$("#view").change(function () {
			$("#games-form").submit();
		});
		$("#device").change(function () {
			$("#games-form").submit();
		});
		$("#category").change(function () {
			$("#games-form").submit();
		});

		$('.checkAll').on('ifChecked', function(event){
			$('.minimal').iCheck('check');
		});

		$('.checkAll').on('ifUnchecked\t', function(event){
			$('.minimal').iCheck('uncheck');
		});

		$('.checkAll').click(function(event){
			if($(event.target).is(':checked') ){
				$('input[type=checkbox]').attr('checked', true);
			}else{
				$('input[type=checkbox]').attr('checked', false);
			}
		});

		$('.btn-box-tool').click(function(event){
			if( $('.games_show').hasClass('collapsed-box') ){
				$.cookie('games_show', '1');
			} else {
				$.removeCookie('games_show');
			}
		});

		if( $.cookie('games_show') ){
			$('.games_show').removeClass('collapsed-box');
			$('.games_show .btn-box-tool i').removeClass('fa-plus').addClass('fa-minus');
		}

		$("#shop_id").change(function () {
			$("#change-shop-form").submit();
		});

	</script>
@stop