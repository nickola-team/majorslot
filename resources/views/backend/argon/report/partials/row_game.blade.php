@if ($total)
<td>{{$user->username}}</td>
<?php
    $game = $stat->title;
    if ($gacmerge == 1)
    {
        $game = preg_replace('/GameArtCasino/', 'EvolutionVIP', $game);
    }
?>
<td>
@if (auth()->user()->hasRole(['admin','comaster']))
    <a href="{{argon_route('argon.report.game.details', ['cat_id'=>$stat->category_id, 'user_id'=>$user->id, 'dates' => Request::get('dates')])}}">{{$game}}</a>
@else
    {{$game}}
@endif
</td>
@if (auth()->user()->isInOutPartner())
<td>
    <ul>
    <li>
        <span class='text-green'>배팅 : {{number_format($stat->totalbet)}}</span>
    </li>
    <li>
        <span class='text-red'>당첨 : {{number_format($stat->totalwin)}}</span>
    </li>
    <li>
    롤링금 : {{number_format($stat->total_mileage)}}
    </li>
    <li>
    정산금 : {{number_format($stat->totalbet-$stat->totalwin-$stat->total_mileage)}}
    </li>
    </ul>
</td>
@endif
<td >
    <ul>
    <li>
        <span class='text-green'>배팅 : {{number_format($stat->totaldealbet)}}</span>
    </li>
    <li>
        <span class='text-red'>당첨 : {{number_format($stat->totaldealwin)}}</span>
    </li>
    <li>
    롤링금 : {{number_format($stat->total_mileage)}}
    </li>
    <li>
    정산금 : {{number_format($stat->totaldealbet-$stat->totaldealwin-$stat->total_mileage)}}
    </li>
    </ul>
</td>
<td>
    <ul>
        <li>총죽장 : {{ number_format($stat->total_ggr)}}</li>
        <li>하부죽장 : {{ number_format($stat->total_ggr_mileage)}}</li>
        <li>본인죽장 : {{ number_format($stat->total_ggr-$stat->total_ggr_mileage)}}</li>
    </ul>
</td>
<td>
    <ul>
        <li>총롤링 : {{ number_format($stat->total_deal)}}</li>
        <li>하부롤링 : {{ number_format($stat->total_mileage)}}</li>
        <li>본인롤링 : {{ number_format($stat->total_deal-$stat->total_mileage)}}</li>
    </ul>
</td>
@else
@foreach ($category['cat'] as $stat)
    @if ($loop->index > 0)
	<tr>
	@endif
    <td>{{$user->username}}</td>
    <?php
        $game = $stat['title'];
        if ($gacmerge == 1)
        {
            $game = preg_replace('/GameArtCasino/', 'EvolutionVIP', $game);
        }
    ?>
    <td>
    @if (auth()->user()->hasRole(['admin','comaster']))
        <a href="{{argon_route('argon.report.game.details', ['cat_id'=>$stat['category_id'], 'user_id'=>$user->id, 'dates'=>[$category['date'], $category['date']]])}}">{{$game}}</a>
    @else
        {{$game}}
    @endif
    </td>
    @if (auth()->user()->isInOutPartner())
    <td>
        <ul>
        <li>
            <span class='text-green'>배팅 : {{number_format($stat['totalbet'])}}</span>
        </li>
        <li>
            <span class='text-red'>당첨 : {{number_format($stat['totalwin'])}}</span>
        </li>
        <li>
        롤링금 : {{number_format($stat['total_mileage'])}}
        </li>
        <li>
        정산금 : {{number_format($stat['totalbet']-$stat['totalwin']-$stat['total_mileage'])}}
        </li>
        </ul>
    </td>
    @endif
    <td >
        <ul>
        <li>
            <span class='text-green'>배팅 : {{number_format($stat['totaldealbet'])}}</span>
        </li>
        <li>
            <span class='text-red'>당첨 : {{number_format($stat['totaldealwin'])}}</span>
        </li>
        <li>
        롤링금 : {{number_format($stat['total_mileage'])}}
        </li>
        <li>
        정산금 : {{number_format($stat['totaldealbet']-$stat['totaldealwin']-$stat['total_mileage'])}}
        </li>
        </ul>
    </td>
    <!-- <td class="bw-title">{{number_format($stat['totalbet'])}}
        @if (auth()->user()->hasRole('admin'))
        <div class="bw-btn ">
            <form method="POST">
                <input type='hidden' name="summaryid" value="{{$stat['id']}}">
                <input type='text' name="totalbet" value="{{$stat['totalbet']}}" style="width:80px;">
                <button type="submit" class="btn-sm btn-warning">수정</button>
            </form>
        </div>    
        @endif
    </td>
    <td class="bw-title">{{number_format($stat['totalwin'])}}
        @if (auth()->user()->hasRole('admin'))
        <div class="bw-btn ">
            <form method="POST">
                <input type='hidden' name="summaryid" value="{{$stat['id']}}">
                <input type='text' name="totalwin" value="{{$stat['totalwin']}}" style="width:80px;">
                <button type="submit" class="btn-sm btn-warning">수정</button>
            </form>
        </div>    
        @endif
    </td>
    <td>{{number_format($stat['totalbet']-$stat['totalwin'])}}</td> -->
    <td>
        <ul>
        <li>총죽장 : {{ number_format($stat['total_ggr'])}}</li>
        <li>하부죽장 : {{ number_format($stat['total_ggr_mileage'])}}</li>
        <li>본인죽장 : {{ number_format($stat['total_ggr']-$stat['total_ggr_mileage'],0)}}</li>
        </ul>
    </td>
	<td>
        <ul>
        <li>총롤링 : {{ number_format($stat['total_deal'])}}</li>
        <li>하부롤링 : {{ number_format($stat['total_mileage'])}}</li>
        <li>본인롤링 : {{ number_format($stat['total_deal']-$stat['total_mileage'],0)}}</li>
        </ul>
    </td>
    @if ($loop->index > 0)
	</tr>
	@endif
@endforeach
@endif
