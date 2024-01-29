@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', '매장환수금')
@section('page-heading', '매장환수금')

@section('content')

    <section class="content-header">
        @include('backend.Default.partials.messages')
    </section>

    <section class="content">
		<form action="{{ route($admurl.'.game.gamebanks_setting') }}" id="games-form" method="POST">
		<div class="box box-danger">
			<div class="box-header with-border">
				<h3 class="box-title">설정</h3>
			</div>
			<div class="box-body">
					<div class="col-md-3">
						<div class="form-group">
							<label>최소 슬롯환수금</label>
							<input type="text" class="form-control" name="minslot" value="{{ $minslot?$minslot->value:'' }}" placeholder="">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>최대 슬롯환수금</label>
							<input type="text" class="form-control" name="maxslot" value="{{ $maxslot?$maxslot->value:'' }}" placeholder="">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>최소 보너스환수금</label>
							<input type="text" class="form-control" name="minbonus" value="{{ $minbonus?$minbonus->value:'' }}" placeholder="">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>최대 보너스환수금</label>
							<input type="text" class="form-control" name="maxbonus" value="{{ $maxbonus?$maxbonus->value:'' }}" placeholder="">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>리셋주기</label>
							{!! Form::select('reset_bank',
								['1시간','2시간','6시간','12시간','매일'], $reset_bank?$reset_bank->value:0, ['class' => 'form-control', 'style' => 'width: 100%;', 'id' => 'reset_bank']) !!}
						</div>
					</div>
			</div>
			<div class="box-footer">
				<button type="submit" class="btn btn-primary">
					설정
				</button>
			</div>
		</div>
		</form>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">매장환수금</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
						<thead>
						<tr>
                        <th>매장이름</th>
                        <th>환수율%</th>
                        <th>슬롯환수금 &nbsp;&nbsp;&nbsp; <a href="javascript:;" class="small-box-footer openAdd" data-toggle="modal" data-target="#openAddModal" data-type="slots" data-shop="{{ 0 }}">일괄수정 <i class="fa fa-arrow-circle-right"></i></a></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if (count($gamebank))
							@foreach ($gamebank as $bank)
								@include('backend.Default.games.partials.row_bank')
							@endforeach
						@else
							<tr><td colspan="5">@lang('app.no_data')</td></tr>
						@endif
                        </tbody>
                        <thead>
						<tr>
                        <th>매장이름</th>
                        <th>환수율%</th>
						<th>슬롯환수금 &nbsp;&nbsp;&nbsp; <a href="javascript:;" class="small-box-footer openAdd" data-toggle="modal" data-target="#openAddModal" data-type="slots" data-shop="{{ 0 }}">일괄수정 <i class="fa fa-arrow-circle-right"></i></a></th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

		<div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">보너스환수금</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
						<thead>
						<tr>
							<th>본사이름</th>
							<th>게임갯수</th>
							<th>환수금 합계</th>
                        </tr>
                        </thead>
                        <tbody>
						@if (count($bonusbank))
							@foreach ($bonusbank as $bank)
								@include('backend.Default.games.partials.row_bank_b')
							@endforeach
						@else
							<tr><td colspan="4">@lang('app.no_data')</td></tr>
						@endif
                        </tbody>
                        <thead>
						<tr>
							<th>본사이름</th>
							<th>게임갯수</th>
							<th>환수금 합계</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="openAddModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<form action="" method="POST" id="gamebank_add">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title">환수금수정</h4>
						</div>

						<div class="modal-body">
							<div class="form-group">
								<input type="text" class="form-control" id="AddSum" name="summ" placeholder="@lang('app.sum')" required>
                                <input type="hidden" id="act" name="act" value="">
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
							<button type="button" class="btn btn-success openAddButton">충전</button>
							<button type="button" class="btn btn-danger openOutButton">환전</button>
							<a href="" class="btn btn-warning openAddClear">모두환전</a>
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
            var shop = $(event.target).data('shop');
			$('.openAddClear').attr('href', '{{ route($admurl.'.game.gamebanks_clear') }}?type=' + type + '&shop=' + shop);
			$('#gamebank_add').attr('action', '{{ route($admurl.'.game.gamebanks_add') }}?type=' + type + '&shop=' + shop);
		});
		$('.openAddButton').click(function(event){
			$('#act').val('add');
			$('#gamebank_add').submit();
		});
		$('.openOutButton').click(function(event){
			$('#act').val('out');
			$('#gamebank_add').submit();
		});
		$('.changeAddSum').click(function(event){
			$v = Number($('#AddSum').val());
			$('#AddSum').val($v + $(event.target).data('value'));
		});
	</script>
@stop