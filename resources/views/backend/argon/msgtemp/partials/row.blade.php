<tr>        
    <td>{{$msgtemp->id}}</td>
    <td>{{$msgtemp->title}}</td>
    <td>{{ $msgtemp->order }}</td>
	<td>{{ $msgtemp->created_at }}</td>
    @if (auth()->user()->hasRole('admin'))
    <td>{{$msgtemp->writer?$msgtemp->writer->username:'알수없음'}}</td>
    @endif
    <td class="text-right">
    <a href="{{ argon_route('argon.msgtemp.edit', $msgtemp->id) }}">
        <button type="button" class="btn btn-success btn-sm">편집</button>
    </a>
    <a href="{{ argon_route('argon.msgtemp.delete', $msgtemp->id) }}"
            data-method="DELETE"
            data-confirm-title="확인"
            data-confirm-text="템플릿을 삭제하시겠습니까?"
            data-confirm-delete="확인"
            data-confirm-cancel="취소">
            <button type="button" class="btn btn-warning btn-sm">삭제</button>
        </a>
    </td>
</tr>