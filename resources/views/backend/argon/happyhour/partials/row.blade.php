<tr>
	<td>{{ $happyhour->id }}</td>
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
	<td>{{ ['없음','메이저','그랜드'][$happyhour->jackpot] }}</td>
	<td>
		@if($happyhour->status == 0)
			차단
		@elseif($happyhour->status == 1)
			활성
		@else
			완료
		@endif
	</td>
	<td class="text-right">
		<a href="{{argon_route('argon.happyhour.edit', ['id'=>$happyhour->id])}}"><button class="btn btn-primary btn-sm">편집</button></a>
		<a href="{{ argon_route('argon.happyhour.delete', ['id'=>$happyhour->id]) }}" 
        data-method="DELETE"
        data-confirm-title="확인"
        data-confirm-text="콜을 삭제하시겠습니까?"
        data-confirm-delete="확인"
        data-confirm-cancel="취소">
        <button class="btn btn-warning btn-sm" >삭제</button>
    </a>
	</td>
</tr>