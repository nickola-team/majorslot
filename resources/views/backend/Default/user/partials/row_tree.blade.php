<td>
@if ( $user['role_id'] > 3 )
<a href="{{ route('backend.user.tree') }}?parent={{$user['id']}}">
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
        $role = trans("app." . strtolower($role));
        $available_roles_trans[$key] = $role;
    }
?>
<td>{{ $available_roles_trans[$user['role_id']] }}</td>
@if ( isset($user['shop']) && empty(Request::get('search')) )
<td>
<a href="{{ route('backend.shop.edit', $user['shop_id']) }}">
{{ $user['shop'] }}
</a>
</td>
@endif
<td>{{ number_format($user['balance'],0) }}</td>
@if (!auth()->user()->hasRole(['admin','comaster']) && $user['role_id']==6)
<td>없음</td>
<td>없음</td>
<td>없음</td>
@else
<td>{{ number_format($user['profit'],0) }}</td>
<td>{{ number_format($user['deal_percent'],2) }}</td>
<td>{{ number_format($user['table_deal_percent'],2) }}</td>
@endif
@if ( auth()->user()->hasRole('admin'))
<td>{{ number_format($user['bonus'],0) }}</td>
@endif
<td>
@if (Session::get('isCashier'))
<button type="button" class="btn btn-block btn-primary btn-xs" disabled>편집</button>
@else
@if ( $user['role_id'] == 3)
<a href="{{ route('backend.shop.edit', $user['shop_id']) }}">
<button type="button" class="btn btn-block btn-primary btn-xs">편집</button>
</a>
@else
<a href="{{ route('backend.user.edit', $user['id']) }}">
<button type="button" class="btn btn-block btn-primary btn-xs">편집</button>
</a>
@endif
@endif
</td>
<td>
@if ($user['role_id']>3 && $user['id']!=auth()->user()->id && (auth()->user()->role_id == $user['role_id']+1||auth()->user()->hasRole(['admin','comaster','master'])) && !Session::get('isCashier'))
<a class="newPayment addPayment" href="#" data-toggle="modal" data-target="#openAddModal" data-id="{{ $user['id'] }} disabled" >
<button type="button" class="btn btn-block btn-success btn-xs">@lang('app.in')</button>
</a>
@else
<button type="button" class="btn btn-block btn-success btn-xs" disabled>@lang('app.in')</button>
@endif
</td>

<td>
@if ($user['role_id']>3 && $user['id']!=auth()->user()->id && (auth()->user()->role_id == $user['role_id']+1||auth()->user()->hasRole(['admin','comaster','master'])) && !Session::get('isCashier'))
<a class="newPayment outPayment" href="#" data-toggle="modal" data-target="#openOutModal" data-id="{{ $user['id'] }}" >
<button type="button" class="btn btn-block btn-danger btn-xs">@lang('app.out')</button>
</a>
@else
<button type="button" class="btn btn-block btn-danger btn-xs" disabled>@lang('app.out')</button>
@endif

</td>


