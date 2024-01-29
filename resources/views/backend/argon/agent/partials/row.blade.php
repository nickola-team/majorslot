<td><span  class="{{$user->role_id>3?'partner':'shop'}}">{{$user->username}}</span></td>
<td>{{$user->id}}</td>
<?php 
    $badge_class = \VanguardLTE\User::badgeclass();
?>
<td><span class="badge {{$badge_class[$user->role_id]}}">{{$user->role->description}}</span></td>
@if ($user->hasRole('manager'))
<td>{{number_format($user->shop->balance)}}</td>
<td>{{ number_format($user->shop->deal_balance - $user->shop->mileage,0) }}</td>
<td>
    <ul>
    <li>슬롯 : {{ number_format($user->shop->deal_percent,2) }}</li>
    <li>라이브 : {{ number_format($user->shop->table_deal_percent,2) }}</li>
    <li>스포츠 : {{ number_format($user->shop->sports_deal_percent,2) }}</li>
    <li>단폴(파워볼) : {{ number_format($user->shop->pball_single_percent,2) }}</li>
    <li>조합(파워볼) : {{ number_format($user->shop->pball_comb_percent,2) }}</li>
    </ul>
</td>
<td>
    <ul>
    <li>슬롯 : {{ number_format($user->shop->ggr_percent,2) }}</li>
    <li>라이브 : {{ number_format($user->shop->table_ggr_percent,2) }}</li>
    </ul>
</td>
@else
<td>{{number_format($user->balance)}}</td>
<td>{{ number_format($user->deal_balance - $user->mileage,0) }}</td>
<td><ul>
    <li>슬롯 : {{ number_format($user->deal_percent,2) }}</li>
    <li>라이브 : {{ number_format($user->table_deal_percent,2) }}</li>
    <li>스포츠 : {{ number_format($user->sports_deal_percent,2) }}</li>
    <li>단폴(파워볼) : {{ number_format($user->pball_single_percent,2) }}</li>
    <li>조합(파워볼) : {{ number_format($user->pball_comb_percent,2) }}</li>
    </ul>
</td>
<td><ul>
    <li>슬롯 : {{ number_format($user->ggr_percent,2) }}</li>
    <li>라이브 : {{ number_format($user->table_ggr_percent,2) }}</li>
    </ul>
</td>
@endif
<td>{{ $user->created_at }}</td>
<td class="text-right">
@if ($user->status == \VanguardLTE\Support\Enum\UserStatus::ACTIVE)
{{--<a href="{{argon_route('argon.common.balance', ['type' => 'add', 'id' => $user->id, 'url' => argon_route('argon.agent.list')])}}" ><button class="btn btn-success btn-sm" >지 급</button></a>--}}
@if ($user->hasRole('manager'))
<a href="#" onclick="AddPayment('{{$user->id}}--{{number_format($user->shop->balance)}}--{{$user->role->description}}--{{$user->username}}');" data-toggle="modal" data-target="#AddPaymentModal" >
		<button type="button" class="btn btn-success btn-sm">지 급</button></a>
@else
<a href="#" onclick="AddPayment('{{$user->id}}--{{number_format($user->balance)}}--{{$user->role->description}}--{{$user->username}}');" data-toggle="modal" data-target="#AddPaymentModal" >
		<button type="button" class="btn btn-success btn-sm">지 급</button></a>
@endif
@if ($moneyperm || (auth()->user()->role_id==$user->role_id+1))
{{--<a href="{{argon_route('argon.common.balance', ['type' => 'out', 'id' => $user->id, 'url' => argon_route('argon.agent.list')])}}"><button class="btn btn-warning btn-sm">회 수</button></a>--}}
@if ($user->hasRole('manager'))
<a href="#" onclick="OutPayment('{{$user->id}}--{{number_format($user->shop->balance)}}--{{$user->role->description}}--{{$user->username}}');" data-toggle="modal" data-target="#OutPaymentModal" >
		<button type="button" class="btn btn-success btn-sm">회 수</button></a>
@else
<a href="#" onclick="OutPayment('{{$user->id}}--{{number_format($user->balance)}}--{{$user->role->description}}--{{$user->username}}');" data-toggle="modal" data-target="#OutPaymentModal" >
		<button type="button" class="btn btn-success btn-sm">회 수</button></a>
@endif
@else
<a href="#"><button class="btn btn-warning btn-sm" disabled>회 수</button></a>
@endif
@endif
<a href="{{argon_route('argon.common.profile', ['id'=>$user->id])}}"><button class="btn btn-primary btn-sm" >설정</button></a>
@if ($user->status == \VanguardLTE\Support\Enum\UserStatus::ACTIVE)
<div class="dropdown">
    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <button class="btn btn-danger btn-sm">기타<i class="dropdown-caret fa fa-caret-down"></i></button>
    </a>
    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
        @if (auth()->user()->hasRole('admin'))
        <a class="dropdown-item" href="{{argon_route('argon.agent.move', ['id' => $user->id])}}">파트너 이동</a>
        @endif
        <a class="dropdown-item" href="{{argon_route('argon.agent.dealstat', ['user' => $user->username])}}">롤링내역</a>
        <a class="dropdown-item" href="{{argon_route('argon.agent.transaction', ['user' => $user->username, 'role' => $user->role_id])}}">지급내역</a>
    </div>
</div>
@endif
</td>