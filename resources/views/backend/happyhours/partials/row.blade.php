<tr>
	<td>{{ $happyhour->id }}</td>
	<td><a href="{{ route('backend.happyhour.edit', $happyhour->id) }}">{{ $happyhour->user?$happyhour->user->username:'unknown' }}</a></td>
	<td>{{ $happyhour->total_bank }}</td>
	<td>{{ $happyhour->current_bank }}</td>
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