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
@if (auth()->user()->hasRole('admin'))
    <a href="{{argon_route('argon.report.game.details', ['cat_id'=>$stat->category_id, 'dates' => Request::get('dates')])}}">{{$game}}</a>
@else
    {{$game}}
@endif
</td>
<td>{{number_format($stat->totalbet)}}</td>
<td>{{number_format($stat->totalwin)}}</td>
<td>{{number_format($stat->totalbet-$stat->totalwin)}}</td>
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
    @if (auth()->user()->hasRole('admin'))
        <a href="{{argon_route('argon.report.game.details', ['cat_id'=>$stat['category_id'],'dates'=>[$category['date'], $category['date']]])}}">{{$game}}</a>
    @else
        {{$game}}
    @endif
    </td>    
    <td>{{number_format($stat['totalbet'])}}</td>
    <td>{{number_format($stat['totalwin'])}}</td>
    <td>{{number_format($stat['totalbet']-$stat['totalwin'])}}</td>
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
