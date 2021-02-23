<tr>
	
	<td>{{ $stat->shop->name }}</td>
	<td>{{ $stat->user->username }}</td>
	<td>
		@if ($stat->type == 'add')
			<span class="text-green">{{ number_format(abs($stat->sum),2) }}	</span>
		@endif
	</td>
	<td>
		@if ($stat->type != 'add')
			<span class="text-red">{{ number_format(abs($stat->sum),2) }}</span>
		@endif
	</td>
	@if($stat->requestInfo)
	<td> {{"[ " . $stat->requestInfo->bank_name . " ] ". $stat->requestInfo->account_no}} </td>
	<td>{{ $stat->requestInfo->recommender }}</td>
	@else
	<td></td>
	<td></td>
	@endif
	<td>{{ date(config('app.date_time_format'), strtotime($stat->date_time)) }}</td>
	@if(isset($show_shop) && $show_shop)
		@if($stat->shop)
			<td><a href="{{ route('backend.shop.edit', $stat->shop->id) }}">{{ $stat->shop->name }}</a></td>
		@endif
	@endif
</tr>