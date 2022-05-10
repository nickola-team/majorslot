<?php 
    $badge_class = \VanguardLTE\User::badgeclass();
?>
<td><span class="{{$adjustment->user->role_id>3?'partner':'shop'}}">{{ $adjustment->user->username }}</span>&nbsp;<span class="badge {{$badge_class[$adjustment->user->role_id]}}">{{$adjustment->user->role->description}}</span></td>
<td>{{ date('Y-m-d',strtotime($adjustment->date)) }}</td>
<td>{{ number_format($adjustment->totalin,0) }}</td>
<td>{{ number_format($adjustment->totalout,0) }}</td>
<td>{{ number_format($adjustment->moneyin,0) }}</td>
<td>{{ number_format($adjustment->moneyout,0) }}</td>
<td>{{ number_format($adjustment->dealout,0) }}</td>
<td>{{ number_format($adjustment->totalbet-$adjustment->totalwin,0) }}</td>
<td>{{ number_format($adjustment->balance+$adjustment->childsum,0)}}</td>
@if (auth()->user()->hasRole('admin'))
<td>
    <?php
        $prevDay = $adjustment->prevDay;
        $margin = 0;
        if ($prevDay)
        {
            if ($adjustment->user->isInoutPartner())
            {
                $margin = ($adjustment->balance+$adjustment->childsum) - ($prevDay->balance + $prevDay->childsum + $adjustment->moneyin - $adjustment->moneyout - ($adjustment->totalbet-$adjustment->totalwin));
            }
            else
            {
                $margin = ($adjustment->balance+$adjustment->childsum) - ($prevDay->balance + $prevDay->childsum + +$adjustment->totalin - $adjustment->totalout + $adjustment->moneyin - $adjustment->moneyout + $adjustment->dealout - ($adjustment->totalbet-$adjustment->totalwin));
            }
        }
        
    ?>
    @if ($margin > 0)
    <span class="text-red">{{number_format($margin, 0)}}</span>
    @else
    <span class="text-green">{{number_format($margin, 0)}}</span>
    @endif
</td>
@endif
@if(auth()->user()->isInoutPartner())
    <td>{{ number_format($adjustment->totalin - $adjustment->totalout,0) }}</td>
@endif
