<tr>
<td>
@if ($partner==0)	
	<a href="{{ route('backend.statistics', ['user' => $stat->user->username])  }}">

		{{ $stat->user->username }}
@else
	<a href="{{ route('backend.statistics_partner', ['user' => $stat->user->username])  }}">

	{{ $stat->user->username }} [ 
	@foreach(['7' => 'app.admin', '6' => 'app.master','5' => 'app.agent', '4' => 'app.distributor', 'shop' => 'app.shop', '3' => 'app.manager', '2' => 'app.cashier'] AS $role_id=>$role_name)
		@if($role_id == $stat->user->role_id)
			@lang($role_name)
		@endif
    @endforeach
	]
@endif
	</a>
</td>
<td>

	{{ $stat->admin ? $stat->admin->username : $stat->system  }} [ 
	@foreach(['7' => 'app.admin', '6' => 'app.master','5' => 'app.agent', '4' => 'app.distributor', 'shop' => 'app.shop', '3' => 'app.manager', '2' => 'app.cashier'] AS $role_id=>$role_name)
		@if($stat->admin && $role_id == $stat->admin->role_id)
			@lang($role_name)
		@endif
    @endforeach
	]

</td>

@if (auth()->user()->hasRole(['admin', 'master']))
<td>
	{{number_format($stat->balance,0)}}
	</td>
@endif
<td>
{{number_format($stat->old,0)}}
</td>
<td>
{{number_format($stat->new,0)}}
</td>
<td>
@if ($stat->type == 'add')
	<span class="text-green">{{ number_format(abs($stat->summ),0) }}</span>
@endif
</td>
<td>
	@if ($stat->type == 'out')
		<span class="text-red">{{ number_format(abs($stat->summ),0) }}</span>
	@endif
</td>
@if ($partner==1)
<td>
	@if ($stat->type == 'deal_out')
		<span class="text-red">{{ number_format(abs($stat->summ),0) }}</span>
	@endif
</td>
@if($stat->requestInfo)
<td> {{"[ " . $stat->requestInfo->bank_name . " ] ". $stat->requestInfo->account_no}} </td>
<td>{{ $stat->requestInfo->recommender }}</td>
@else
<td></td>
<td></td>
@endif
@endif
<td>{{ $stat->created_at->format(config('app.date_time_format')) }}</td>
	@if(isset($show_shop) && $show_shop)
		@if($stat->shop)
			<td><a href="{{ route('backend.shop.edit', $stat->shop->id) }}">{{ $stat->shop->name }}</a></td>
			@else
			<td>@lang('app.no_shop')</td>
		@endif
	@endif
</tr>