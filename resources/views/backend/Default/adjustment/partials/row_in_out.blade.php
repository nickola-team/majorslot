<tr>
<?php  
    $available_roles = Auth::user()->available_roles( true );
    $available_roles_trans = [];
    foreach ($available_roles as $key=>$role)
    {
        $role = trans("app." . strtolower($role));
        $available_roles_trans[$key] = $role;
    }
	?>
<td>
	<?php
		$level = $in_out_log->user->level();
		$parent = $in_out_log->user->referral;
		$hierarchy = '';
		for (;$level<Auth::user()->level();$level++)
		{
			$hierarchy = $hierarchy . ' > ' . $parent->username .'[' .$available_roles_trans[$parent->role_id]. ']';
			$parent = $parent->referral;
		}
	?>
	@if($in_out_log->partner_type == 'partner')
	<a class="partnerInfo" href="#" data-toggle="modal" data-target="#infoModal" data-id="{{ $hierarchy }}" >
		{{ $in_out_log->user->username }} [{{$available_roles_trans[$in_out_log->user->role_id] }}]
		</a>
	
	@else
	<a class="partnerInfo" href="#" data-toggle="modal" data-target="#infoModal" data-id="{{ $hierarchy }}" >
	{{ $in_out_log->shop->name }} [매장]
	</a>
	@endif
	
</td>
	@if($in_out_log->type == 'add' )
	<td><span class="text-green">{{ number_format($in_out_log->sum,0) }}</span></td>
	@elseif($in_out_log->type == 'out' )
	<td><span class="text-red">{{ number_format($in_out_log->sum,0) }}</span></td>
	@elseif($in_out_log->type == 'deal_out' )
	<td><span class="text-red">{{ number_format($in_out_log->sum,0) }}</span></td>
	@endif
	<td>{{"[ " . $in_out_log->bank_name . " ] ". $in_out_log->account_no}}</td>

	<td>{{ $in_out_log->recommender}}</td>
	<td>{{ $in_out_log->created_at}}</td>

	@if($in_out_log->status == 1)
	<td>완료</td>
	@if($in_out_log->user_id != auth()->user()->id)
	<td></td>
	@endif
	@elseif($in_out_log->status == 0)
	<td>대기</td>
	@if($in_out_log->user_id != auth()->user()->id)
	<td>
		<a class="newPayment allowPayment" href="#" data-toggle="modal" data-target="#openAllowModal" data-id="{{ $in_out_log->id }}" >
			<button type="button" class="btn btn-block btn-primary btn-xs">승인</button>
		</a>
	</td>
	<td>
		<a class="newPayment rejectPayment" href="#" data-toggle="modal" data-target="#openRejectModal" data-id="{{ $in_out_log->id }}" >
			<button type="button" class="btn btn-block btn-danger btn-xs">취소</button>
		</a>

	</td>
	@endif
	@elseif($in_out_log->status == 2)
	<td>취소됨</td>	
	@if($in_out_log->user_id != auth()->user()->id)
	<td></td>
	@endif
	@endif

</tr>