<?php
        $game = preg_replace('/PM/', '_pp', $stat->game);
        $game = preg_replace('/HBN/', '_HBN', $game);
        $game = preg_replace('/CQ9/', '_cq9', $game);
        $game = explode(' ', $game)[0];
		$game = preg_replace('/^_/', '', $game);
        $cc = explode('_', $game);
        if (count($cc) > 1)
        {
            array_pop($cc);
            $game = implode(' ', $cc);
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
        <?php
            $roleid = auth()->user()->role_id;
            if (auth()->user()->hasRole('admin'))
            {
                $roleid = $roleid - 1;
            }

        ?>
        {{$stat->user->parents($roleid-1, $roleid-1)}}
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
<td>
    @if ($stat->category && $stat->category->href == 'habaneroplay')
    -1
    @else
    {{number_format($stat->balance,0)}}
    @endif
</td>
<td>{{number_format($stat->bet,0)}}</td>
<td>{{number_format($stat->win,0)}}</td>
<td>{{ $stat->date_time }}</td>
<td>
    @if ($stat->status==1)
    <span class="text-success">적중</span>
    @else
    <span class="text-warning">미적중</span>
    @endif
</td>
<td>
<a href="{{argon_route('argon.player.gamedetail', ['statid' => $stat->id])}}" onclick="window.open(this.href,'newwindow','width=1000,height=800'); return false;"><button class="btn btn-success btn-sm">상세보기</button></a>
</td>
