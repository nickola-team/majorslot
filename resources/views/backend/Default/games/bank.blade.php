@extends('backend.Default.layouts.'.$layout.'.app')

@section('page-title', '매장환수금')
@section('page-heading', '매장환수금')

@section('content')

    <section class="content-header">
        @include('backend.Default.partials.messages')
    </section>

    <section class="content">

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
                        <th>슬롯환수금</th>
                        <th>테이블환수금</th>
                        <th>보너스환수금</th>
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
                        <th>슬롯환수금</th>
                        <th>테이블환수금</th>
                        <th>보너스환수금</th>
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
							<h4 class="modal-title">환수금조절</h4>
						</div>

						<div class="modal-body">
							<div class="form-group">
								<input type="text" class="form-control" id="AddSum" name="summ" placeholder="@lang('app.sum')" required>
                                <input type="hidden" name="shopid" value="">
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
            var shop = $(event.target).data('shop');
			$('.openAddClear').attr('href', '{{ route('backend.game.gamebanks_clear') }}?type=' + type + '&shop=' + shop);
			$('#gamebank_add').attr('action', '{{ route('backend.game.gamebanks_add') }}?type=' + type + '&shop=' + shop);
		});
		$('.changeAddSum').click(function(event){
			$v = Number($('#AddSum').val());
			$('#AddSum').val($v + $(event.target).data('value'));
		});
	</script>
@stop