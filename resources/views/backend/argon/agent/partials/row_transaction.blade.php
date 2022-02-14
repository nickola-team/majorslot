<td>{{$stat->id}}</td>
<td>
    @if ($stat->user)
    <a href="#" data-toggle="tooltip" data-original-title="{{$stat->user->parents()}}">
        {{$stat->user->username}} [{{$stat->user->role->description}}]
    </a>
    @else
        {{__('Unknown')}}
    @endif
</td>
<td>
    @if ($stat->admin)
    {{$stat->admin->username}} [{{$stat->admin->role->description}}]
    @else
    {{__('Unknown')}}
    @endif
</td>
<td>
    @if ($stat->admin && auth()->user()->role_id >= $stat->admin->role_id)
        {{number_format($stat->balance,0)}}
    @else
        0
    @endif
</td>
<td>{{number_format($stat->old,0)}}</td>
<td>{{number_format($stat->new,0)}}</td>
@if($stat->type == 'add')
<td><span class="text-success">{{number_format($stat->summ,0)}}</span></td>
<td><span class="text-success">{{__($stat->type)}}</span></td>
@else
<td><span class="text-warning">{{number_format($stat->summ,0)}}</span></td>
<td><span class="text-warning">{{__($stat->type)}}</span></td>
@endif
<td>
@if ($stat->request_id != null)
<a href="#" data-toggle="tooltip" data-original-title="{{$stat->requestInfo->bankinfo()}}">
<span class="badge badge-success">계좌</span>
</a>
@else
<span class="badge badge-primary">수동</span>
@endif
</td>
<td>{{$stat->created_at}}</td>