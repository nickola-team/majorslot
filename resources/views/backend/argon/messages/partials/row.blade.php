<?php 
    $badge_class = \VanguardLTE\User::badgeclass();
?>
    <td>
    <span  class="{{count($msg->refs)>0?'partner':'shop'}}">
        @if ($msg->writer)
            @if ($msg->writer->role_id > auth()->user()->role_id)
                총본사
            @else
            <a href="{{argon_route('argon.common.profile', ['id'=>$msg->writer->id])}}" data-toggle="tooltip" data-original-title="{{$msg->writer->parents(auth()->user()->role_id-1)}}">
                {{$msg->writer->username}} <span class="badge {{$badge_class[$msg->writer->role_id]}}">{{$msg->writer->role->description}}</span>
            </a>
            @endif
        @elseif (isset(\VanguardLTE\Message::RECV_NAME[$msg->writer_id]))
            {{\VanguardLTE\Message::RECV_NAME[$msg->writer_id]}}
        @else
        알수없음
        @endif
    </span>
    </td>
    <td>
        @if ($msg->user)
            @if ($msg->user->role_id > auth()->user()->role_id)
                총본사
            @else
            <a href="{{argon_route('argon.common.profile', ['id'=>$msg->user->id])}}" data-toggle="tooltip" data-original-title="{{$msg->user->parents(auth()->user()->role_id-1)}}">
                {{$msg->user->username}}<span class="badge {{$badge_class[$msg->user->role_id]}}">{{$msg->user->role->description}}</span>
            </a>
            @endif
        @elseif (isset(\VanguardLTE\Message::RECV_NAME[$msg->user_id]))
            {{\VanguardLTE\Message::RECV_NAME[$msg->user_id]}}
        @else
        알수없음
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
        @if ($msg->writer)
        <a class="newMsg viewMsg" href="#" data-toggle="modal" data-target="#openMsgModal" data-msg="{{ $msg->content }}" data-id="{{$msg->id}}" data-writer="{{$msg->writer->role_id>auth()->user()->role_id?'총본사':$msg->writer->username}}" data-parent="{{$msg->writer->role_id>auth()->user()->role_id?'총본사':$msg->writer->parents(auth()->user()->role_id-1)}}" data-title="{{$msg->title}}" onclick="viewMsg(this);">
            {{$msg->title}}
        </a>
        @else
        <a class="newMsg viewMsg" href="#" data-toggle="modal" data-target="#openMsgModal" data-msg="{{ $msg->content }}" data-id="{{$msg->id}}" data-writer="Unknown" data-parent="Unknown" data-title="{{$msg->title}}" onclick="viewMsg(this);">
            {{$msg->title}}
        </a>
        @endif
    </td>
	<td>{{ $msg->created_at }}</td>
    <td>{{ $msg->read_at??'읽지않음' }}</td>
    <td>{{ $msg->count }}</td>
    <td>
        @if (count($msg->refs)>0)
        <span class="text-green">답변함</span>
        @else
        <span class="text-red">답변없음</span>
        @endif
    </td>
    <td class="text-right">
    <?php
        if ($msg->writer_id == 0) //system message
        {
            $msg->content = preg_replace('/replace_with_backend/',config('app.admurl'),$msg->content);
        }
    ?>
        @if (auth()->user()->isInOutPartner() && $msg->user_id==auth()->user()->id)
        <a  href="{{argon_route('argon.msg.create', ['ref' => $msg->id, 'type'=>\Request::get('type')])}}" >
            <button class="btn btn-primary btn-sm">답변</button>
        </a>
        @endif
        <a href="{{ argon_route('argon.msg.delete', $msg->id) }}"
            data-method="DELETE"
            data-confirm-title="확인"
            data-confirm-text="쪽지를 삭제하시겠습니까?"
            data-confirm-delete="확인"
            data-confirm-cancel="취소">
            <button type="button" class="btn btn-warning btn-sm">삭제</button>
        </a>
    </td>