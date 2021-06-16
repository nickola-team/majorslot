<tr>
    <td>
        <a href="{{ route('backend.user.edit', $user->id) }}">
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
			echo '<td><a href="'.route('backend.user.edit', $parent->id).'">'.$parent->username.'</a></td>';
		}
		else
		{
			echo '<td><a href="#">unknown</a></td>';
		}
	}
?>

	@permission('users.balance.manage')
	<td>{{ number_format($user->balance,0) }}</td>
	{{-- <td>{{ $user->bonus }}</td> --}}
	<td>{{ number_format($user->total_in,0) }}</td>
	<td>{{ number_format($user->total_out,0) }}</td>
	<td>{{ number_format($user->wager,0) }}</td>
	@if($user->status == 'Active')
	<td>활성</td>
	@elseif($user->status == 'Banned')
	<td>차단</td>
	@else
	<td>미승인</td>
	@endif
	<td>
		@if (Auth::user()->hasRole(['admin','comaster','master','manager']) && $user->hasRole('user'))
		@if (auth()->user()->hasRole('master') && settings('enable_master_deal'))
		<button type="button" class="btn btn-block btn-success disabled btn-xs">@lang('app.in')</button>
		@else
		<a class="newPayment addPayment" href="#" data-toggle="modal" data-target="#openAddModal" data-id="{{ $user->id }}" >
		<button type="button" class="btn btn-block btn-success btn-xs">@lang('app.in')</button>
		</a>
		@endif
		@else
			<button type="button" class="btn btn-block btn-success disabled btn-xs">@lang('app.in')</button>
		@endif
	</td>
	<td>
		@if (Auth::user()->hasRole(['admin','comaster','master','manager']) && $user->hasRole('user'))
		@if (auth()->user()->hasRole('master') && settings('enable_master_deal'))
		<button type="button" class="btn btn-block btn-danger disabled btn-xs">@lang('app.out')</button>
		@else
		<a class="newPayment outPayment" href="#" data-toggle="modal" data-target="#openOutModal" data-id="{{ $user->id }}" >
		<button type="button" class="btn btn-block btn-danger btn-xs">@lang('app.out')</button>
		</a>
		@endif
		@else
			<button type="button" class="btn btn-block btn-danger disabled btn-xs">@lang('app.out')</button>
		@endif
	</td>
	{{-- @if(Auth::user()->hasRole('cashier'))
	<td>
		@if(
    		$user->rating == 1 
		)
		<a class="newPayment outPayment" href="{{ route('backend.user.balance.bonus', $user->id )}}">
		<button type="button" class="btn btn-block btn-danger btn-xs">설정</button>
		</a>
		@else
		<a class="newPayment outPayment" href="{{ route('backend.user.balance.bonus', $user->id )}}">
			<button type="button" class="btn btn-block btn-danger btn-xs">해제</button>
		</a>
		@endif
	</td>
	@endif --}}
    @endpermission

	@if(isset($show_shop) && $show_shop)
		@if($user->shop)
			<td><a href="{{ route('backend.shop.edit', $user->shop->id) }}">{{ $user->shop->name }}</a></td>
			@else
			<td></td>
		@endif
	@endif
</tr>