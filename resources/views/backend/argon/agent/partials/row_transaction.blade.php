<td>
    @if ($stat->user)
        {{$stat->user->username}}
        [{{__(\App\Models\User::ROLE_NAMES[$stat->user->role_id])}}]
    @else
    {{__('Unknown')}}
    @endif
</td>
<td>
    @if ($stat->payeer)
    {{$stat->payeer->username}}
    @else
    {{__('Unknown')}}
    @endif
</td>
<td>
    @if ($stat->payeer && auth()->user()->role_id >= $stat->payeer->role_id)
        {{number_format($stat->balance,0)}}
    @else
        0
    @endif
</td>
<td>{{number_format($stat->old,0)}}</td>
<td>{{number_format($stat->new,0)}}</td>
@if($stat->type == 'add')
<td><span class="text-green">{{__('AddBalance')}}</span></td>
<td><span class="text-green">{{number_format($stat->summ,0)}}</span></td>
@else
<td><span class="text-red">{{__('OutBalance')}}</span></td>
<td><span class="text-red">{{number_format($stat->summ,0)}}</span></td>
@endif
<td>{{$stat->created_at}}</td>