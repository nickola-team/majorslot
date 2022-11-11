<tr>        
    <td>{{$website->id}}</td>
    <td>{{$website->domain}}</td>
	<td>{{ $website->title }}</td>
    <td>{{ $website->frontend }}</td>
    <td>{{ $website->backend }}</td>
    <td>{{ $website->admin ?$website->admin->username :'알수없음' }}</td>
    <td>{{ $website->created_at }}</td>
    <td class="text-right">
    <a href="{{ argon_route('argon.website.edit', $website->id) }}">
        <button type="button" class="btn btn-success btn-sm">편집</button>
    </a>
    <a href="{{ argon_route('argon.website.delete', $website->id) }}"
            data-method="DELETE"
            data-confirm-title="확인"
            data-confirm-text="도메인을 삭제하시겠습니까?"
            data-confirm-delete="확인"
            data-confirm-cancel="취소">
            <button type="button" class="btn btn-warning btn-sm">삭제</button>
        </a>
    </td>
</tr>