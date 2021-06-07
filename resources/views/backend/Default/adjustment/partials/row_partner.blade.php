<tr>
	<td>
	@if($adjustment['role_id'] > 3)
	<a href="{{ route('backend.adjustment_partner', 'parent='.$adjustment['user_id']) }}">{{ $adjustment['name'] }}	</a>
	@else
	{{ $adjustment['name'] }}
	@endif
	</td>
	<?php  
    $available_roles = Auth::user()->available_roles( true );
    $available_roles_trans = [];
    foreach ($available_roles as $key=>$role)
    {
        $role = trans("app." . strtolower($role));
        $available_roles_trans[$key] = $role;
    }
	?>
	<td>{{ $available_roles_trans[$adjustment['role_id']] }}</td>
	<td>{{ number_format($adjustment['totalin'],0) }}</td>
	<td>{{ number_format($adjustment['totalout'],0) }}</td>
	@if(auth()->user()->hasRole(['admin', 'comaster', 'master']))
		<td>{{ number_format($adjustment['totalin'] - $adjustment['totalout'],0) }}</td>
	@endif
	<td>{{ number_format(abs($adjustment['total_deal'] - $adjustment['total_mileage']),0) }}</td>
	<td>{{ number_format($adjustment['dealout'],0) }}</td>
	<td>{{ number_format($adjustment['moneyin'],0) }}</td>
	<td>{{ number_format($adjustment['moneyout'],0) }}</td>
	<td>{{ number_format($adjustment['totalbet'],0)}}</td>
	<td>{{ number_format($adjustment['totalwin'],0)}}</td>
	<td>{{ number_format($adjustment['totalbet'] - $adjustment['totalwin'],0) }}</td>
	@if(auth()->user()->hasRole(['admin','comaster',  'master']))
		<td>{{ number_format($adjustment['totalbet'] - $adjustment['totalwin'] - abs($adjustment['total_deal'] - $adjustment['total_mileage']),0) }}</td>
	@endif
</tr>