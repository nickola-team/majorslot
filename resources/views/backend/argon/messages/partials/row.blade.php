<?php 
    $badge_class = \VanguardLTE\User::badgeclass();
?>
<tr>
    <td>
        @if ($msg->writer_id == 0)
            시스템
        @elseif ($msg->writer)
            @if ($msg->writer->role_id > auth()->user()->role_id)
                총본사
            @else
            <a href="#" data-toggle="tooltip" data-original-title="{{$msg->writer->parents(auth()->user()->role_id-1)}}">
                {{$msg->writer->username}} <span class="badge {{$badge_class[$msg->writer->role_id]}}">{{$msg->writer->role->description}}</span>
            </a>
            @endif
        @else
        '알수없음'
        @endif
    </td>
    <td>
        @if ($msg->user_id == 0)
            시스템
        @elseif ($msg->user)
            @if ($msg->user->role_id > auth()->user()->role_id)
                총본사
            @else
            <a href="#" data-toggle="tooltip" data-original-title="{{$msg->user->parents(auth()->user()->role_id-1)}}">
                {{$msg->user->username}}<span class="badge {{$badge_class[$msg->user->role_id]}}">{{$msg->user->role->description}}</span>
            </a>
            @endif
        @else
        '알수없음'
        @endif
    </td>
    <td>
        @if (auth()->user()->id == $msg->user_id)
        <span class="badge badge-success">수신</span>
        @else
        <span class="badge badge-primary">발신</span>
        @endif
    </td>
    <td>
        <a class="newMsg viewMsg" href="#" data-toggle="modal" data-target="#openMsgModal" data-msg="{{ $msg->content }}" >
            {{$msg->title}}
        </a>
    </td>
	<td>{{ $msg->created_at }}</td>
    <td>{{ $msg->read_at??'읽지않음' }}</td>
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