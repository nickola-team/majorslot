<tr>        
    <td>
		<a href="{{ route('backend.user.list', ['shopname'=>$shop->name]) }}">{{ $shop->name }}</a>
	</td>
	<td>
	@if($shop->creator)
		@if( Auth::user()->hasRole(['admin', 'master', 'agent']) )
			<a href="{{ route('backend.user.edit', $shop->creator->id) }}" >{{ $shop->creator->username }}</a>
		@else	
		{{ $shop->creator->username }}
		@endif
	@endif
	</td>
	<td>
	@if($shop->creator && $shop->creator->referral)
		@if( Auth::user()->hasRole(['admin', 'master', 'agent']) )
			<a href="{{ route('backend.user.edit', $shop->creator->referral->id) }}" >{{ $shop->creator->referral->username }}</a>
		@else	
		{{ $shop->creator->referral->username }}
		@endif
	@endif
	</td>
	{{-- <td><a href="{{ route('frontend.jpstv', $shop->shop_id) }}" target="_blank">{{ $shop->shop_id }}</a></td> --}}
    <td>{{ number_format($shop->balance,2) }}</td>
	@if(auth()->user()->hasRole('admin')  && !Session::get('isCashier'))
	<td>{{ $shop->percent }}</td>
	@endif
	<td>{{ $shop->deal_percent }}</td>
	<td>{{ $shop->table_deal_percent }}</td>
	{{-- <td>{{ $shop->frontend }}</td>
	<td>{{ $shop->currency }}</td>
	<td>{{ $shop->orderby }}</td> --}}
	<td>
		@if($shop->is_blocked)
			<small><i class="fa fa-circle text-red"></i></small>
		@elseif($shop->pending)
			<small><i class="fa fa-circle text-yellow"></i></small>
		@else
			<small><i class="fa fa-circle text-green"></i></small>
		@endif
	</td>
	<td>
		@if( Auth::user()->hasRole(['admin', 'master', 'distributor']) && !Session::get('isCashier'))
		
		<a class="addPayment" href="#" data-toggle="modal" data-target="#openAddModal" data-id="{{ $shop->shop_id }}" >
		<button type="button" class="btn btn-block btn-success btn-xs"> 충전</button>
	    </a>
		@else
			<button type="button" class="btn btn-block btn-success disabled btn-xs"> 충전</button>
		@endif
	</td>
	<td>
		@if( Auth::user()->hasRole(['admin', 'master', 'distributor']) && !Session::get('isCashier'))
		<a class="outPayment" href="#" data-toggle="modal" data-target="#openOutModal" data-id="{{ $shop->shop_id }}" >
	    <button type="button" class="btn btn-block btn-danger btn-xs"> 환전</button>
		</a>
		@else
			<button type="button" class="btn btn-block btn-danger disabled btn-xs"> 환전</button>
		@endif
	</td>
</tr>