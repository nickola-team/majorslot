<td>{{$user->id}}</td>
<td>
     {{$user->username}}
</td>
<td>
@if ($user->referral)
    <a href="#" data-toggle="tooltip" data-original-title="{{$user->referral->parents(auth()->user()->role_id)}}">
        {{$user->referral->username}}
    </a>
@endif
</td>
<td>{{$user->phone}}</td>
<td>{{$user->bankInfo()}}</td>
<td>{{ $user->created_at }}</td>
<td class="text-right">
    <button class="btn btn-success btn-sm">승인</button>
    <button class="btn btn-warning btn-sm">취소</button>
</td>