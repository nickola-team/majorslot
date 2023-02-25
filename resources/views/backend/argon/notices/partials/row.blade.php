<tr>        
    <td>{{$notice->title}}</td>
	<td>{{ $notice->date_time }}</td>
    <td>
            @if ($notice->popup==null)
            일반
            @else
            {{\VanguardLTE\Notice::popups()[$notice->popup]}}
            @endif
    </td>
    <td>
        {{\VanguardLTE\Notice::lists()[$notice->type]}}
    </td>
    <td>{{ $notice->active==1?'활성':'비활성' }} </td>
    @if (auth()->user()->hasRole('admin'))
    <td>{{$notice->writer?$notice->writer->username:'알수없음'}}</td>
    @endif
    <td class="text-right">
    <a href="{{ argon_route('argon.notice.edit', $notice->id) }}">
        <button type="button" class="btn btn-success btn-sm">편집</button>
    </a>
    <a href="{{ argon_route('argon.notice.delete', $notice->id) }}"
            data-method="DELETE"
            data-confirm-title="확인"
            data-confirm-text="공지를 삭제하시겠습니까?"
            data-confirm-delete="확인"
            data-confirm-cancel="취소">
            <button type="button" class="btn btn-warning btn-sm">삭제</button>
        </a>
    </td>
</tr>