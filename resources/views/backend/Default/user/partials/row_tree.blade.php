<td>
@if ( $user['role_id'] > 3 )
<a href="{{ route($admurl.'.user.tree') }}?parent={{$user['id']}}">
@endif
{{ $user['name'] }}
@if ( $user['role_id'] > 3 )
</a>
@endif
</td>
<?php  
    $available_roles = Auth::user()->available_roles( true );
    $available_roles_trans = [];
    foreach ($available_roles as $key=>$role)
    {
        $role = \VanguardLTE\Role::find($key)->description;
        $available_roles_trans[$key] = $role;
    }
?>
<td>{{ $available_roles_trans[$user['role_id']] }}</td>
@if ( isset($user['shop']) && empty(Request::get('search')) )
<td>
<a href="{{ route($admurl.'.shop.edit', $user['shop_id']) }}">
{{ $user['shop'] }}
</a>
</td>
@endif
<td>{{ number_format($user['balance'],0) }}</td>
<td>{{ number_format($user['profit'],0) }}</td>
@if (auth()->user()->hasRole('admin')  || auth()->user()->ggr_percent > 0 || (auth()->user()->hasRole('manager') && auth()->user()->shop->ggr_percent > 0))
<td>{{ number_format($user['ggr_profit'],0) }}</td>
@endif
<td>{{ number_format($user['deal_percent'],2) }}</td>
<td>{{ number_format($user['table_deal_percent'],2) }}</td>
@if (auth()->user()->hasRole('admin')  || auth()->user()->ggr_percent > 0 || (auth()->user()->hasRole('manager') && auth()->user()->shop->ggr_percent > 0))
<td>{{ number_format($user['ggr_percent'],2) }}</td>
<td>{{ $user['reset_days']??0 }}일</td>
@endif

<td>
<a href="{{ route($admurl.'.user.edit', $user['id']) }}">
<button type="button" class="btn btn-block btn-primary btn-xs">편집</button>
</a>
</td>
<td>
@if ($user['role_id']!=3 && (auth()->user()->hasRole('admin') || ($user['id']!=auth()->user()->id && auth()->user()->role_id == $user['role_id']+1)))
<a class="newPayment addPayment" href="#" data-toggle="modal" data-target="#openAddModal" data-id="{{ $user['id'] }} disabled" >
<button type="button" class="btn btn-block btn-success btn-xs">@lang('app.in')</button>
</a>
@else
<button type="button" class="btn btn-block btn-success btn-xs" disabled>@lang('app.in')</button>
@endif
</td>

<td>
@if ($user['role_id']!=3 && auth()->user()->isInoutPartner() && $user['id']!=auth()->user()->id)
<a class="newPayment outPayment" href="#" data-toggle="modal" data-target="#openOutModal" data-id="{{ $user['id'] }}" >
<button type="button" class="btn btn-block btn-danger btn-xs">@lang('app.out')</button>
</a>
@else
<button type="button" class="btn btn-block btn-danger btn-xs" disabled>@lang('app.out')</button>
@endif

</td>


