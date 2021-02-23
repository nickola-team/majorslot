<tr>
	@if($adjustment->partner instanceof \VanguardLTE\User)
	<td><a href="{{ route('backend.adjustment_partner', 'parent='.$adjustment->partner->id) }}">{{ $adjustment->partner->username }}</a></td>
	@else
	<td>{{ $adjustment->partner->name }}</td>
	@endif
	<td>{{ number_format($adjustment->total_in,2) }}</td>
	<td>{{ number_format($adjustment->total_out,2) }}</td>
	<td>{{ number_format($adjustment->total_bet,2)}}</td>
	<td>{{ number_format($adjustment->total_win,2)}}</td>
	<td>{{ number_format($adjustment->total_deal,2)}}</td>
	<td>{{ number_format($adjustment->total_mileage,2) }}</td>
	<td>{{ number_format($adjustment->total_deal - $adjustment->total_mileage,2) }}</td>
	<td>{{ number_format($adjustment->partner->balance,2) }}</td>
</tr>