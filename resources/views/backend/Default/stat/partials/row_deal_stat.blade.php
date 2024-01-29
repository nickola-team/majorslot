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
	@if($stat->type == 'shop')
	<td>{{ $stat->shop->name }}</td>
	@else
	<td>
	@if ($stat->partner)
		{{ $stat->partner->username }}
	@else
		삭제된 파트너 - {{$stat->partner_id}}
	@endif
	</td>
	@endif
	<td>{{ number_format($stat->bet,2) }}</td>
	<td>{{ number_format($stat->win,2) }}</td>
	<td><span class="text-green">{{ number_format($stat->deal_profit  - $stat->mileage,2) }}</span></td>
	@if (auth()->user()->hasRole('admin')  || (auth()->user()->hasRole(['master','agent', 'distributor']) && auth()->user()->ggr_percent > 0) 
		|| (auth()->user()->hasRole('manager') && auth()->user()->shop->ggr_percent > 0))
	<td><span class="text-green">{{ number_format($stat->ggr_profit  - $stat->ggr_mileage - ($stat->deal_profit  - $stat->mileage),2) }}</span></td>
	@endif
	<td>{{ date(config('app.date_time_format'), strtotime($stat->date_time)) }}</td>
    @if(isset($show_shop) && $show_shop)
        @if($stat->shop)
            <td><a href="{{ route($admurl.'.shop.edit', $stat->shop->id) }}">{{ $stat->shop->name }}</a></td>
        @else
            <td>@lang('app.no_shop')</td>
        @endif
    @endif
</tr>