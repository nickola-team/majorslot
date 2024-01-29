<tr>
<?php  
		$available_roles = \jeremykenedy\LaravelRoles\Models\Role::orderby('id')->pluck('name', 'id');
		$available_roles_trans = [];
		foreach ($available_roles as $key=>$role)
		{
			$role = \VanguardLTE\Role::find($key)->description;
			$available_roles_trans[$key] = $role;
		}
	?>
<td>
@if ($stat->user)
@if ($partner==0)
	<a href="{{ route($admurl.'.statistics', ['user' => $stat->user->username])  }}">
		{{ $stat->user->username }}
@else
	<a href="{{ route($admurl.'.statistics_partner', ['user' => $stat->user->username])  }}">
	{{ $stat->user->username }} [ {{$available_roles_trans[$stat->user->role_id]}} ]
@endif
</a>
@else
삭제된 유저 - {{$stat->user_id}}
@endif
	
</td>
<td>
	@if ($stat->admin)
	{{ $stat->admin ? $stat->admin->username : $stat->system  }} [ {{$available_roles_trans[$stat->admin->role_id]}} ]
	@else
	Unknown
	@endif

</td>
<td>
@if ($partner==0)
	@if ($stat->admin->role_id  <= auth()->user()->role_id)
	{{number_format($stat->balance,0)}}
	@endif
@else
@if (auth()->user()->isInoutPartner())
	{{number_format($stat->balance,0)}}
@endif
@endif
</td>
<td>
{{number_format($stat->old,0)}}
</td>
<td>
{{number_format($stat->new,0)}}
</td>
<td>
@if ($stat->type == 'add')
	<span class="text-green">{{ number_format(abs($stat->summ),0) }} 
	@if ($stat->reason != null)
		({{ $stat->reason }})
	@endif
	</span>
@endif
</td>
<td>
	@if ($stat->type == 'out')
		<span class="text-red">{{ number_format(abs($stat->summ),0) }}
		@if ($stat->reason != null)
			({{ $stat->reason }})
		@endif
		</span>
	@endif
</td>
<td>
	@if ($stat->type == 'deal_out')
		<span class="text-red">{{ number_format(abs($stat->summ),0) }}</span>
	@endif
</td>
@if ($partner==1)
@if (auth()->user()->hasRole('admin')  || auth()->user()->ggr_percent > 0 || (auth()->user()->hasRole('manager') && auth()->user()->shop->ggr_percent > 0))
<td>
	@if ($stat->type == 'ggr_out')
			<span class="text-red">{{ number_format(abs($stat->summ),0) }}</span>
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
@endif
<td>{{ $stat->created_at->format(config('app.date_time_format')) }}</td>
	@if(isset($show_shop) && $show_shop)
		@if($stat->shop)
			<td><a href="{{ route($admurl.'.shop.edit', $stat->shop->id) }}">{{ $stat->shop->name }}</a></td>
			@else
			<td>@lang('app.no_shop')</td>
		@endif
	@endif
</tr>