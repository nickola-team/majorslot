@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', trans('app.games'))
@section('page-heading', trans('app.games'))

@section('content')

	<section class="content-header">
		@include('backend.Default.partials.messages')
	</section>

	<section class="content">
		<form action="" id="games-form" method="GET">
			<div class="box box-danger collapsed-box games_show">
				<div class="box-header with-border">
					<h3 class="box-title">@lang('app.filter')</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
					</div>
				</div>
				<div class="box-body">
						<div class="col-md-6">
							<div class="form-group">
								<label>총본사</label>
								{!! Form::select('comaster', ['0'=>'전체']+$comasters, Request::get('comaster'), ['id' => 'comaster', 'class' => 'form-control']) !!}
							</div>
						</div>
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
								<label>@lang('app.category')</label>
								<select class="form-control select2" name="category[]" id="category" multiple="multiple" style="width: 100%;" data-placeholder="">
									<option value=""></option>
									@foreach ($categories as $key=>$category)
										<option value="{{ $category->id }}" {{ (count($savedCategory) && in_array($category->id, $savedCategory))? 'selected="selected"' : '' }}>{{ $category->trans->trans_title }}</option>
										@foreach ($category->inner as $inner)
											<option value="{{ $inner->id }}" {{ (count($savedCategory) && in_array($inner->id, $savedCategory))? 'selected="selected"' : '' }}>&nbsp;&nbsp;&nbsp;{{ $inner->trans->trans_title}}</option>
										@endforeach
									@endforeach
								</select>
							</div>
						</div>
						

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

		<form action="{{ route($admurl.'.game.mass') }}" method="POST" class="pb-2 mb-3 border-bottom-light">
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
{{--							<th>배팅금</th>
							<th>당첨금</th>
							<th>죽은금액</th>
							<th>베팅횟수</th> --}}
							<th>상태</th>
							<th>===</th>
						</tr>
						</thead>
						<tbody>
						@if (count($games))
							@foreach ($games as $game)
								@include('backend.Default.games.partials.row')
							@endforeach
						@else
							<tr><td colspan="6">@lang('app.no_data')</td></tr>
						@endif
						</tbody>
						<thead>
						<tr>
						<th>@lang('app.game')</th>
{{--						<th>배팅금</th>
							<th>당첨금</th>
							<th>죽은금액</th>
							<th>베팅횟수</th> --}}
							<th>상태</th>
							<th>===</th>
						</tr>
						</thead>
                            </table>
						</div>
						{{ $games->appends(Request::except('page'))->links() }}
                    </div>
			</div>
		</form>

	</section>

@stop

@section('scripts')
	<script>
		$('.openAdd').click(function(event){
			var type = $(event.target).data('type');
			$('.openAddClear').attr('href', '{{ route($admurl.'.game.gamebanks_clear') }}?type=' + type);
			$('#gamebank_add').attr('action', '{{ route($admurl.'.game.gamebanks_add') }}?type=' + type)
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