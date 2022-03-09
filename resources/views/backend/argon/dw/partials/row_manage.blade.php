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

<td><span class="text-success">{{number_format($stat->sum,0)}}</span></td>
<td>{{$stat->bankinfo()}}</td>
<td>{{$stat->created_at}}</td>
<td>
    @if ($stat->status==1)
    <span class="text-green">승인</span>
    @else
    <span class="text-red">취소</span>
    @endif
</td>