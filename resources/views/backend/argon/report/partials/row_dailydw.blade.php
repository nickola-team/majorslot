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
    $betwin = $adjustment->betwin();
?>
<td><ul>
    <li>
        <span class='text-green'>충전 : {{number_format($adjustment->totalin)}}</span>
    </li>
    <li>
        <span class='text-red'>환전 : {{number_format($adjustment->totalout)}}</span>
    </li>
    <li>
        정산금 : {{number_format($adjustment->totalin - $adjustment->totalout)}}
    </li>
</ul></td>
<td><ul>
    <li>
        <span class='text-green'>충전 : {{number_format($adjustment->moneyin)}}</span>
    </li>
    <li>
        <span class='text-red'>환전 : {{number_format($adjustment->moneyout)}}</span>
    </li>
    <li>
        정산금 : {{number_format($adjustment->moneyin - $adjustment->moneyout)}}
    </li>
</ul></td>
<td>{{ number_format($adjustment->dealout,0) }}</td>
@if (auth()->user()->isInoutPartner())
<td>
@if (isset($sumInfo) && $sumInfo!='')
    <span class='text-red'>준비중</span>
@else
@foreach ($betwin as $type => $bt)
    <div class="d-flex">
    <div class="d-flex" style="justify-content : center;align-items : center;">
            {{__($type)}}
    </div>
    <div class="d-flex flex-column justify-content-center" style="margin-left:1.6rem">
        <ul>
        <li>
            <span class='text-green'>배팅 : {{number_format($bt['totalbet'])}}</span>
        </li>
        <li>
            <span class='text-red'>당첨 : {{number_format($bt['totalwin'])}}</span>
        </li>
        <li>
<<<<<<< HEAD
        롤링금 : {{number_format($bt['total_deal']>0?$bt['total_deal']:$bt['total_mileage'])}}
        </li>
        <li>
        정산금 : {{number_format($bt['totalbet']-$bt['totalwin'] - ($bt['total_deal']>0?$bt['total_deal']:$bt['total_mileage']))}}
=======
        롤링금 : {{number_format($bt['total_mileage'])}}
        </li>
        <li>
        정산금 : {{number_format($bt['totalbet']-$bt['totalwin'] - $bt['total_mileage'])}}
>>>>>>> 6e42aae5246f761b007c8e7f726bd4c247a5c346
        </li>
        </ul>
    </div>
    
    </div>
    @if ($loop->index < count($betwin)-1)
    <hr style="margin-top:0.5rem !important; margin-bottom:0.5rem !important;">
    @endif
@endforeach
@endif
</td>
@endif
<td>
@if (isset($sumInfo) && $sumInfo!='')
    <span class='text-red'>준비중</span>
@else
@foreach ($betwin as $type => $bt)
    <div class="d-flex">
    <div class="d-flex" style="justify-content : center;align-items : center;">
            {{__($type)}}
    </div>
    <div class="d-flex flex-column justify-content-center" style="margin-left:1.6rem">
        <ul>
        <li>
            <span class='text-green'>배팅 : {{number_format($bt['totaldealbet'])}}</span>
        </li>
        <li>
            <span class='text-red'>당첨 : {{number_format($bt['totaldealwin'])}}</span>
        </li>
        <li>
<<<<<<< HEAD
        롤링금 : {{number_format($bt['total_deal']>0?$bt['total_deal']:$bt['total_mileage'])}}
        </li>
        <li>
        정산금 : {{number_format($bt['totaldealbet']-$bt['totaldealwin'] - ($bt['total_deal']>0?$bt['total_deal']:$bt['total_mileage']))}}
=======
        롤링금 : {{number_format($bt['total_mileage'])}}
        </li>
        <li>
        정산금 : {{number_format($bt['totaldealbet']-$bt['totaldealwin'] - $bt['total_mileage'])}}
>>>>>>> 6e42aae5246f761b007c8e7f726bd4c247a5c346
        </li>
        </ul>
    </div>
    
    </div>
    @if ($loop->index < count($betwin)-1)
    <hr style="margin-top:0.5rem !important; margin-bottom:0.5rem !important;">
    @endif
@endforeach    
@endif
</td>
<td>
@if (isset($sumInfo) && $sumInfo!='')
    <span class='text-red'>준비중</span>
@else
    <ul>
    <li>총죽장 : {{ number_format($betwin['total']['total_ggr'],0)}}</li>
    <li>하부죽장 : {{ number_format($betwin['total']['total_ggr_mileage'],0)}}</li>
    <li>본인죽장 : {{ number_format($betwin['total']['total_ggr']-$betwin['total']['total_ggr_mileage'],0)}}</li>
    </ul>
@endif
</td>
<td>
@if (isset($sumInfo) && $sumInfo!='')
    <span class='text-red'>준비중</span>
@else

    <ul>
    <li>총롤링 : {{ number_format($betwin['total']['total_deal'],0)}}</li>
    <li>하부롤링 : {{ number_format($betwin['total']['total_mileage'],0)}}</li>
    <li>본인롤링 : {{ number_format($betwin['total']['total_deal']-$betwin['total']['total_mileage'],0)}}</li>
    </ul>
@endif
</td>
<td>
    <ul>
    <li>총보유금 : {{ number_format($adjustment->balance+$adjustment->childsum,0)}}</li>
    <li>본인 보유금 : {{ number_format($adjustment->balance,0)}}</li>
    <li>유저 보유금 : {{ number_format($adjustment->user_sum,0)}}</li>
    <li>파트너 보유금 : {{ number_format($adjustment->partner_sum,0)}}</li>
    </ul>
</td>
@if (isset($sumInfo) && $sumInfo!='')
@else
<td>
    <ul>
    <li>총롤링금 : {{ number_format($adjustment->deal_balance - $adjustment->deal_mileage + $adjustment->partner_dealsum + $adjustment->user_dealsum,0)}}</li>
    <li>본인 롤링금 : {{ number_format($adjustment->deal_balance - $adjustment->deal_mileage,0)}}</li>
    <li>유저 롤링금 : {{ number_format($adjustment->user_dealsum,0)}}</li>
    <li>파트너 롤링금 : {{ number_format($adjustment->partner_dealsum,0)}}</li>
    </ul>
</td>

@if (auth()->user()->hasRole('admin'))
<td>
    <?php
        $prevDay = $adjustment->prevDay;
        $margin = 0;
        if ($prevDay)
        {
            if ($adjustment->user->isInoutPartner())
            {
                $margin = ($adjustment->balance+$adjustment->childsum) - ($prevDay->balance + $prevDay->childsum + $adjustment->moneyin - $adjustment->moneyout - ($betwin['total']['totalbet']-$betwin['total']['totalwin']));
            }
            else
            {
                $margin = ($adjustment->balance+$adjustment->childsum) - ($prevDay->balance + $prevDay->childsum + $adjustment->totalin - $adjustment->totalout + $adjustment->moneyin - $adjustment->moneyout + $adjustment->dealout - ($betwin['total']['totalbet']-$betwin['total']['totalwin']));
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

@endif