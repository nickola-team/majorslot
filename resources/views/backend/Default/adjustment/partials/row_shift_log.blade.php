<tr>
	@if($shift_log->type == 'partner')
	<td>{{ $shift_log->user->username }}</td>
	@else
	<td>{{ $shift_log->shop->name }}</td>
	@endif
	<td>{{ $shift_log->start_date }}</td>
	<td>{{ $shift_log->end_date }}</td>
	<td>{{ number_format($shift_log->old_total,0) }}</td>
	<td>{{ number_format($shift_log->balance_in,0) }}</td>
	<td>{{ number_format($shift_log->balance_out,0) }}</td>
	<td>{{ number_format($adjustment->open_shift->money_in,0) }}</td>
	<td>{{ number_format($adjustment->open_shift->money_out,0) }}</td>
	<td>{{ number_format($shift_log->deal_profit,0)}}</td>
	<td>{{ number_format($shift_log->mileage,0)}}</td>
	<td>{{ number_format($shift_log->convert_deal,0) }}</td>
	<td>{{ number_format($shift_log->deal_profit - $shift_log->mileage - $shift_log->convert_deal,0) }}</td>
	<td>{{ number_format($shift_log->balance,0) }}</td>
	<td>{{ number_format($shift_log->last_returns,0) }}</td>

</tr>