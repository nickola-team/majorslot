<td><span  class="{{$user->role_id>3?'partner':'shop'}}">{{$user->username}}</span></td>
<td>{{$user->role->description}}</td>
<td>{{number_format($user->balance)}}</td>
<td>{{ number_format($user->deal_balance - $user->mileage,0) }}</td>
<td>{{ number_format($user->deal_percent,2) }}</td>
<td>{{ number_format($user->table_deal_percent,2) }}</td>
