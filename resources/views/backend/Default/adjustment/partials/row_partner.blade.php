<tr>
	<td>
	@if($adjustment['role_id'] > 3)
	<a href="{{ route($admurl.'.adjustment_partner', 'parent='.$adjustment['user_id']) }}">{{ $adjustment['name'] }}	</a>
	@else
	{{ $adjustment['name'] }}
	@endif
	</td>
	<?php  
		$role = \VanguardLTE\Role::find($adjustment['role_id']);
	?>
	<td>{{ $role->description }}</td>
	<td>{{ number_format($adjustment['totalin'],0) }}</td>
	<td>{{ number_format($adjustment['totalout'],0) }}</td>
	<td>{{ number_format($adjustment['moneyin'],0) }}</td>
	<td>{{ number_format($adjustment['moneyout'],0) }}</td>
	@if(auth()->user()->isInoutPartner())
		<td>{{ number_format($adjustment['totalin'] - $adjustment['totalout'],0) }}</td>
	@endif
	<td>{{ number_format($adjustment['total_deal'] - $adjustment['total_mileage'],0) }}</td>
	<td>{{ number_format($adjustment['dealout'],0) }}</td>
	@if ( auth()->user()->hasRole('admin') || auth()->user()->ggr_percent > 0 || (auth()->user()->hasRole('manager') && auth()->user()->shop->ggr_percent > 0))
	<td>{{ number_format($adjustment['total_ggr'] - $adjustment['total_ggr_mileage'] - ($adjustment['total_deal'] - $adjustment['total_mileage']),0) }}</td>
	<td>{{ number_format($adjustment['ggrout'],0) }}</td>
	@endif

	<td>{{ number_format($adjustment['totalbet'],0)}}</td>
	<td>{{ number_format($adjustment['totalwin'],0)}}</td>
	<td>{{ number_format($adjustment['totalbet'] - $adjustment['totalwin'],0) }}</td>
	@if(auth()->user()->isInoutPartner())
	<td>{{ number_format($adjustment['ggr'],0) }}</td>
	<td>{{ number_format($adjustment['totalin'] - $adjustment['totalout'] - $adjustment['ggr'] ,0) }}</td>
	@endif
</tr>