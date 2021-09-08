<tr>
    <td>
        <a href="{{ route($admurl.'.user.edit', $user->id) }}">
            {{ $user->username ?: trans('app.n_a') }}
        </a>
    </td>
	<td>
        {{ $user->shop->name}}
    </td>
<?php
	$parent = $user->referral;
	$role_id = $parent->role_id;
	for ($r=$role_id+1;$r<auth()->user()->role_id;$r++)
	{
		if ($parent){
			$parent = $parent->referral;
		}
		if ($parent)
		{
			echo '<td><a href="'.route($admurl.'.user.edit', $parent->id).'">'.$parent->username.'</a></td>';
		}
		else
		{
			echo '<td><a href="#">unknown</a></td>';
		}
	}
?>

	@permission('users.balance.manage')
	<td>{{ number_format($user->balance,0) }}</td>
	<td>{{ number_format($user->deal_balance, 0) }}</td>
	<td>{{ $user->deal_percent }}</td>
	<td>{{ $user->table_deal_percent }}</td>
	<td>{{ number_format($user->total_in,0) }}</td>
	<td>{{ number_format($user->total_out,0) }}</td>
	<td>{{ $user->phone }}</td>
	@if($user->status == 'Active')
	<td>활성</td>
	@elseif($user->status == 'Banned')
	<td>차단</td>
	@else
	<td>미승인</td>
	@endif
	<td>
		@if( auth()->user()->isInoutPartner() || auth()->user()->hasRole('manager'))
		<a class="newPayment addPayment" href="#" data-toggle="modal" data-target="#openAddModal" data-id="{{ $user->id }}" >
		<button type="button" class="btn btn-block btn-success btn-xs">@lang('app.in')</button>
		</a>
		@else
		<button type="button" class="btn btn-block btn-success disabled btn-xs">@lang('app.in')</button>
		@endif
	</td>
	<td>
		@if( auth()->user()->isInoutPartner() || auth()->user()->hasRole('manager'))
		<a class="newPayment outPayment" href="#" data-toggle="modal" data-target="#openOutModal" data-id="{{ $user->id }}" >
		<button type="button" class="btn btn-block btn-danger btn-xs">@lang('app.out')</button>
		</a>
		@else
			<button type="button" class="btn btn-block btn-danger disabled btn-xs">@lang('app.out')</button>
		@endif
	</td>

	<td>
		@if( auth()->user()->isInoutPartner() || auth()->user()->hasRole('manager'))
		<a class="newPayment dealoutPayment" href="#" data-toggle="modal" data-target="#openDealOutModal"  data-id="{{ $user->id }}" data-val="{{ (int)($user->deal_balance / 10000) * 10000 }}">
			<button type="button" class="btn btn-block btn-primary btn-xs" id="convert-deal-balance-btn">
			딜비전환
			</button>
		</a>
		@else
			<button type="button" class="btn btn-block btn-primary disabled btn-xs">딜비전환</button>
		@endif
	</td>
    @endpermission

</tr>