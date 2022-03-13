<td><span class="{{$adjustment->user->role_id>3?'partner':'shop'}}">{{ $adjustment->user->username }}</span></td>
<td>{{ date('Y-m-d',strtotime($adjustment->date)) }}</td>
<td>{{ number_format($adjustment->totalbet,0)}}</td>
<td>{{ number_format($adjustment->totalwin,0)}}</td>
<td>{{ number_format($adjustment->totalbet - $adjustment->totalwin,0) }}</td>
<td>{{ number_format($adjustment->total_deal-$adjustment->total_mileage,0)}}</td>
