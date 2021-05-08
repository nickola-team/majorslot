@foreach ($adjustment['games'] as $game)
	@if ($loop->index > 0)
	<tr>
	@endif
	<td style="color:{{ $adjustment['category']?'black':'red' }};">{{ $game['name'] }}</td>
	<td style="color:{{ $adjustment['category']?'black':'red' }};">{{ number_format($game['total_bet'],2) }}</td>
	<td style="color:{{ $adjustment['category']?'black':'red' }};">{{ number_format($game['total_win'],2)}}</td>
	<td style="color:{{ $adjustment['category']?'black':'red' }};">{{ number_format($game['total_bet'] - $game['total_win'],2)}}</td>
	<td style="color:{{ $adjustment['category']?'black':'red' }};">{{ number_format($game['total_bet_count'])}}</td>
	@if(auth()->user()->hasRole('admin'))
	<td style="color:{{ $adjustment['category']?'black':'red' }};">0</td>
	<td style="color:{{ $adjustment['category']?'black':'red' }};">{{ number_format($game['total_deal'],2)}}</td>
	<td style="color:{{ $adjustment['category']?'black':'red' }};">{{ number_format(-$game['total_deal'],2)}}</td>

	@else
	<td style="color:{{ $adjustment['category']?'black':'red' }};">{{ number_format($game['total_deal'],2)}}</td>
	<td style="color:{{ $adjustment['category']?'black':'red' }};">{{ number_format($game['total_mileage'],2)}}</td>
	<td style="color:{{ $adjustment['category']?'black':'red' }};">{{ number_format($game['total_deal'] - $game['total_mileage'],2)}}</td>

	@endif
	@if ($loop->index > 0)
	</tr>
	@endif
@endforeach