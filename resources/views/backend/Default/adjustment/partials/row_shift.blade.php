<tr>
	@if($adjustment->partner instanceof \VanguardLTE\User)
	<td><a href="{{ route('backend.adjustment_shift', 'parent='.$adjustment->partner->id) }}">{{ $adjustment->partner->username }}</a></td>
	@else
	<td>{{ $adjustment->partner->name }}</td>
	@endif
	@if($adjustment->open_shift)
	<td>{{ $adjustment->open_shift->start_date }}</td>
	<td>{{ $adjustment->open_shift->end_date }}</td>
	<td>{{ number_format($adjustment->open_shift->old_total,2) }}</td>
	<td>{{ number_format($adjustment->open_shift->balance_in,2) }}</td>
	<td>{{ number_format($adjustment->open_shift->balance_out,2) }}</td>
	<td>{{ number_format($adjustment->open_shift->money_in,2) }}</td>
	<td>{{ number_format($adjustment->open_shift->money_out,2) }}</td>
	@if($adjustment->partner instanceof \VanguardLTE\Shop)
	<td>{{ number_format($adjustment->total_in,2) }}</td>
	@endif
	<td>{{ number_format($adjustment->open_shift->deal_profit,2)}}</td>
	<td>{{ number_format($adjustment->open_shift->mileage,2)}}</td>
	<td>{{ number_format($adjustment->open_shift->convert_deal,2) }}</td>
	<td>{{ number_format($adjustment->open_shift->deal_profit - $adjustment->open_shift->mileage - $adjustment->open_shift->convert_deal,2) }}</td>
	<td>{{ number_format($adjustment->partner->balance+$adjustment->total_in,2) }}</td>
	@else
		<td colspan =12>정산을 시작하지 않았습니다.</td>
	@endif
	
	<td>
	@if(($adjustment->partner instanceof \VanguardLTE\User && $adjustment->partner->id == auth()->user()->id) || 
		($adjustment->partner instanceof \VanguardLTE\Shop && auth()->user()->hasRole('manager') && $adjustment->partner->id == auth()->user()->shop_id))
		@if($adjustment->open_shift)
		<a class="newPayment outPayment" href="#" data-toggle="modal" data-target="#openOutModal" data-id="{{ number_format($adjustment->partner->balance,2,'.','')}}" data-id1="{{ number_format($adjustment->partner->deal_balance - $adjustment->partner->mileage,2,'.','') }}">
			<button type="button" class="btn btn-block btn-success btn-xs" disabled>정산</button>
		</a>
		@else
		<a href="{{ route('backend.adjustment_create_shift') }}">
			<button type="button" class="btn btn-block btn-success btn-xs">정산시작</button>
		</a>
		@endif
	@endif
	</td>
</tr>