<?php
    $statdata = json_decode($stat->data,true);
    $table = null;
    if (isset($statdata['tableName'])){
        $table = \VanguardLTE\Http\Controllers\Web\GameProviders\GACController::getGameObj($statdata['tableName']);
    }
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
    {{number_format(isset($statdata['betAmount'])?$statdata['betAmount']:0)}}
</td>
<td>{{ $stat->date_time }}</td>
<td>미결중</td>
@if (auth()->user()->hasRole('admin'))
<td>
<a href="{{argon_route('argon.player.processgame', ['id' => $stat->id])}}" ><button class="btn btn-success btn-sm">수동처리</button></a>
</td>
@endif
