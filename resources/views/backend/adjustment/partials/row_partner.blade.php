<tr>
	@if(isset($adjustment['id']))
	<td><a href="{{ route('backend.adjustment_partner', 'parent='.$adjustment['id']) }}">{{ $adjustment['name'] }}</a></td>
	@else
	<td>{{ $adjustment['name'] }}</td>
	@endif
	<td>{{ number_format($adjustment['totalin'],2) }}</td>
	<td>{{ number_format($adjustment['totalout'],2) }}</td>
	<td>{{ number_format($adjustment['totalbet'],2)}}</td>
	<td>{{ number_format($adjustment['totalwin'],2)}}</td>
	<td>{{ number_format($adjustment['total_deal'],2)}}</td>
	<td>{{ number_format($adjustment['total_mileage'],2) }}</td>
	<td>{{ number_format($adjustment['total_deal'] - $adjustment['total_mileage'],2) }}</td>
	<td>{{ number_format($adjustment['balance'],2) }}</td>
	@if(auth()->user()->hasRole('admin'))
	<td>{{ number_format($adjustment['profit'],2) }}</td>
	@endif
</tr>