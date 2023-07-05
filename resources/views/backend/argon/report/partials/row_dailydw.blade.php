<?php 
    $badge_class = \VanguardLTE\User::badgeclass();
?>
<td><span class="{{$adjustment->user->role_id>3?'partner':'shop'}}">{{ $adjustment->user->username }}</span>&nbsp;<span class="badge {{$badge_class[$adjustment->user->role_id]}}">{{$adjustment->user->role->description}}</span></td>
@if ($adjustment->date=='')
<td></td>
@else
<td>{{ date('Y-m-d',strtotime($adjustment->date)) }}</td>
@endif
<?php
    if ($adjustment->date == date('Y-m-d'))
    {
        $inout = $adjustment->calcInOut();
        $adjustment->totalin = $inout['totalin'];
        $adjustment->totalout = $inout['totalout'];
        $adjustment->moneyin = $inout['moneyin'];
        $adjustment->moneyout = $inout['moneyout'];
    }
?>
<td><ul>
    <li>
        <span class='text-green'>충전 : {{number_format($adjustment->totalin)}}</span>
    </li>
    <li>
        <span class='text-red'>환전 : {{number_format($adjustment->totalout)}}</span>
    </li>
</ul></td>
<td><ul>
    <li>
        <span class='text-green'>충전 : {{number_format($adjustment->moneyin)}}</span>
    </li>
    <li>
        <span class='text-red'>환전 : {{number_format($adjustment->moneyout)}}</span>
    </li>
</ul></td>
<td>{{ number_format($adjustment->dealout,0) }}</td>
@if (auth()->user()->isInoutPartner())
<td><ul>
    <li class="bw-title">
        <span class='text-green'>배팅 : {{number_format($adjustment->totalbet)}}</span>
        @if (auth()->user()->hasRole('admin'))
        <div class="bw-btn ">
            <form method="POST">
                <input type='hidden' name="summaryid" value="{{$adjustment->id}}">
                <input type='text' name="totalbet" value="{{$adjustment->totalbet}}" style="width:80px;">
                <button type="submit" class="btn-sm btn-warning">수정</button>
            </form>
        </div>    
        @endif
    </li>
    <li class="bw-title">
        <span class='text-red'>당첨 : {{number_format($adjustment->totalwin)}}</span>
        @if (auth()->user()->hasRole('admin'))
        <div class="bw-btn ">
            <form method="POST">
                <input type='hidden' name="summaryid" value="{{$adjustment->id}}">
                <input type='text' name="totalwin"  value="{{$adjustment->totalwin}}" style="width:80px;">
                <button type="submit"class="btn-sm btn-warning">수정</button>
            </form>
        </div>
        @endif
    </li>
    <li>
        정산금 : {{ number_format($adjustment->totalbet-$adjustment->totalwin-$adjustment->total_deal,0) }}
    </li>
</ul></td>
@endif
<td><ul>
    <li class="bw-title">
        <span class='text-green'>배팅 : {{number_format($adjustment->totaldealbet)}}</span>
        @if (auth()->user()->hasRole('admin'))
        <div class="bw-btn ">
            <form method="POST">
                <input type='hidden' name="summaryid" value="{{$adjustment->id}}">
                <input type='text' name="totaldealbet" value="{{$adjustment->totaldealbet}}" style="width:80px;">
                <button type="submit" class="btn-sm btn-warning">수정</button>
            </form>
        </div>    
        @endif
    </li>
    <li class="bw-title">
        <span class='text-red'>당첨 : {{number_format($adjustment->totaldealwin)}}</span>
        @if (auth()->user()->hasRole('admin'))
        <div class="bw-btn ">
            <form method="POST">
                <input type='hidden' name="summaryid" value="{{$adjustment->id}}">
                <input type='text' name="totaldealwin"  value="{{$adjustment->totaldealwin}}" style="width:80px;">
                <button type="submit"class="btn-sm btn-warning">수정</button>
            </form>
        </div>
        @endif
    </li>
    <li>
        정산금 : {{ number_format($adjustment->totaldealbet-$adjustment->totaldealwin-$adjustment->total_deal,0) }}
    </li>
</ul></td>
<td>
    <ul>
    <li>총죽장 : {{ number_format($adjustment->total_ggr,0)}}</li>
    <li>하부죽장 : {{ number_format($adjustment->total_ggr_mileage,0)}}</li>
    <li>본인죽장 : {{ number_format($adjustment->total_ggr-$adjustment->total_ggr_mileage,0)}}</li>
    </ul>
</td>
<td>
    <ul>
    <li>총롤링 : {{ number_format($adjustment->total_deal,0)}}</li>
    <li>하부롤링 : {{ number_format($adjustment->total_mileage,0)}}</li>
    <li>본인롤링 : {{ number_format($adjustment->total_deal-$adjustment->total_mileage,0)}}</li>
    </ul>
</td>
<td>
    <ul>
    <li>총보유금 : {{ number_format($adjustment->balance+$adjustment->childsum,0)}}</li>
    <li>본인 보유금 : {{ number_format($adjustment->balance,0)}}</li>
    <li>유저 보유금 : {{ number_format($adjustment->user_sum,0)}}</li>
    <li>파트너 보유금 : {{ number_format($adjustment->partner_sum,0)}}</li>
    </ul>
</td>
<td>
    <ul>
    <li>총롤링금 : {{ number_format($adjustment->deal_balance - $adjustment->deal_mileage + $adjustment->partner_dealsum + $adjustment->user_dealsum,0)}}</li>
    <li>본인 롤링금 : {{ number_format($adjustment->deal_balance - $adjustment->deal_mileage,0)}}</li>
    <li>유저 롤링금 : {{ number_format($adjustment->user_dealsum,0)}}</li>
    <li>파트너 롤링금 : {{ number_format($adjustment->partner_dealsum,0)}}</li>
    </ul>
</td>
@if (isset($sumInfo) && $sumInfo!='')
@else
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
                $margin = ($adjustment->balance+$adjustment->childsum) - ($prevDay->balance + $prevDay->childsum + $adjustment->totalin - $adjustment->totalout + $adjustment->moneyin - $adjustment->moneyout + $adjustment->dealout - ($adjustment->totalbet-$adjustment->totalwin));
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
@endif