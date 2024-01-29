<tr>
<td>
	<?php
		$hierarchy = '';
		if ($in_out_log->user){
			$level = $in_out_log->user->level();
			$parent = $in_out_log->user->referral;
			for (;$level<Auth::user()->level()-1;$level++)
			{
				$role = \VanguardLTE\Role::find($parent->role_id);
				$hierarchy = $hierarchy . ' > ' . $parent->username .'[' .$role->description. ']';
				$parent = $parent->referral;
			}
		}
	?>
	@if($in_out_log->partner_type == 'partner')
		@if ($in_out_log->user)
		<a class="partnerInfo" href="#" data-toggle="modal" data-target="#infoModal" data-id="{{ $hierarchy }}" >
		{{ $in_out_log->user->username }} [{{\VanguardLTE\Role::find($in_out_log->user->role_id)->description }}]
		</a>
		@else
		삭제된 파트너
		@endif
	
	@else
	@if ($in_out_log->shop)
		<a class="partnerInfo" href="#" data-toggle="modal" data-target="#infoModal" data-id="{{ $hierarchy }}" >
		{{ $in_out_log->shop->name }} [매장]
		</a>
	@else
		삭제된 매장
	@endif
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
	<td>{{ \VanguardLTE\WithdrawDeposit::statMsg()[$in_out_log->status]}}</td>

	@if($in_out_log->user_id != auth()->user()->id)
	<td>
		<a class="newPayment allowPayment" href="#" data-toggle="modal" data-target="#openAllowModal" data-id="{{ $in_out_log->id }}" >
			<button type="button" class="btn btn-block btn-primary btn-xs">승인</button>
		</a>
	</td>
	@if($in_out_log->status == \VanguardLTE\WithdrawDeposit::REQUEST)
	<td>
		<a href="{{ route('frontend.api.wait_in_out', $in_out_log->id) }}" >
			<button type="button" class="btn btn-block btn-success btn-xs">대기</button>
		</a>
	</td>
	@endif
	<td>
		<a class="newPayment rejectPayment" href="#" data-toggle="modal" data-target="#openRejectModal" data-id="{{ $in_out_log->id }}" >
			<button type="button" class="btn btn-block btn-danger btn-xs">취소</button>
		</a>

	</td>
	@endif
</tr>