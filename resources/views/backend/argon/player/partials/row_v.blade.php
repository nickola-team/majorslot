<td>{{$user->id}}</td>
<td>
    <a href="#" data-toggle="tooltip" data-original-title="{{$user->parents(auth()->user()->role_id)}}">
        {{$user->username}}
    </a>
</td>
<td>{{$user->referral->username}}</td>
<td><span id="uid_{{$user->id}}">{{number_format($user->balance)}}</span>&nbsp;<a href="#" onclick="refreshPlayerBalance({{$user->id}});return false;" class="btn btn-xs btn-icon-only btn-info"><i class="fas fa-undo"></i></a></td>
<td>{{number_format($user->deal_balance)}}</td>
<td>{{number_format($user->total_in)}}</td>
<td>{{number_format($user->total_out)}}</td>
<td>{{$user->phone}}</td>
<td>
<span class="badge badge-dot mr-4">
    @if ($user->isLoggedIn())
        <i class="bg-success"></i>
        <span class="status">온라인</span>
    @else
        <i class="{{$status_class[$user->status]}}"></i>
        <span class="status">{{$statuses[$user->status]}}</span>
    @endif
</span>
</td>
<td>{{ $user->created_at }}</td>
<td>{{ $user->last_login }}</td>
<td class="text-right">
{{--<a href="{{argon_route('argon.common.balance', ['type' => 'add', 'id' => $user->id, 'url' => Request::getRequestUri()])}}" ><button class="btn btn-success btn-sm" >지 급</button></a>--}}
<a href="#" onclick="AddPayment('{{$user->id}}--{{$user->username}}');" data-toggle="modal" data-target="#AddPaymentModal" >
		<button type="button" class="btn btn-success btn-sm">지 급</button></a>
@if ($moneyperm || (auth()->user()->role_id==$user->role_id+1))
{{--<a href="{{argon_route('argon.common.balance', ['type' => 'out', 'id' => $user->id, 'url' => Request::getRequestUri()])}}"><button class="btn btn-warning btn-sm">회 수</button></a>--}}
<a href="#" onclick="OutPayment('{{$user->id}}--{{$user->username}}');" data-toggle="modal" data-target="#OutPaymentModal" >
		<button type="button" class="btn btn-success btn-sm">회 수</button></a>
@else
<a href="#"><button class="btn btn-warning btn-sm" disabled>회 수</button></a>
@endif
<a href="{{argon_route('argon.common.profile', ['id'=>$user->id])}}"><button class="btn btn-primary btn-sm" >설정</button></a>  
<a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
<button class="btn btn-danger btn-sm">기타<i class="dropdown-caret fa fa-caret-down"></i></button>
</a>
<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
    <a class="dropdown-item" href="{{argon_route('argon.msg.create', ['to' => $user->username, 'type'=>1])}}">쪽지보내기</a>
    <a class="dropdown-item" href="{{argon_route('argon.player.gamehistory', ['player' => $user->username])}}">게임내역</a>
    <a class="dropdown-item" href="{{argon_route('argon.player.transaction', ['user' => $user->username, 'role' => $user->role_id])}}">지급내역</a>
    <a class="dropdown-item" href="{{argon_route('argon.player.terminate', ['id' => $user->id])}}" data-method="DELETE"
                                data-confirm-title="확인"
                                data-confirm-text="유저의 게임을 종료하시겠습니까?"
                                data-confirm-delete="확인"
                                data-confirm-cancel="취소"
                                onClick="$(this).css('pointer-events', 'none');"
                                >게임종료</a>
    <a class="dropdown-item" href="{{argon_route('argon.player.logout', ['id' => $user->id])}}" onClick="$(this).css('pointer-events', 'none');">로그아웃</a>
</div>
</td>