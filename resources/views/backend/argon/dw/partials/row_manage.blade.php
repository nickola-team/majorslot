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
    @if ($stat->user->hasRole('manager'))
    {{number_format($stat->user->shop->balance,0)}}
    @else
    {{number_format($stat->user->balance,0)}}
    @endif
    @endif
</td>
<td>
    @if ($stat->type=='add')
    <span class="text-success">{{number_format($stat->sum,0)}}</span>
    @else
    <span class="text-warning">{{number_format($stat->sum,0)}}</span>
    @endif
</td>
<td>{{$stat->bankinfo(!auth()->user()->isInOutPartner())}}</td>
<td>{{$stat->created_at}}</td>
<td class="text-right">
<a href="{{argon_route('argon.dw.process', ['id' => $stat->id])}}" ><button class="btn btn-success btn-sm" >승인</button></a>
@if ($stat->status != \VanguardLTE\WithdrawDeposit::WAIT)
<a href="{{ route('frontend.api.wait_in_out', $stat->id) }}"><button class="btn btn-primary btn-sm" >대기</button></a>
@endif
    <a href="{{ argon_route('argon.dw.reject', ['id' => $stat->id]) }}" 
        data-method="DELETE"
        data-confirm-title="확인"
        data-confirm-text="신청을 취소하시겠습니까?"
        data-confirm-delete="확인"
        data-confirm-cancel="취소">
        <button class="btn btn-warning btn-sm" >취소</button>
    </a>
</td>