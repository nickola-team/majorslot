<tr>
	
	<td>
	@if ($stat->shop)
		{{ $stat->shop->name }}
	@else
		unknown
	@endif
	</td>
	<td>
	@if ($stat->user)
		{{ $stat->user->username }}
		{{ $stat->user ? $stat->user->username : 'unknown'  }} [ 
		@foreach(['7' => 'app.admin', '6' => 'app.master','5' => 'app.agent', '4' => 'app.distributor', 'shop' => 'app.shop', '3' => 'app.manager', '2' => 'app.cashier'] AS $role_id=>$role_name)
			@if($stat->user && $role_id == $stat->user->role_id)
				@lang($role_name)
			@endif
		@endforeach
		]
	@else
		unknown
	@endif
	</td>
	<td>
		@if ($stat->type == 'add')
			<span class="text-green">{{ number_format(abs($stat->sum),2) }}	</span>
		@endif
	</td>
	<td>
		@if ($stat->type == 'out')
			<span class="text-red">{{ number_format(abs($stat->sum),2) }}</span>
		@endif
	</td>
	<td>
		@if ($stat->type == 'deal_out')
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