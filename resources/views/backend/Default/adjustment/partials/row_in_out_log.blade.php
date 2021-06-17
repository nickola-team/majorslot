<tr>
	@if($in_out_log->partner_type == 'partner')
	<?php  
    $available_roles = Auth::user()->available_roles( true );
    $available_roles_trans = [];
    foreach ($available_roles as $key=>$role)
    {
        $role = trans("app." . strtolower($role));
        $available_roles_trans[$key] = $role;
    }
	?>
	<td>{{ $in_out_log->user->username }} [{{$available_roles_trans[$in_out_log->user->role_id] }}]</td>
	@else
	@if ($in_out_log->shop)
	<td>{{ $in_out_log->shop->name }} [매장]</td>
	@endif
	@endif
	@if (auth()->user()->isInoutPartner())
	@if (auth()->user()->hasRole(['admin','comaster','master','agent']))
	<td>
	@if ($in_out_log->user->role_id == 3)
	{{$in_out_log->user->referral->username}}
	@endif
	</td>
	@endif
	@if (auth()->user()->hasRole(['admin','comaster','master']))
	<td>
	@if ($in_out_log->user->role_id == 3)
	{{$in_out_log->user->referral->referral->username}}
	@endif
	@if ($in_out_log->user->role_id == 4)
	{{$in_out_log->user->referral->username}}
	@endif
	</td>
	@endif
	@if (auth()->user()->hasRole(['admin','comaster']))
	<td>
	@if ($in_out_log->user->role_id == 3)
	{{$in_out_log->user->referral->referral->referral->username}}
	@endif
	@if ($in_out_log->user->role_id == 4)
	{{$in_out_log->user->referral->referral->username}}
	@endif
	@if ($in_out_log->user->role_id == 5)
	{{$in_out_log->user->referral->username}}
	@endif
	</td>
	@endif
	@endif
	@if($in_out_log->type == 'add' )
	<td><span class="text-green">{{ number_format($in_out_log->sum,0) }}</span></td>
	@if (Request::is('backend/in_out_history'))
	<td></td>
	@endif
	@elseif($in_out_log->type == 'out' )
	@if (Request::is('backend/in_out_history'))
	<td></td>
	@endif
	<td><span class="text-red">{{ number_format($in_out_log->sum,0) }}</span></td>
	@elseif($in_out_log->type == 'deal_out' )
	@if (Request::is('backend/in_out_history'))
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
	@if (Request::is('backend/in_out_history'))
	<td>
		@if ($in_out_log->status==1)
		<span class="text-green">승인</span>
		@else
		<span class="text-red">취소</span>
		@endif
	</td>
	@endif

</tr>