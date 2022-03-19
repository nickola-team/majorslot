<?php
        $game = preg_replace('/PM/', '_pp', $stat->game);
        $game = preg_replace('/HBN/', '_HBN', $game);
        $game = preg_replace('/CQ9/', '_cq9', $game);
        $game = explode(' ', $game)[0];
		$game = preg_replace('/^_/', '', $game);
        $cc = explode('_', $game);
        if (count($cc) > 1)
        {
            $game = $cc[0];
        }
	?>
<td>
    @if ($stat->user)
        {{$stat->user->username}}
    @else
        {{__('Unknown')}}
    @endif
</td>
<td> {{$game}} </td>
<td> {{$stat->category->trans->trans_title}}</td>
<td>{{number_format($stat->balance,0)}}</td>
<td>{{number_format($stat->bet,0)}}</td>
<td>{{number_format($stat->win,0)}}</td>
<td>{{ $stat->date_time }}</td>
