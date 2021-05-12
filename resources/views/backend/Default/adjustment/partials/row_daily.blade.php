<tr>
	@if($adjustment->user->hasRole('manager'))
	<td>{{ $adjustment->user->username }}</td>
	@else
	<td><a href="{{ route('backend.adjustment_daily', 'parent='.$adjustment->user_id) }}">{{ $adjustment->user->username }}</a></td>
	@endif
	<td>{{ $adjustment->date}}</td>
	<td>{{ number_format($adjustment->totalin,2) }}</td>
	<td>{{ number_format($adjustment->totalout,2) }}</td>
	@if(auth()->user()->hasRole(['admin', 'master']))
		<td>{{ number_format($adjustment->totalin - $adjustment->totalout,2) }}</td>
	@endif
	<td>{{ number_format(abs($adjustment->total_deal-$adjustment->total_mileage),2)}}</td>
	<td>{{ number_format($adjustment->dealout,2) }}</td>
	<td>{{ number_format($adjustment->moneyin,2) }}</td>
	<td>{{ number_format($adjustment->moneyout,2) }}</td>
	<td>{{ number_format($adjustment->totalbet,2)}}</td>
	<td>{{ number_format($adjustment->totalwin,2)}}</td>
	<td>{{ number_format($adjustment->totalbet - $adjustment->totalwin,2) }}</td>
	@if(auth()->user()->hasRole(['admin', 'master']))
	<td>{{ number_format($adjustment->totalbet - $adjustment->totalwin - abs($adjustment->total_deal - $adjustment->total_mileage),2) }}</td>
	@endif
</tr>