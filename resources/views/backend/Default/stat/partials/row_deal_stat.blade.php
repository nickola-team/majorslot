<tr>
    <td>{{ $stat->game }}</td>
	<td>{{ $stat->user->username }}</td>
	@if($stat->type == 'shop')
	<td>{{ $stat->shop->name }}</td>
	@else
	<td>{{ $stat->partner->username }}</td>
	@endif
	{{-- <td><span class="text-green">{{ number_format($stat->balance_before) }}</span></td>
	<td><span class="text-green">{{ number_format($stat->balance_after) }}</span></td> --}}
	<td>{{ number_format($stat->bet,2) }}</td>
	<td><span class="text-green">{{ number_format($stat->deal_profit  - $stat->mileage,2) }}</span></td>
	<td>{{ date(config('app.date_time_format'), strtotime($stat->date_time)) }}</td>
    @if(isset($show_shop) && $show_shop)
        @if($stat->shop)
            <td><a href="{{ route('backend.shop.edit', $stat->shop->id) }}">{{ $stat->shop->name }}</a></td>
        @else
            <td>@lang('app.no_shop')</td>
        @endif
    @endif
</tr>