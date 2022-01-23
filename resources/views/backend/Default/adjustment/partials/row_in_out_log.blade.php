<tr>
	@if($in_out_log->partner_type == 'partner')
	@if ($in_out_log->user)
	<?php  
		$role = \VanguardLTE\Role::find($in_out_log->user->role_id);
	?>
	<td>{{ $in_out_log->user->username }} [{{$role->description }}]</td>
	@else
	<td>삭제된 파트너</td>
	@endif
	@else
	@if ($in_out_log->shop)
	<td>{{ $in_out_log->shop->name }} [매장]</td>
	@else
	<td>삭제된 매장 </td>
	@endif
	@endif
	@if (auth()->user()->isInoutPartner())
	<?php
		$end = auth()->user()->level();
		if ($in_out_log->user){
			$begin = $in_out_log->user->level();
			$parent = $in_out_log->user->referral;
		}
		else
		{
			$begin = 3;
			$parent = null;
		}
		for ($l=3;$l<$end;$l++)
		{
			if ($l<=$begin)
			{
				echo "<td></td>";
			}
			else
			{
				if ($parent) {
					echo "<td>".$parent->username."</td>";
					$parent = $parent->referral;
				}
				else
				{
					echo "<td></td>";
				}
			}
		}
	?>
	@endif
	@if($in_out_log->type == 'add' )
	<td><span class="text-green">{{ number_format($in_out_log->sum,0) }}</span></td>
	@if (Request::is('*/in_out_history'))
	<td></td>
	@endif
	@elseif($in_out_log->type == 'out' )
	@if (Request::is('*/in_out_history'))
	<td></td>
	@endif
	<td><span class="text-red">{{ number_format($in_out_log->sum,0) }}</span></td>
	@elseif($in_out_log->type == 'deal_out' )
	@if (Request::is('*/in_out_history'))
	<td></td>
	@endif
	<td><span class="text-red">{{ number_format($in_out_log->sum,0) }}</span></td>
	@endif
	@if ($in_out_log->partner_type == 'shop')
	@if (isset($in_out_log->shopStat))
	<td>{{number_format($in_out_log->shopStat->old)}}</td>
	<td>{{number_format($in_out_log->shopStat->new)}}</td>
	@else
	<td></td><td></td>
	@endif
	@else
	@if (isset($in_out_log->transaction))
	<td>{{number_format($in_out_log->transaction->old)}}</td>
	<td>{{number_format($in_out_log->transaction->new)}}</td>
	@else
	<td></td><td></td>
	@endif
	@endif
	<td>{{"[ " . $in_out_log->bank_name . " ] ". $in_out_log->account_no}}</td>

	<td>{{ $in_out_log->recommender}}</td>
	<td>{{ $in_out_log->created_at}}</td>
	<td>{{ $in_out_log->updated_at}}</td>
	@if (Request::is('*/in_out_history'))
	<td>
		@if ($in_out_log->status==1)
		<span class="text-green">승인</span>
		@else
		<span class="text-red">취소</span>
		@endif
	</td>
	@endif

</tr>