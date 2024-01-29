<?php 
    $badge_class = \VanguardLTE\User::badgeclass();
?>
<td><span class="{{$adjustment->user->role_id>3?'partner':'shop'}}">{{ $adjustment->user->username }}</span>&nbsp;<span class="badge {{$badge_class[$adjustment->user->role_id]}}">{{$adjustment->user->role->description}}</span></td>
<td>{{ date('Y-m-d',strtotime($adjustment->date)) }}</td>
<td>{{ number_format($adjustment->totalbet,0)}}</td>
<td>{{ number_format($adjustment->totalwin,0)}}</td>
<td>{{ number_format($adjustment->totalbet - $adjustment->totalwin,0) }}</td>
<td>
    <ul>
    <li>총롤링금 : {{ number_format($adjustment->total_deal,0)}}</li>
    <li>하부롤링금 : {{ number_format($adjustment->total_mileage,0)}}</li>
    <li>본인롤링금 : {{ number_format($adjustment->total_deal-$adjustment->total_mileage,0)}}</li>
    </ul>
</td>
