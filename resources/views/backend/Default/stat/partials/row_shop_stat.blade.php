<tr>
	
	<td>
	@if ($stat->shop)
		{{ $stat->shop->name }}
	@else
		삭제된 매장 - {{$stat->shop_id}}
	@endif
	</td>
	<td>
	<?php  
		$available_roles = \jeremykenedy\LaravelRoles\Models\Role::orderby('id')->pluck('name', 'id');
		$available_roles_trans = [];
		foreach ($available_roles as $key=>$role)
		{
			$role = \VanguardLTE\Role::find($key)->description;
			$available_roles_trans[$key] = $role;
		}
	?>
	@if ($stat->user)
		{{ $stat->user ? $stat->user->username : 'unknown'  }} [ {{$available_roles_trans[$stat->user->role_id]}} ]
	@else
		삭제된 파트너 - {{$stat->user_id}}
	@endif
	</td>
	@if (auth()->user()->isInoutPartner())
	<td>
		{{number_format($stat->balance,0)}}
	</td>
	@endif
	<td>
		{{ number_format($stat->old,0) }}
	</td>
	<td>
		{{ number_format($stat->new,0) }}
	</td>
	<td>
		@if ($stat->type == 'add')
			<span class="text-green">{{ number_format(abs($stat->sum),0) }}	
			@if ($stat->reason != null)
				({{ $stat->reason }})
			@endif
			</span>
		@endif
	</td>
	<td>
		@if ($stat->type == 'out')
			<span class="text-red">{{ number_format(abs($stat->sum),0) }}
			@if ($stat->reason != null)
				({{ $stat->reason }})
			@endif
			</span>
		@endif
	</td>
	<td>
		@if ($stat->type == 'deal_out')
			<span class="text-red">{{ number_format(abs($stat->sum),0) }}</span>
		@endif
	</td>
	@if (auth()->user()->hasRole('admin')  || auth()->user()->ggr_percent > 0 || (auth()->user()->hasRole('manager') && auth()->user()->shop->ggr_percent > 0))
	<td>
		@if ($stat->type == 'ggr_out')
				<span class="text-red">{{ number_format(abs($stat->sum),0) }}</span>
		@endif
	</td>
	@endif
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
			<td><a href="{{ route($admurl.'.shop.edit', $stat->shop->id) }}">{{ $stat->shop->name }}</a></td>
		@endif
	@endif
</tr>