<tr>
<td>
    <a href="{{argon_route('argon.report.user.details', ['user_id'=>$stat->user->id, 'dates'=>Request::get('dates')])}}">{{$stat->user->username}}</a>
</td>
<td>{{number_format($stat->totalbet)}}</td>
<td>{{number_format($stat->totalwin)}}</td>
<td>
@if ($stat->totalbet > $stat->totalwin)
<span class='text-green'>{{number_format($stat->totalbet - $stat->totalwin)}}</span>
@else
<span class='text-red'>{{number_format($stat->totalbet - $stat->totalwin)}}</span>
@endif
</td>
</tr>