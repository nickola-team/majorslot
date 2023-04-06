<td>{{$user->id}}</td>
<td>
    {{$user->username}}
</td>

<td>{{number_format($user->balance)}}</td>
<td>{{ number_format($user->deal_balance - $user->mileage,0) }}</td>
<td>{{$user->referral->username}}</td>
<td>{{number_format(1000000)}}</td>
<td><span class="badge badge-success">에볼루션</span>&nbsp;&nbsp;<span class="badge badge-success">오리엔탈</span></td>
<td></td>