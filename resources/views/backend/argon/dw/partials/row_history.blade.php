<td>{{$stat->id}}</td>
<?php 
    $badge_class = \VanguardLTE\User::badgeclass();
?>
<td>
    @if ($stat->user)
    <a href="#" data-toggle="tooltip" data-original-title="{{$stat->user->parents(auth()->user()->role_id)}}">
        {{$stat->user->username}} <span class="badge {{$badge_class[$stat->user->role_id]}}">{{$stat->user->role->description}}</span>
    </a>
    @else
        {{__('Unknown')}}
    @endif
</td>
@if ($stat->transaction)
<td>{{number_format($stat->transaction->old,0)}}</td>
<td>{{number_format($stat->transaction->new,0)}}</td>
@else
<td></td>
<td></td>
@endif

@if($stat->type == 'add')
<td><span class="text-success">{{number_format($stat->sum,0)}}</span></td>
<td><span class="text-success">충전</span></td>
@else
<td><span class="text-warning">{{number_format($stat->sum,0)}}</span></td>
<td><span class="text-warning">환전</span></td>
@endif
<td>
    {{$stat->bankinfo(!auth()->user()->isInOutPartner())}}
</td>
<td>{{$stat->created_at}}</td>
<td>{{$stat->updated_at}}</td>
<td>
    @if ($stat->status==1)
    <span class="text-green">승인</span>
    @else
    <span class="text-red">취소</span>
    @endif
</td>