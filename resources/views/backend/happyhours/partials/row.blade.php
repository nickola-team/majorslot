<tr>
	<td>{{ $happyhour->id }}</td>
	<td><a href="{{ route('backend.happyhour.edit', $happyhour->id) }}">{{ $happyhour->user?$happyhour->user->username:'unknown' }}</a></td>
	<td>{{ number_format($happyhour->total_bank,2) }}</td>
	<td>{{ number_format($happyhour->current_bank,2) }}</td>
	<td>{{ number_format($happyhour->over_bank,2) }}</td>
	<td>{{ ($happyhour->jackpot==1)?'활성':'비활성' }}</td>
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