<td><span  class="{{$user->role_id>3?'partner':'shop'}}">{{$user->username}}</span></td>
<td>{{$user->id}}</td>
<?php 
    $badge_class = \VanguardLTE\User::badgeclass();
?>
<td><span class="badge {{$badge_class[$user->role_id]}}">{{$user->role->description}}</span></td>
@if ($user->hasRole('manager'))
<td>{{number_format($user->shop->balance)}}</td>
<td>{{ number_format($user->shop->deal_balance - $user->shop->mileage,0) }}</td>
<td>{{ number_format($user->shop->deal_percent,2) }}</td>
<td>{{ number_format($user->shop->table_deal_percent,2) }}</td>
@else
<td>{{number_format($user->balance)}}</td>
<td>{{ number_format($user->deal_balance - $user->mileage,0) }}</td>
<td>{{ number_format($user->deal_percent,2) }}</td>
<td>{{ number_format($user->table_deal_percent,2) }}</td>
@endif
<td>{{ $user->created_at }}</td>
<td class="text-right">
<a href="{{argon_route('argon.common.balance', ['type' => 'add', 'id' => $user->id, 'url' => Request::getRequestUri()])}}" ><button class="btn btn-success btn-sm" {{(auth()->user()->isInOutPartner() || (auth()->user()->role_id==$user->role_id+1)?'':'disabled')}}>충 전</button></a>
<a href="{{argon_route('argon.common.balance', ['type' => 'out', 'id' => $user->id, 'url' => Request::getRequestUri()])}}"><button class="btn btn-warning btn-sm" {{(auth()->user()->isInOutPartner() || (auth()->user()->role_id==$user->role_id+1)?'':'disabled')}}>환 전</button></a>

<div class="dropdown">
    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-ellipsis-v"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
        <a class="dropdown-item" href="{{argon_route('argon.common.profile', ['id'=>$user->id])}}">설정 및 정보</a>
        <hr class="my-1">
        @if (auth()->user()->hasRole('admin'))
        <a class="dropdown-item" href="{{argon_route('argon.agent.move', ['id' => $user->id])}}">에이전트 이동</a>
        @endif
        <a class="dropdown-item" href="{{argon_route('argon.agent.dealstat', ['user' => $user->username])}}">롤링내역</a>
        <a class="dropdown-item" href="{{argon_route('argon.agent.transaction', ['user' => $user->username, 'role' => $user->role_id])}}">지급내역</a>
    </div>
</div>
</td>