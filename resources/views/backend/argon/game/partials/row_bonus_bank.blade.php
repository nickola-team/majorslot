<?php 
    $badge_class = \VanguardLTE\User::badgeclass();
?>
<td>{{ $bank->id }} </td>
<td><a href="{{argon_route('argon.game.bonusbank', ['id'=>$bank->master->id])}}">{{ $bank->master->username }}  <span class="badge {{$badge_class[6]}}">본사</span></a></td>
<td>{{ $bank->games }}</td>
<td>{{ number_format($bank->totalBank) }} </td>
