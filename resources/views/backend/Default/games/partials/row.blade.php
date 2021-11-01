<tr>
    <td>
		@permission('games.edit')
		<a href="{{ route($admurl.'.game.edit', $game->id) }}">
		@endpermission

		{{ $game->title }}

		@permission('games.edit')
		</a>
		@endpermission
	</td>
{{--
	@permission('games.in_out')
	<td>{{ number_format($game->stat_in,0) }}</td>
	<td>{{ number_format($game->stat_out,0) }}</td>
	<td>
		@if(($game->stat_in - $game->stat_out) >= 0)
			<span class="text-green">
		@else
			<span class="text-red">
		@endif	
		{{ number_format($game->stat_in-$game->stat_out, 0) }}
		</span>
	</td>
	@endpermission
	<td>{{ number_format($game->bids) }}</td>
--}}
	<td>
		@if($game->view == 1)
			<span class="text-green"> 활성
		@else
			<span class="text-red"> 비활성
		@endif	
		</span>
	</td>
	<td>
		@if($game->view == 1)
			<a href="{{route($admurl.'.game.show', $game->id) . '?view=0&comaster='. Request::get('comaster')}}">
			<button type="button" class="btn btn-block btn-danger btn-xs">비활성</button>
		@else
		<a href="{{route($admurl.'.game.show', $game->id) . '?view=1&comaster='. Request::get('comaster')}}">
			<button type="button" class="btn btn-block btn-success btn-xs">활성</button>
		@endif	
			</a>
	</td>
</tr>