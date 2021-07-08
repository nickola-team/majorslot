<tr>
    <td>
	<?php
		$game = preg_replace('/PM/', '_pp', $stat->game);
		$game = explode(' ', $game)[0];
	?>
	{{$game}}

	</td>
	<td>
	@if ($stat->user)
	{{ $stat->user->username }}
	@else
	삭제된 회원 - {{$stat->user_id}}
	@endif
	</td>
	<td><span class="text-green">{{ number_format($stat->balance,0) }}</span></td>
	<td>{{ number_format($stat->bet,0) }}</td>
	<td>{{ number_format($stat->win,0) }}</td>
	{{-- <td>{{ $stat->percent }}</td>
	<td>{{ $stat->percent_jps }}</td>
	<td>{{ $stat->percent_jpg }}</td>
	@if(auth()->user()->hasRole('admin'))
	<td>{{ $stat->profit }}</td>
	@endif
	<td>{{ $stat->denomination }}</td> --}}
	<td>{{ date(config('app.date_time_format'), strtotime($stat->date_time)) }}</td>
    @if(isset($show_shop) && $show_shop)
        @if($stat->shop)
            <td><a href="{{ route('backend.shop.edit', $stat->shop->id) }}">{{ $stat->shop->name }}</a></td>
        @else
            <td>@lang('app.no_shop')</td>
        @endif
    @endif
</tr>