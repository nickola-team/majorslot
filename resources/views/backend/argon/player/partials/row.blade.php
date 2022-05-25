<td>{{$user->id}}</td>
<td>
    <a href="#" data-toggle="tooltip" data-original-title="{{$user->parents(auth()->user()->role_id)}}">
        {{$user->username}}
    </a>
</td>
<td>{{$user->referral->username}}</td>
<td>{{number_format($user->balance)}}</td>
<td>{{ number_format($user->deal_balance - $user->mileage,0) }}</td>
<td>{{number_format($user->total_in)}}</td>
<td>{{number_format($user->total_out)}}</td>

<td>
<span class="badge badge-dot mr-4">
    <i class="{{$status_class[$user->status]}}"></i>
    <span class="status">{{$statuses[$user->status]}}</span>
</span>
</td>
<td>{{ $user->created_at }}</td>
<td>{{ $user->last_login }}</td>
<td class="text-right">
<a href="{{argon_route('argon.common.balance', ['type' => 'add', 'id' => $user->id, 'url' => Request::getRequestUri()])}}" ><button class="btn btn-success btn-sm" {{(auth()->user()->isInOutPartner() || auth()->user()->hasRole('manager')?'':'disabled')}}>충 전</button></a>
<a href="{{argon_route('argon.common.balance', ['type' => 'out', 'id' => $user->id, 'url' => Request::getRequestUri()])}}"><button class="btn btn-warning btn-sm" {{(auth()->user()->isInOutPartner() || auth()->user()->hasRole('manager')?'':'disabled')}}>환 전</button></a>
<a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fas fa-ellipsis-v"></i>
</a>
<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
    <a class="dropdown-item" href="{{argon_route('argon.common.profile', ['id'=>$user->id])}}">설정 및 정보</a>
    <hr class="my-1">
    <a class="dropdown-item" href="{{argon_route('argon.msg.create', ['to' => $user->username])}}">쪽지보내기</a>
    <a class="dropdown-item" href="{{argon_route('argon.player.gamehistory', ['player' => $user->username])}}">게임내역</a>
    <a class="dropdown-item" href="{{argon_route('argon.player.transaction', ['user' => $user->username, 'role' => $user->role_id])}}">지급내역</a>
</div>
</td>