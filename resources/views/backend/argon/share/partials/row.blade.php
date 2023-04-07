<td>{{$user->id}}</td>
<td>
    {{$user->username}}
</td>

<td>{{number_format($user->balance)}}</td>
<td>{{ number_format($user->deal_balance - $user->mileage,0) }}</td>
<td>{{$user->referral->username}}</td>
<td>
    <ul>
        @if (count($user->sharebetinfo) > 0)
        @foreach ($user->sharebetinfo as $info)
        <li>{{$info->category->trans?$info->category->trans->trans_title: $info->category->title}} : {{ number_format($info->minlimit)}}</li>
        @endforeach
        @else
        <li><span class="text-red">설정없음</span></li>
        @endif
    </ul>
</td>
<td>
<a href="{{argon_route('argon.share.setting', ['id' => $user->id])}}" ><button class="btn btn-success btn-sm" >설정</button></a>
</td>