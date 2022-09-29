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
        if ($gacmerge == 1)
        {
            $game = preg_replace('/포츈스피드바카라/', 'VIP스피드바카라', $game);
        }
	?>
<td>
    @if ($stat->user)
        {{$stat->user->username}}
    @else
        {{__('Unknown')}}
    @endif
</td>
<td>
    @if ($stat->user)
    <a href="#" data-toggle="tooltip" data-original-title="{{$stat->user->parents(auth()->user()->role_id)}}">
        {{$stat->user->parents(auth()->user()->role_id-1, auth()->user()->role_id-1)}}
    </a>
    @else
        {{__('Unknown')}}
    @endif
</td>
<td> 
    @if ($stat->category)
        @if ($stat->category->trans)
            {{$stat->category->trans->trans_title}}
        @else
            {{$stat->category->title}}
        @endif
    @else
    {{__('Unknown')}}
    @endif
</td>
<td> {{$game}} </td>
<td>{{number_format($stat->balance,0)}}</td>
<td>{{number_format($stat->bet,0)}}</td>
<td>{{number_format($stat->win,0)}}</td>
<td>{{ $stat->date_time }}</td>
