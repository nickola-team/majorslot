<tr>        
    <td>{{$ipblock->id}}</td>
	<td>{{ $ipblock->user->username }}</td>
    <td>{{$ipblock->ip_address}}</td>
    <td>
@if (auth()->user()->isInoutPartner())
    <a href="{{ argon_route('argon.ipblock.delete', $ipblock->id) }}"
            data-method="DELETE"
            data-confirm-title="확인"
            data-confirm-text="IP을 삭제하시겠습니까?"
            data-confirm-delete="확인"
            data-confirm-cancel="취소">
            <button type="button" class="btn btn-warning btn-sm">삭제</button>
        </a>
@endif
    </td>
</tr>