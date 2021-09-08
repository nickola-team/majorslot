<tr>
	<td>{{ $happyhour->id }}</td>
	<td><a href="{{ route($admurl.'.happyhour.edit', $happyhour->id) }}">{{ $happyhour->user?$happyhour->user->username:'unknown' }}</a></td>
	<td>{{ number_format($happyhour->total_bank,0) }}</td>
	<td>{{ number_format($happyhour->current_bank,0) }}</td>
	<td>{{ number_format($happyhour->over_bank,0) }}</td>
	<td>{{ ['비활성','메이저','그랜드'][$happyhour->jackpot] }}</td>
	<td>{{ \VanguardLTE\HappyHour::$values['time'][$happyhour->time] }}</td>
	<td>
		@if($happyhour->status == 0)
			차단
		@elseif($happyhour->status == 1)
			활성
		@else
			완료
		@endif
	</td>

</tr>