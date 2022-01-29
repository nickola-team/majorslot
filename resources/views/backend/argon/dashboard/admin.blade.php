@extends('layouts.app')
@section('page-title',  '대시보드' )
@section('content-right-header')
<div class="col-lg-6 col-5 text-right">
    <a href="{{route('partners.create')}}" class="btn btn-neutral">{{__('New')}}</a>
</div>
@endsection
@section('content')
<div class="container-fluid mt--8">
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
                            <label for="name" class="form-control-label">{{__('Name')}}</label>
                            <input class="form-control" type="text" value="{{ Request::get('name') }}" id="name" name="name">
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
        <h3 class="mb-0">{{__(__(\App\Models\User::ROLE_NAMES[$role_id]))}} {{ __('List')}}</h3>
    </div>
    <div class="table-responsive">
        <table class="table align-items-center table-flush">
        <thead class="thead-light">
            <tr>
            <th scope="col" class="sort" data-sort="name">{{__('Name')}}</th>
            <th scope="col" class="sort" data-sort="budget">{{__('Email')}}</th>
            <th scope="col" class="sort" data-sort="budget">{{__('Parent')}}</th>
            @if ($role_id == \App\Models\User::ROLE_OPERATOR)
            <th scope="col" class="sort" data-sort="budget">{{__('RTP')}}</th>
            @endif
            <th scope="col" class="sort" data-sort="budget">{{__('Balance')}}</th>
            <th scope="col" class="sort" data-sort="budget">
            @if ($role_id == \App\Models\User::ROLE_OPERATOR)
                {{__('PlayerSum')}}
            @else
                {{__('ChildBalanceSum')}}
            @endif
            </th>
            <th scope="col" class="sort" data-sort="budget">{{__('Rate')}}</th>
            <th scope="col" class="sort" data-sort="status">{{__('CreatedAt')}}</th>
            <th scope="col" class="sort" data-sort="status">{{__('Status')}}</th>
            <th scope="col"></th>
            </tr>
        </thead>
        <tbody class="list">
            @if (count($partners) > 0)
                @foreach ($partners as $operator)
                    <tr>
                        @include('partners.partials.row')
                    </tr>
                @endforeach
            @else
                <tr><td colspan="6">{{__('No Data')}}</td></tr>
            @endif
        </tbody>
        </table>
    </div>
    <!-- Card footer -->
    <div class="card-footer py-4">
        {{ $partners->withQueryString()->links('vendor.pagination.argon') }}
    </div>
    </div>
</div>
</div>
<!-- Add/Out Balance Modal -->


<div class="modal fade" id="modal-addbalance" tabindex="-1" role="dialog" aria-labelledby="modal-addbalance" aria-hidden="true">
    <form action="{{ route('partners.balance.update') }}" method="POST">
    @csrf
        <input type="hidden" name="type" value="add">
        <input type="hidden" id="AddId" name="user_id">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h2 class="modal-title" id="exampleModalLabel">{{__('AddBalance')}}</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{__('Please input balance to add')}}</label>
                    <input class="form-control" type="text" value="" id="AddSum" name="summ">
                    <br>
                    <button type="button" class="btn btn-default changeAddSum" data-value="10000000">{{__('10M')}}</button>
                    <button type="button" class="btn btn-default changeAddSum" data-value="30000000">{{__('30M')}}</button>
                    <button type="button" class="btn btn-default changeAddSum" data-value="50000000">{{__('50M')}}</button>
                    <button type="button" class="btn btn-default changeAddSum" data-value="100000000">{{__('100M')}}</button>
                    <button type="button" class="btn btn-primary changeAddSum" data-value="0">{{__('Reset')}}</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                <button type="submit" class="btn btn-primary">{{__('AddBalance')}}</button>
            </div>
        </div>
    </div>
    </form>
</div>

<div class="modal fade" id="modal-outbalance" tabindex="-1" role="dialog" aria-labelledby="modal-addbalance" aria-hidden="true">
    <form action="{{ route('partners.balance.update') }}" method="POST">
        @csrf
        <input type="hidden" name="type" value="out">
        <input type="hidden" id="OutId" name="user_id">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h2 class="modal-title" id="exampleModalLabel">{{__('OutBalance')}}</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{__('Please input balance to out')}}</label>
                    <input class="form-control" type="text" value="" id="OutSum" name="summ">
                    <br>
                    <button type="button" class="btn btn-default changeOutSum" data-value="10000000">{{__('10M')}}</button>
                    <button type="button" class="btn btn-default changeOutSum" data-value="30000000">{{__('30M')}}</button>
                    <button type="button" class="btn btn-default changeOutSum" data-value="50000000">{{__('50M')}}</button>
                    <button type="button" class="btn btn-default changeOutSum" data-value="100000000">{{__('100M')}}</button>
                    <button type="button" class="btn btn-primary changeOutSum" data-value="0">{{__('Reset')}}</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                <button type="submit" class="btn btn-danger">{{__('OutBalance')}}</button>
            </div>
        </div>
    </div>
    </form>
</div> 

<!-- delete Modal -->
<div class="modal fade" id="modal-confirm" tabindex="-1" role="dialog" aria-labelledby="modal-confirm" aria-hidden="true">
    <form action="{{ route('partners.status.delete') }}" method="POST">
        @csrf
    <input type="hidden" id="delId" name="user_id">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{__('Confirm')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {{__('Do you wanna delete this user?')}}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
        <button type="submit" class="btn btn-primary">{{__('Yes')}}</button>
      </div>
    </div>
  </div>
</form>
</div>
@stop

@push('js')
<script>
    $('.addPayment').click(function(event){
			if( $(event.target).is('.newPayment') ){
				var id = $(event.target).attr('data-id');
			}else{
				var id = $(event.target).parents('.newPayment').attr('data-id');
			}
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
    $('.outPayment').click(function(event){
			if( $(event.target).is('.newPayment') ){
				var id = $(event.target).attr('data-id');
			}else{
				var id = $(event.target).parents('.newPayment').attr('data-id');
			}
			$('#OutId').val(id);
		});
    $('.deleteUser').click(function(event){
			if( $(event.target).is('.newPayment') ){
				var id = $(event.target).attr('data-id');
			}else{
				var id = $(event.target).parents('.newPayment').attr('data-id');
			}
			$('#delId').val(id);
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
</script>
@endpush