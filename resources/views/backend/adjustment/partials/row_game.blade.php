<tr>
	<td>{{ $adjustment->game->name }}</td>
	<td>{{ $adjustment->category_names }}</td>
	<td>{{ number_format($adjustment->total_bet,2) }}</td>
	<td>{{ number_format($adjustment->total_win,2)}}</td>
	@if(auth()->user()->hasRole('admin'))
	<td>{{number_format($adjustment->total_percent,2)}}</td>
	<td>{{number_format($adjustment->total_percent_jps+$adjustment->total_percent_jpg,2)}}</td>
	<td>{{ number_format($adjustment->total_profit,2)}}</td>
	@endif
	<td>{{ number_format($adjustment->total_bet_count)}}</td>
	<td>{{ number_format($adjustment->total_deal,2)}}</td>
	<td>{{ number_format($adjustment->total_mileage,2)}}</td>
	@if(auth()->user()->hasRole('admin'))
	<td>{{ number_format($adjustment->total_real_profit,2)}}</td>
	@elseif(auth()->user()->hasRole(['agent', 'distributor', 'manager']))
	<td>{{ number_format($adjustment->total_deal - $adjustment->total_mileage,2)}}</td>
	@endif
</tr>