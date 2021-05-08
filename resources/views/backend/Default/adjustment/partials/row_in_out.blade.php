<tr>
	@if($in_out_log->partner_type == 'partner')
	<td>{{ $in_out_log->user->username }}</td>
	@else
	<td>{{ $in_out_log->shop->name }} [매장]</td>
	@endif
	
	@if($in_out_log->type == 'add' )
	<td><span class="text-green">{{ number_format($in_out_log->sum,0) }}</span></td>
	<td></td>
	@if(auth()->user()->hasRole(['master','manager']))
	<td></td>
	@endif
	@elseif($in_out_log->type == 'out' )
	<td></td>
	<td><span class="text-red">{{ number_format($in_out_log->sum,0) }}</span></td>
	@if(auth()->user()->hasRole(['master','manager']))
	<td></td>
	@endif
	@elseif($in_out_log->type == 'deal_out' )
	<td></td>
	<td></td>
	@if(auth()->user()->hasRole(['master','manager']))
	<td><span class="text-red">{{ number_format($in_out_log->sum,0) }}</span></td>
	@endif
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