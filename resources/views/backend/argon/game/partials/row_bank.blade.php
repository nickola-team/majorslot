<?php 
    $badge_class = \VanguardLTE\User::badgeclass();
?>
<td>{{ $bank->id }}</td>
<td>{{ $bank->shop->name }} <span class="badge {{$badge_class[3]}}">매장</span></td>
<td>{{ $bank->game?$bank->game->name:'공통' }}</td>
<td>{{ $bank->shop->percent }}</td>
<td>{{ number_format($bank->slots_01,0) }}</td>
<td>{{ number_format($bank->slots_02,0) }}</td>
<td>{{ number_format($bank->slots_03,0) }}</td>
<td>{{ number_format($bank->slots_04,0) }}</td>
<td>{{ number_format($bank->slots_05,0) }}</td>
<td> <a href="{{argon_route('argon.game.bankbalance', ['batch'=>0,'type'=>'slots','id'=>$bank->id])}}">환수금수정 <i class="fa fa-arrow-circle-right"></i></a></td>
