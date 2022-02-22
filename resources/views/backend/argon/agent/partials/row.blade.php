<td><span  class="{{$user->role_id>3?'partner':'shop'}}">{{$user->username}}</span></td>
<?php 
    $badge_class = [
        'badge-default',
        'badge-primary',
        'badge-danger',
        'badge-success',
        'badge-info',
        'badge-warning',
    ]
?>
<td><span class="badge {{$badge_class[$user->role_id-3]}}">{{$user->role->description}}</span></td>
<td>{{number_format($user->balance)}}</td>
<td>{{ number_format($user->deal_balance - $user->mileage,0) }}</td>
<td>{{ number_format($user->deal_percent,2) }}</td>
<td>{{ number_format($user->table_deal_percent,2) }}</td>
<td>{{ $user->created_at }}</td>
<td class="text-right">
<div class="dropdown">
    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-ellipsis-v"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
        <a class="dropdown-item" href="{{argon_route('argon.common.profile', ['id'=>$user->id])}}">설정 및 정보</a>
        <hr class="my-1">
        <a class="dropdown-item" href="{{argon_route('argon.common.balance', ['type' => 'add', 'id' => $user->id, 'url' => argon_route('argon.agent.list')])}}">충 전</a>
        <a class="dropdown-item" href="{{argon_route('argon.common.balance', ['type' => 'out', 'id' => $user->id, 'url' => argon_route('argon.agent.list')])}}">환 전</a>
        <a class="dropdown-item" href="{{argon_route('argon.agent.transaction', ['user' => $user->username, 'role' => $user->role_id])}}">지급내역</a>
    </div>
</div>
</td>