<tr>
    <td>
		@permission('games.edit')
		<a href="{{ route('backend.game.edit', $game->id) }}">
		@endpermission

		{{ $game->title }}

		@permission('games.edit')
		</a>
		@endpermission
	</td>
	@permission('games.in_out')
	<td>{{ number_format($game->stat_in,2) }}</td>
	<td>{{ number_format($game->stat_out,2) }}</td>
	<td>
		@if(($game->stat_in - $game->stat_out) >= 0)
			<span class="text-green">
		@else
			<span class="text-red">
		@endif	
		{{ number_format($game->stat_in-$game->stat_out, 2) }}
		</span>
	</td>
	@endpermission
	<td>{{ number_format($game->bids) }}</td>
	{{-- <td>{{ $game->denomination }}</td> --}}
{{-- <td>

	<label class="checkbox-container">
		<input type="checkbox" name="checkbox[{{ $game->id }}]">
		<span class="checkmark"></span>
	</label> --}}
			<!--
        <input class="custom-control-input minimal" id="cb-[{{ $game->id }}]" name="checkbox[{{ $game->id }}]" type="checkbox">

			-->
{{-- </td> --}}
</tr>