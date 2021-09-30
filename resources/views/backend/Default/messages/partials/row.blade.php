<tr>        
    <td>
        {{$msg->user_id==0?'전체회원':($msg->user?$msg->user->username:'삭제된회원')}}
    </td>
    <td>{{$msg->title}}</td>
	<td>{{ $msg->created_at }}</td>
    <td>{{ $msg->read_at??'읽지않음' }}</td>
    @if (auth()->user()->hasRole('admin'))
    <td>{{$msg->writer?$msg->writer->username:'알수없음'}}</td>
    @endif
    <td>
        <a class="newMsg viewMsg" href="#" data-toggle="modal" data-target="#openMsgModal" data-msg="{{ $msg->content }}" >
        <button type="button" class="btn btn-block btn-success btn-xs">보기</button>
        </a>
    </td>
    <td>
    <a href="{{ route($admurl.'.msg.delete', $msg->id) }}"
            data-method="DELETE"
            data-confirm-title="@lang('app.please_confirm')"
            data-confirm-text="쪽지를 삭제하시겠습니까?"
            data-confirm-delete="@lang('app.yes_delete_him')">
            <button type="button" class="btn btn-block btn-danger btn-xs">삭제</button>
        </a>
    </td>
</tr>