<tr>        
    <td>{{$website->domain}}</td>
	<td>{{ $website->title }}</td>
    <td>{{ $website->frontend }}</td>
    <td>{{ $website->backend }}</td>
    <td>{{ $website->admin ?$website->admin->username :'알수없음' }}</td>
    <td>{{ $website->created_at }}</td>
    <td>
    <a href="{{ argon_route('argon.website.edit', $website->id) }}">
        <button type="button" class="btn btn-success btn-sm">편집</button>
    </a>
    <a href="{{ argon_route('argon.website.delete', $website->id) }}"
            data-method="DELETE"
            data-confirm-title="@lang('app.please_confirm')"
            data-confirm-text="공지를 삭제하시겠습니까?"
            data-confirm-delete="@lang('app.yes_delete_him')">
            <button type="button" class="btn btn-warning btn-sm">삭제</button>
        </a>
    </td>
</tr>