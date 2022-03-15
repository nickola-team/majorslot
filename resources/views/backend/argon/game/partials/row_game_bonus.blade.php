<td>{{$bank->id}}</td>
<td>
	@if ($bank->game_id == 0)
		일반
	@else
		{{ $bank->game->title }}
	@endif
</td>
<td>{{ number_format($bank->bank,0) }}</td>
<td>
    @if ($bank->max_bank == 0)
        없음
    @else
    {{ number_format($bank->max_bank,0) }} 
    @endif
</td>