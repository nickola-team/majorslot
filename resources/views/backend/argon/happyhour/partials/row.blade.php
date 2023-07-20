<tr>
	<td>{{ $happyhour->id }}</td>
	<td>
		@if ($happyhour->admin)
		{{ $happyhour->admin->username }}
		@else
		삭제된 파트너
		@endif
	</td>
	<td>
		@if ($happyhour->user)
		<a href="#" data-toggle="tooltip" data-original-title="{{$happyhour->user->parents(auth()->user()->role_id)}}">{{ $happyhour->user->username }}</a>
		@else
		삭제된 회원
		@endif
	</td>
	<td>{{ number_format($happyhour->total_bank,0) }}</td>
	<td>{{ number_format($happyhour->current_bank,0) }}</td>
	<td>{{ number_format($happyhour->over_bank,0) }}</td>
	<!-- <td>{{ ['없음','메이저','그랜드'][$happyhour->jackpot] }}</td> -->
	<td>{{ $happyhour->created_at }}</td>
	<td>
		@if($happyhour->status == 2)
		{{ $happyhour->updated_at }}
		@endif
	</td>
	<td>
		@if($happyhour->status == 0)
			<span class="text-red">차단</span>
		@elseif($happyhour->status == 1)
			<span class="text-green">활성</span>
		@else
			<span class="text-red">완료</span>
		@endif
	</td>
	<td class="text-right">
		<!-- <a href="{{argon_route('argon.happyhour.edit', ['id'=>$happyhour->id])}}"><button class="btn btn-primary btn-sm">편집</button></a> -->
		@if ($happyhour->status != 2)
		<a href="{{ argon_route('argon.happyhour.delete', ['id'=>$happyhour->id]) }}" 
        data-method="DELETE"
        data-confirm-title="확인"
        data-confirm-text="콜을 완료하시겠습니까?"
        data-confirm-delete="확인"
        data-confirm-cancel="취소">
        <button class="btn btn-warning btn-sm" >완료</button>
    	</a>
		@endif
	</td>
</tr>