<td>
@if (Session::get('isCashier'))
{{ $user->username }}
@else
<a href="{{ route('backend.user.edit', $user->id) }}">
{{ $user->username }}
</a>
@endif
</td>
<?php
        $parent = $user->referral;
        for ($r=$role_id+1;$r<auth()->user()->role_id;$r++)
        {
             echo '<td><a href="'.route('backend.user.edit', $parent->id).'">'.$parent->username.'</a></td>';
            $parent = $parent->referral;
        }
?>
<td>{{ number_format($user->balance,0) }}</td>
<td>{{ number_format($user->deal_balance - $user->mileage,0) }}</td>
@if (auth()->user()->hasRole('admin')  || auth()->user()->ggr_percent > 0 || (auth()->user()->hasRole('manager') && auth()->user()->shop->ggr_percent > 0))
<td>{{ number_format($user->ggr_balance - $user->ggr_mileage - ($user->count_deal_balance - $user->count_mileage),0) }}</td>
@endif
<td>{{ number_format($user->deal_percent,2) }}</td>
<td>{{ number_format($user->table_deal_percent,2) }}</td>
@if (auth()->user()->hasRole('admin')  || auth()->user()->ggr_percent > 0 || (auth()->user()->hasRole('manager') && auth()->user()->shop->ggr_percent > 0))
<td>{{ number_format($user->ggr_percent,2) }}</td>
<td>{{ $user->reset_days??0 }}일</td>
@endif

@if ($user->role_id == 7)
<td>{{ number_format($user->money_percent,2) }}</td>
@endif

<td>
@if (auth()->user()->isInoutPartner() || (auth()->user()->role_id == $user->role_id+1))
<a class="newPayment addPayment" href="#" data-toggle="modal" data-target="#openAddModal" data-id="{{ $user['id'] }} disabled" >
<button type="button" class="btn btn-block btn-success btn-xs">@lang('app.in')</button>
</a>
@else
<button type="button" class="btn btn-block btn-success btn-xs" disabled>@lang('app.in')</button>
@endif
</td>

<td>
@if (auth()->user()->isInoutPartner())
<a class="newPayment outPayment" href="#" data-toggle="modal" data-target="#openOutModal" data-id="{{ $user['id'] }}" >
<button type="button" class="btn btn-block btn-danger btn-xs">@lang('app.out')</button>
</a>
@else
<button type="button" class="btn btn-block btn-danger btn-xs" disabled>@lang('app.out')</button>
@endif

</td>


