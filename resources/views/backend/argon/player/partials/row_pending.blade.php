<?php
    $statdata = json_decode($stat->data,true);
    $table = \VanguardLTE\Http\Controllers\Web\GameProviders\GACController::getGameObj($statdata['tableName']);
    if ($table == null)
    {
        $table = \VanguardLTE\Http\Controllers\Web\GameProviders\GACController::getGameObj('unknowntable');
    }
?>
<td>
    {{$stat->user->username}}
</td>
<td> 
    에볼루션
</td>
<td> {{$table['title']}} </td>
<td>
    {{number_format($statdata['betAmount'])}}
</td>
<td>{{ $stat->date_time }}</td>
<td>미결중</td>
@if (auth()->user()->hasRole('admin'))
<td>
<a href="{{argon_route('argon.player.cancelgame', ['id' => $stat->id])}}" ><button class="btn btn-success btn-sm">취소처리</button></a>
</td>
@endif
