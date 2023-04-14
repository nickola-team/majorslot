<?php 
    $badge_class = \VanguardLTE\User::badgeclass();
?>
<td><span class="partner">{{ $adjustment->user->username }}</span>&nbsp;<span class="badge {{$badge_class[$adjustment->user->role_id]}}">{{$adjustment->user->role->description}}</span></td>
<td>{{ date('Y-m-d',strtotime($adjustment->date)) }}</td>
<td><ul>
    <li>총배팅금 : {{ number_format($adjustment->bet,0)}}</li>
    <li>파트너배팅금 : {{ number_format($adjustment->betlimit,0)}}</li>
    <li>받치기배팅금 : {{ number_format($adjustment->bet - $adjustment->betlimit,0)}}</li>
</ul></td>
<td><ul>
    <li>총당첨금 : {{ number_format($adjustment->win,0)}}</li>
    <li>파트너당첨금 : {{ number_format($adjustment->winlimit,0)}}</li>
    <li>받치기당첨금 : {{ number_format($adjustment->win - $adjustment->winlimit,0)}}</li>
</ul></td>
<td><ul>
    <li>총벳윈 : {{ number_format($adjustment->bet - $adjustment->win,0)}}</li>
    <li>파트너벳원 : {{ number_format($adjustment->betlimit - $adjustment->winlimit,0)}}</li>
    <li>받치기벳원 : {{ number_format($adjustment->bet - $adjustment->win - $adjustment->betlimit + $adjustment->winlimit,0)}}</li>
</ul></td>

<td><ul>
    <li>총롤링금 : {{ number_format($adjustment->deal_limit + $adjustment->deal_share,0)}}</li>
    <li>파트너롤링금 : {{ number_format($adjustment->deal_limit,0)}}</li>
    <li>받치기롤링금 : {{ number_format($adjustment->deal_share,0)}}</li>
</ul></td>

<td>{{ number_format($adjustment->deal_out,0) }}</td>
