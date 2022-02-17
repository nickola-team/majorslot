<tr>
<?php
	$black = $user->checkBlack();
?>	
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

	<td>
		@if ($black['phone'])
			<a href="{{auth()->user()->hasRole('admin')?route($admurl.'.black.list', ['phone'=>$user->phone]):'#'}}" data-tooltip="tooltip" title="{{$black['phone']}}" style="background:#d73925;">
			{{ $user->phone }}
			</a>
		@else
			{{ $user->phone }}
		@endif
	</td>
	<td>
		@if ($black['account'])
			<a href="{{auth()->user()->hasRole('admin')?route($admurl.'.black.list', ['account'=>$user->account_no]):'#'}}" data-tooltip="tooltip" title="{{$black['account']}}" style="background:#d73925;">
			{{"[ " . $user->bank_name . " ] ". $user->account_no}}
			</a>
		@else
			{{"[ " . $user->bank_name . " ] ". $user->account_no}}
		@endif
	</td>
	<td>{{ $user->recommender }}</td>
	<td>{{ $user->created_at }}</td>
	<td>
		@if( auth()->user()->isInoutPartner())
		<a class="newPayment allowJoin" href="#" data-toggle="modal" data-target="#openAllowModal" data-id="{{ $user->id }}" >
		<button type="button" class="btn btn-block btn-success btn-xs" {{($black['count'] > 0)?'disabled':''}}>승인</button>
		</a>
		@endif
	</td>
	<td>
		@if( auth()->user()->isInoutPartner())
		<a class="newPayment rejectJoin" href="#" data-toggle="modal" data-target="#openRejectModal" data-id="{{ $user->id }}" >
		<button type="button" class="btn btn-block btn-danger btn-xs">취소</button>
		</a>
		@endif
	</td>


</tr>