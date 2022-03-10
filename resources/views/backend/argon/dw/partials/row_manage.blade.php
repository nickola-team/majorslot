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
<td>
    @if ($stat->user)
    {{number_format($stat->user->balance,0)}}
    @endif
</td>
<td><span class="text-success">{{number_format($stat->sum,0)}}</span></td>
<td>{{$stat->bankinfo()}}</td>
<td>{{$stat->created_at}}</td>
<td class="text-right">
<a href="{{argon_route('argon.dw.process', ['id' => $stat->id])}}" ><button class="btn btn-success btn-sm" >승인</button></a>
@if ($stat->status != \VanguardLTE\WithdrawDeposit::WAIT)
<a href="{{ route('frontend.api.wait_in_out', $stat->id) }}"><button class="btn btn-primary btn-sm" >대기</button></a>
@endif
<a href="{{ argon_route('argon.dw.reject', ['id' => $stat->id]) }}"><button class="btn btn-warning btn-sm" >취소</button></a>
</td>