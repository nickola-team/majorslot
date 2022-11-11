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
    <td class="text-right">
    <?php
        if ($msg->writer_id == 0) //system message
        {
            $msg->content = preg_replace('/replace_with_backend/',config('app.admurl'),$msg->content);
        }
    ?>
        <a class="newMsg viewMsg" href="#" data-toggle="modal" data-target="#openMsgModal" data-msg="{{ $msg->content }}" >
            <button class="btn btn-success btn-sm">보기</button>
        </a>
        <a href="{{ argon_route('argon.msg.delete', $msg->id) }}"
            data-method="DELETE"
            data-confirm-title="확인"
            data-confirm-text="쪽지를 삭제하시겠습니까?"
            data-confirm-delete="확인"
            data-confirm-cancel="취소">
            <button type="button" class="btn btn-warning btn-sm">삭제</button>
        </a>
    </td>
</tr>