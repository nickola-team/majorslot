<td><span class="{{$adjustment->user->role_id>3?'partner':'shop'}}">{{ $adjustment->user->username }}</span></td>
<td>{{ date('Y-m-d',strtotime($adjustment->date)) }}</td>
<td>{{ number_format($adjustment->totalin,0) }}</td>
<td>{{ number_format($adjustment->totalout,0) }}</td>
<td>{{ number_format($adjustment->moneyin,0) }}</td>
<td>{{ number_format($adjustment->moneyout,0) }}</td>
@if(auth()->user()->isInoutPartner())
    <td>{{ number_format($adjustment->totalin - $adjustment->totalout,0) }}</td>
@endif
<td>{{ number_format($adjustment->dealout,0) }}</td>