<?php
        $game = explode(' ', $stat->game)[0];
		$game = preg_replace('/^_/', '', $game);
        $cc = explode('_', $game);
        if (count($cc) > 1)
        {
            array_pop($cc);
            $game = implode(' ', $cc);
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
    @if ($stat->partner)
        {{$stat->partner->username}}
    @else
        {{__('Unknown')}}
    @endif
</td>
<td>
    @if (auth()->user()->hasRole('comaster'))
        상위파트너
    @else
        @if ($stat->shareuser)
            {{$stat->shareuser->username}}
        @else
            {{__('Unknown')}}
        @endif
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

<td>
<ul>
    <li>총배팅금 : {{ number_format($stat->bet,0)}}</li>
    <li>파트너배팅금 : {{ number_format($stat->betlimit,0)}}</li>
    <li>받치기배팅금 : {{ number_format($stat->bet - $stat->betlimit,0)}}</li>
</ul>
</td>
<td><ul>
    <li>총당첨금 : {{ number_format($stat->win,0)}}</li>
    <li>파트너당첨금 : {{ number_format($stat->winlimit,0)}}</li>
    <li>받치기당첨금 : {{ number_format($stat->win - $stat->winlimit,0)}}</li>
</ul>
</td>
<td>{{number_format($stat->deal_percent,2)}}</td>
<td><ul>
    <li>총롤링금 : {{ number_format(($stat->bet * $stat->deal_percent) / 100,0)}}</li>
    <li>파트너롤링금 : {{ number_format($stat->deal_limit,0)}}</li>
    <li>받치기롤링금 : {{ number_format($stat->deal_share,0)}}</li>
</ul>
</td>

<td>{{ $stat->date_time }}</td>
<td>
<a href="{{argon_route('argon.player.gamedetail', ['statid' => $stat->stat_id])}}" onclick="window.open(this.href,'newwindow','width=1000,height=800'); return false;"><button class="btn btn-success btn-sm">상세보기</button></a>
</td>
