<td>{{$user->id}}</td>
<td>
     {{$user->username}}
     @if ($user->role_id > 1)
     <?php 
        $badge_class = \VanguardLTE\User::badgeclass();
    ?>
        <span class="badge {{$badge_class[$user->role_id]}}">{{$user->role->description}}</span>
    @endif
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
    <a href="{{argon_route('argon.player.join', ['id' => $user->id, 'type'=>'allow'])}}"><button class="btn btn-success btn-sm">승인</button></a>
@if ($join)
    <a href="{{argon_route('argon.player.join', ['id' => $user->id, 'type'=>'stand'])}}"><button class="btn btn-info btn-sm">대기</button></a>
@endif    
    <a href="{{argon_route('argon.player.join', ['id' => $user->id, 'type'=>'reject'])}}"><button class="btn btn-warning btn-sm">취소</button></a>
</td>