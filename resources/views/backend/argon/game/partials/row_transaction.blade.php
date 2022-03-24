<td>{{$stat->id}}</td>
<td>{{$stat->name}}</td>
<td>{{number_format($stat->old,0)}}</td>
<td>{{number_format($stat->new,0)}}</td>
@if($stat->type == 'add')
<td><span class="text-success">{{number_format($stat->sum,0)}}</span></td>
<td><span class="text-success">{{__($stat->type)}}</span></td>
@else
<td><span class="text-warning">{{number_format($stat->sum,0)}}</span></td>
<td><span class="text-warning">{{__($stat->type)}}</span></td>
@endif
<td>{{$stat->created_at}}</td>