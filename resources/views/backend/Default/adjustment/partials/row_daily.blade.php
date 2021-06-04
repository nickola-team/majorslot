<tr>
	@if($adjustment->user->hasRole('manager'))
	<td>{{ $adjustment->user->username }}</td>
	@else
	<td><a href="{{ route('backend.adjustment_daily', 'parent='.$adjustment->user_id) }}">{{ $adjustment->user->username }}</a></td>
	@endif
	<td>{{ $type=='daily'?date('Y-m-d',strtotime($adjustment->date)):date('Y-m',strtotime($adjustment->date))}}</td>
	<td>{{ number_format($adjustment->totalin,0) }}</td>
	<td>{{ number_format($adjustment->totalout,0) }}</td>
	@if(auth()->user()->hasRole(['admin', 'master']))
		<td>{{ number_format($adjustment->totalin - $adjustment->totalout,0) }}</td>
	@endif
	<td>{{ number_format($adjustment->total_deal-$adjustment->total_mileage,0)}}</td>
	<td>{{ number_format($adjustment->dealout,0) }}</td>
	<td>{{ number_format($adjustment->moneyin,0) }}</td>
	<td>{{ number_format($adjustment->moneyout,0) }}</td>
	<td>{{ number_format($adjustment->totalbet,0)}}</td>
	<td>{{ number_format($adjustment->totalwin,0)}}</td>
	<td>{{ number_format($adjustment->totalbet - $adjustment->totalwin,0) }}</td>
	@if(auth()->user()->hasRole(['admin', 'master']))
	<td>{{ number_format($adjustment->totalbet - $adjustment->totalwin - abs($adjustment->total_deal - $adjustment->total_mileage),0) }}</td>
	@endif
</tr>