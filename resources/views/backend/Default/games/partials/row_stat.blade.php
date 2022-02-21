<tr>
    <td>
	<?php
		if (auth()->user()->hasRole('admin'))
		{
			$game = $stat->game;
		}
		else
		{
			$game = preg_replace('/PM/', '_pp', $stat->game);
			$game = preg_replace('/HBN/', '_HBN', $game);
			$game = preg_replace('/CQ9/', '_cq9', $game);
			$game = explode(' ', $game)[0];
		}

		$game = preg_replace('/^_/', '', $game);
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
	<td>
	@if ($stat->balance<0)
		<a href="javascript:void(0);"><span class="text-red getbalance" data-id="{{$stat->id}}">보기</span> </a>
	@else
		<span class="text-green">{{ number_format($stat->balance,0) }}</span>
	@endif
	</td>
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
            <td><a href="{{ route($admurl.'.shop.edit', $stat->shop->id) }}">{{ $stat->shop->name }}</a></td>
        @else
            <td>@lang('app.no_shop')</td>
        @endif
    @endif
</tr>