<td>
@if (Session::get('isCashier'))
{{ $user->username }}
@else
<a href="{{ route($admurl.'.user.edit', $user->id) }}"  data-tooltip="tooltip" title="{{$user->avatar}}">
{{ $user->username }}
</a>
@endif
</td>
<?php
        $parent = $user->referral;
        for ($r=$role_id+1;$r<auth()->user()->role_id;$r++)
        {
             echo '<td><a href="'.route($admurl.'.user.edit', $parent->id).'" data-tooltip="tooltip" title="'.$parent->avatar.'">'.$parent->username.'</a></td>';
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

<td>
@if ($user->ggr_percent > 0)
        @if( $user->hasRole(['cashier', 'manager']) )
        {{$user->shop->last_reset_at?\Carbon\Carbon::parse($user->shop->last_reset_at)->addDays($user->shop->reset_days):date('Y-m-d 00:00:00', strtotime("+" . $user->shop->reset_days . " days"))}}
        @else
        {{$user->last_reset_at?\Carbon\Carbon::parse($user->last_reset_at)->addDays($user->reset_days):date('Y-m-d', strtotime("+" . $user->reset_days . " days"))}}
        @endif
@endif
</td>
@if ($user->role_id == 7)
<td>{{ number_format($user->money_percent,2) }}</td>
@endif
@if ($user->role_id == 6)
<td>
@if ($user->ggr_percent > 0)
<a class="newReset allowReset" href="#" data-toggle="modal" data-target="#openResetModal" data-id="{{ $user['id'] }}" >
<button type="button" class="btn btn-block btn-primary btn-xs">리셋</button>
</a>
@else
<button type="button" class="btn btn-block btn-primary btn-xs" disabled>리셋</button>
@endif
</td>
@endif

<td>
@if (auth()->user()->isInoutPartner() || (auth()->user()->role_id == $user->role_id+1))
<a class="newPayment addPayment" href="#" data-toggle="modal" data-target="#openAddModal" data-id="{{ $user['id'] }}" >
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


